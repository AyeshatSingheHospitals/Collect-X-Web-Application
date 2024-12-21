<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Route;
use App\Models\RouteLog;
use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Systemuser;

class RouteController extends Controller
{
    // Show the form for creating a new route
    public function indexRoute()
    {
        try {
            $routes = Route::all();
            $labs = Lab::all();
            $users = Systemuser::all();
            return view('Admin.route', compact('routes', 'labs', 'users'));
        } catch (\Exception $e) {
            return redirect()->route('admin.route.index')->withErrors('Failed to load routes: ' . $e->getMessage());
        }
    }

    // Store a newly created route in storage
    public function storeRoutes(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'uid' => 'required|exists:systemuser,uid', 
                'lid' => 'required|exists:lab,lid', 
                'routename' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            // Create a new route
            $routes = Route::create($request->all());

            // Create a corresponding log entry in the 'routelogs' table
            RouteLog::create([
                'rid' => $routes->rid,
                'uid' => $routes->uid,
                'lid' => $routes->lid,
                'routename' => $routes->routename,
                'description' => $routes->description,
                'action' => 'inserted', // Set action as 'insert'
            ]);

            // Redirect or return with a success message
            return redirect()->route('admin.route.index')->with('success', 'Route created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.route.index')->withErrors('Failed to create route: ' . $e->getMessage());
        }
    }

    public function updateRoute(Request $request, $rid)
    {
        try {
            // Validate the request data
            $request->validate([
                'uid' => 'required|exists:systemuser,uid', 
                'lid' => 'required|exists:lab,lid',
                'routename' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
        
            // Find and update the route
            $routes = Route::findOrFail($rid);
            $routes->uid = $request->uid;
            $routes->lid = $request->lid;
            $routes->routename = $request->routename;
            $routes->description = $request->description;
            $routes->save();

            // Update a corresponding log entry in the 'routelogs' table
            RouteLog::create([
                'rid' => $routes->rid,
                'uid' => $routes->uid,
                'lid' => $routes->lid,
                'routename' => $routes->routename,
                'description' => $routes->description,
                'action' => 'updated', // Set action as 'updated'
            ]);
        
            // Redirect with success message
            return redirect()->route('admin.route.index')->with('success', 'Route updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.route.index')->withErrors('Failed to update route: ' . $e->getMessage());
        }
    }

    public function destroyRoute($rid)
    {
        try {
            // Find the route by ID
            $routes = Route::findOrFail($rid);

            // Delete the route
            $routes->delete();

            // Log the deletion in the 'routelogs' table with the logged-in user's UID
            RouteLog::create([
                'rid' => $routes->rid,
                'uid' => session('uid'), 
                'lid' => $routes->lid,
                'routename' => $routes->routename,
                'description' => $routes->description,
                'action' => 'deleted', // Log the 'deleted' action
            ]);

            return redirect()->route('admin.route.index')->with('success', 'Route deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.route.index')->withErrors('Failed to delete route: ' . $e->getMessage());
        }
    }

    public function getRoutes($labId)
    {
        try {
            // Fetch routes associated with the selected lab ID
            $routes = Route::where('lid', $labId)->get();

            // Return the routes as JSON
            return response()->json(['routes' => $routes]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch routes: ' . $e->getMessage()], 500);
        }
    }
}
