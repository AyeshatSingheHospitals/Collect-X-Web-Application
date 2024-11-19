<?php

namespace App\Http\Controllers;


use App\Models\Route;
use App\Models\RouteLog;
use Illuminate\Http\Request;


use App\Models\Lab;


class RouteController extends Controller
{
    // Show the form for creating a new route
    public function indexRoute()
    {
        $routes = Route::all();
        $labs = Lab::all();
        return view('Admin.route', compact('routes', 'labs')); // Make sure to create this Blade file

        }

    // Store a newly created route in storage
    public function storeRoutes(Request $request)
    {
        // Validate the request
        $request->validate([
            // 'uid' => 'required|int|exists:systemuser,uid', 
            // 'lid' => 'required|int|exists:lab,lid', 
            'uid' => 'required|string|max:255',
            'lid' => 'required|string|max:255',
            'routename' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create a new route
        $routes=Route::create($request->all());

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
    }

    public function updateRoute(Request $request, $rid)
    {
        // Validate the request data
        $request->validate([
            // 'uid' => 'required|int|exists:systemuser,uid', 
            // 'lid' => 'required|int|exists:lab,lid', 
            'uid' => 'required|string|max:255',
            'lid' => 'required|string|max:255',
            'routename' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        // Find and update the user
        $routes = Route::findOrFail($rid);
        $routes->uid = $request->uid;
        $routes->lid = $request->lid;
        $routes->routename = $request->routename;
        $routes->description = $request->description;
        $routes->save();

        
         // update a corresponding log entry in the 'routelogs' table
         RouteLog::create([
            'rid' => $routes->rid,
            'uid' => $routes->uid,
            'lid' => $routes->lid,
            'routename' => $routes->routename,
            'description' => $routes->description,
            'action' => 'updated', // Set action as 'insert'
        ]);
    
        // Redirect with success message
        return redirect()->route('admin.route.index')->with('success', 'Route updated successfully!');
    }

    public function destroyRoute($rid){

        $routes = Route::findOrFail($rid);
        $routes->delete();

           // update a corresponding log entry in the 'routelogs' table
           RouteLog::create([
            'rid' => $routes->rid,
            'uid' => $routes->uid,
            'lid' => $routes->lid,
            'routename' => $routes->routename,
            'description' => $routes->description,
            'action' => 'deleted', // Set action as 'insert'
        ]);

        return redirect()->route('admin.route.index')->with('success', 'Route deleted successfully!');

        
    }
}
