<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Route;
use App\Models\LabAssign;
use App\Models\Systemuser;
use App\Models\RouteAssign;
use App\Models\RouteAssignLog;


class RouteAssignByInchargeController extends Controller
{
    public function searchLab(Request $request)
    {
        $labId = $request->input('lid');
    
        if (!$labId) {
            return response()->json(['message' => 'Lab ID is required'], 400);
        }
    
        // Find the lab by ID
        $lab = Lab::find($labId);
    
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
    
    

 // In RouteAssignBySupervisorController.php

public function showAssignedRoutes()
{
    // Get the UID of the logged-in user
    $userId = session('uid');

    // Retrieve labs assigned to the logged-in user
    $labs = LabAssign::with('lab')  // Assuming `LabAssign` has a relationship with `Lab` model
                    ->where('uid_assign', $userId)
                    ->get()
                    ->pluck('lab');  // Extracting the related labs

    return view('incharge.rassign', compact('labs'));
}

    
    
public function storeAssignments(Request $request)
{
    $assignments = $request->input('assign', []);

    if (empty($assignments)) {
        return redirect()->back()->with('error', 'No assignments were selected.');
    }

    // Iterate through the assignments and store them
    foreach ($assignments as $userId => $routes) {
        foreach ($routes as $routeId => $value) {
            if ($value) { // Check if the checkbox was selected
                // Check if the RouteAssign exists
                $routeAssign = \App\Models\RouteAssign::where('rid', $routeId)
                    ->first();

                if ($routeAssign) {
                    // Update only if the responsible officer (uid_ro) is different
                    if ($routeAssign->uid_ro != $userId) {
                        // Update the existing RouteAssign record
                        $routeAssign->update(['uid_ro' => $userId]);
                        $routeAssign->update(['uid' => $request->input('uid')]);


                        // Log the update action
                        RouteAssignLog::create([
                            'raid' => $routeAssign->raid,
                            'uid' => $routeAssign->uid,  // Supervisor's UID
                            'rid' => $routeAssign->rid,
                            'uid_ro' => $routeAssign->uid_ro,
                            'action' => 'updated', // Logging update action
                        ]);
                    }
                } else {
                    // Create new RouteAssign if it doesn't exist
                    $routeAssign = \App\Models\RouteAssign::create([
                        'uid' => $request->input('uid'),
                        'rid' => $routeId,
                        'uid_ro' => $userId,
                    ]);

                    // Log the insert action
                    RouteAssignLog::create([
                        'raid' => $routeAssign->raid,
                        'uid' => $routeAssign->uid,  // Supervisor's UID
                        'rid' => $routeAssign->rid,
                        'uid_ro' => $routeAssign->uid_ro,
                        'action' => 'inserted', // Logging insert action
                    ]);
                }
            }
        }
    }

    return redirect()->back()->with('success', 'Assignments saved successfully.');
}






}
