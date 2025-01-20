<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Route;
use App\Models\LabAssign;
use App\Models\Systemuser;
use App\Models\RouteAssign;

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

    // Get existing route assignments for users in the lab
    $existingAssignments = RouteAssign::whereIn('uid_ro', $assignedUsers->pluck('systemuser.uid'))
        ->get()
        ->groupBy('uid_ro')
        ->map(function ($assignments) {
            return $assignments->pluck('rid')->toArray();
        });

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
        'assignments' => $existingAssignments, // Assignments grouped by user
    ];

    return response()->json($response);
}


public function showAssignedRoutes()
{
    // Get all route assignments for the current user
    $assignedRoutes = RouteAssign::with(['route', 'systemuser'])->get();

    // Get all available routes
    $routes = Route::all();

    // Group the routes by user UID
    $groupedAssignments = $assignedRoutes->groupBy('systemuser.uid')->map(function ($assignments) {
        $user = $assignments->first()->systemuser; // Get the user details
        $routeIds = $assignments->pluck('route.rid')->toArray(); // Get all assigned route IDs for the user

        return [
            'user' => $user,
            'routes' => $routeIds,
        ];
    });

    return view('admin.rassign', compact('groupedAssignments', 'routes'));
}

    public function storeAssignments(Request $request){
            $assignments = $request->input('assign', []);

            if (empty($assignments)) {
                return redirect()->back()->with('error', 'No assignments were selected.');
            }

            // Iterate through the assignments and store them
            foreach ($assignments as $userId => $routes) {
                foreach ($routes as $routeId => $value) {
                    if ($value) { // Check if the checkbox was selected
                        \App\Models\RouteAssign::updateOrCreate(
                            ['uid' => $request->input('uid'), 'rid' => $routeId],
                            ['uid_ro' => $userId] // Assigning user performing the action
                        );
                    }
                }
            }

            return redirect()->back()->with('success', 'Assignments saved successfully.');
    }

    
}
