<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Route;
use App\Models\LabAssign;
use App\Models\Systemuser;

class RouteAssignController extends Controller
{
    public function searchLab(Request $request)
    {
        $labName = $request->input('name');
        
        // Search for the lab by name
        $lab = Lab::where('name', 'like', '%' . $labName . '%')->first();

        if (!$lab) {
            return response()->json(['message' => 'Lab not found'], 404);
        }

        // Get users assigned to the lab (role=RO)
        $assignedUsers = LabAssign::with('systemuser')
            ->where('lid', $lab->lid)
            ->whereHas('systemuser', function ($query) {
                $query->where('role', 'RO');
            })
            ->get();

        // Get routes related to the lab
        $routes = Route::where('lid', $lab->lid)->get();

        // Prepare data for the response
        $response = [
            'lab' => $lab,
            'users' => $assignedUsers->map(function ($assignment) {
                return [
                    'id' => $assignment->systemuser->uid,
                    'name' => $assignment->systemuser->full_name,
                ];
            }),
            'routes' => $routes->map(function ($route) {
                return [
                    'id' => $route->rid,
                    'name' => $route->routename,
                ];
            }),
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'assignments' => 'required|array',
            'assignments.*.uid' => 'required|exists:systemuser,uid',
            'assignments.*.uid_ro' => 'required|exists:systemuser,uid',
            'assignments.*.rid' => 'required|exists:route,rid',
        ], [
            'assignments.*.uid.required' => 'User ID is required.',
            'assignments.*.uid_ro.required' => 'Assigned User ID is required.',
            'assignments.*.rid.required' => 'Route ID is required.',
        ]);              

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $assignments = $request->input('assignments');

        foreach ($assignments as $assignment) {
            RouteAssign::updateOrCreate(
                [
                    'uid' => $assignment['uid'],
                    'uid_ro' => $assignment['uid_ro'],
                    'rid' => $assignment['rid'],
                ],
                [] // If additional fields are needed, include them here
            );
        }

        return response()->json(['message' => 'Assignments saved successfully']);
    }
    
}
