<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Route;
use App\Models\LabAssign;
use App\Models\Systemuser;

class RouteAssignController extends Controller
{
    // public function searchLab($searchValue)
    // {
    //     // Search for labs by name
    //     $labs = Lab::where('name', 'LIKE', '%' . $searchValue . '%')->get();
    
    //     $response = [];
        
    //     foreach ($labs as $lab) {
    //         $labRoutes = Route::where('lid', $lab->lid)->get(); // Fetch routes for this lab
    //         $assignedUsers = LabAssign::where('lid', $lab->lid)
    //             ->with(['systemuser' => function ($query) {
    //                 $query->where('role', 'RO'); // Filter users with role "RO"
    //             }])->get();
            
    //         // Prepare the data to send back
    //         $response['labs'][] = [
    //             'lab' => $lab,
    //             'routes' => $labRoutes,
    //             'users' => $assignedUsers->map(function($assign) {
    //                 return $assign->systemuser; // Return only Systemuser data
    //             })->filter() // Remove null entries (users without the role "RO")
    //         ];
    //     }
        
    //     return response()->json($response);
    // }

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
    
}
