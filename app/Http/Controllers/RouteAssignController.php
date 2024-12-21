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

    // Store route assignment
    public function store(Request $request)
    {
        $request->validate([
            'rid' => 'required|exists:route,rid', // Validate Route ID
            'uid' => 'required|exists:systemuser,uid', // Validate User ID
        ]);

        try {
            // Check if this route assignment already exists
            $exists = RouteAssign::where('rid', $request->rid)
                                 ->where('uid', $request->uid)
                                 ->exists();

            if ($exists) {
                return response()->json(['message' => 'Assignment already exists.'], 422);
            }

            // Create a new route assignment
            RouteAssign::create([
                'rid' => $request->rid,
                'uid' => $request->uid,
                'uid_ro' => auth()->id(), // Assuming current user is the assigner
            ]);

            return response()->json(['message' => 'Route assigned successfully!'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to assign route.', 'error' => $e->getMessage()], 500);
        }
    }
}
