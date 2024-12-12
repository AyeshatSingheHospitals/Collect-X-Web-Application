<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;


use App\Models\Center;
use App\Models\CenterLog;

use App\Models\Lab;
use App\Models\Route;
use App\Models\Systemuser;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    // Show the form for creating a new center
    public function indexCenter()
    {
        $centers = Center::all();
        $labs = Lab::all();
        $routes = Route::all();
        $users = Systemuser::all();

        return view('Admin.centers', compact('centers', 'labs', 'routes', 'users'));
    }

    // Store a newly created center
    public function storeCenters(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'uid' => 'required|exists:systemuser,uid',
            'rid' => 'required|exists:route,rid',
            'lid' => 'required|exists:lab,lid',
            'centername' => 'required|string|max:255|unique:center',
            'authorizedperson' => 'required|string|max:255',
            'authorizedcontact' => 'required|string|max:255|unique:center',
            'selectedcontact' => 'required|string|max:255',
            'thirdpartycontact' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        // Create center record
        $centers = Center::create($validated);

        // Optional: Log creation action (if you have a log model)
        CenterLog::create([
            'cid' => $centers->cid ,
            'uid' => $centers->uid ,
            'rid' => $centers->rid,
            'lid' => $centers->lid,
            'centername' => $centers->centername,
            'authorizedperson' => $centers->authorizedperson,
            'authorizedcontact' => $centers->authorizedcontact,
            'selectedcontact' => $centers->selectedcontact,
            'thirdpartycontact' => $centers->thirdpartycontact,
            'description' => $centers->description,
            'latitude' => $centers->latitude,
            'longitude' => $centers->longitude,
            'action' => 'inserted',
        ]);

        // Redirect with success message
        return redirect()->route('admin.center.index')->with('success', 'Center created successfully.');
    }

    public function getRouteById($rid)
    {
        // Fetch the route by ID
        $route = Route::find($rid);

        if ($route) {
            return response()->json([
                'success' => true,
                'route' => $route,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Route not found',
            ], 404);
        }
    }
 
    public function updateCenter(Request $request, $cid){
        //validate the request data
        $request->validate([
            'uid' => 'required|exists:systemuser,uid',
            'rid' => 'required|exists:route,rid',
            'lid' => 'required|exists:lab,lid',
            'centername' => 'required|string|max:255',
            'authorizedperson' => 'required|string|max:255',
            'authorizedcontact' => 'required|string|max:255',
            'selectedcontact' => 'required|string|max:255',
            'thirdpartycontact' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);


          // Find and update the record
          $centers = Center::findOrFail($cid);
          $centers->uid = $request->uid;
          $centers->rid = $request->rid;
          $centers->lid = $request->lid;
          $centers->centername = $request->centername;
          $centers->authorizedperson = $request->authorizedperson;
          $centers->authorizedcontact = $request->authorizedcontact;
          $centers->selectedcontact = $request->selectedcontact;
          $centers->thirdpartycontact = $request->thirdpartycontact;
          $centers->description = $request->description;
          $centers->latitude = $request->latitude;
          $centers->longitude = $request->longitude;
          $centers->save();

         // update a corresponding log entry in the 'routelogs' table
         CenterLog::create([
            'cid' => $centers->cid,
            'uid' => $centers->uid ,
            'rid' => $centers->rid,
            'lid' => $centers->lid,
            'centername' => $centers->centername,
            'authorizedperson' => $centers->authorizedperson,
            'authorizedcontact' => $centers->authorizedcontact,
            'selectedcontact' => $centers->selectedcontact,
            'thirdpartycontact' => $centers->thirdpartycontact,
            'description' => $centers->description,
            'latitude' => $centers->latitude,
            'longitude' => $centers->longitude,
            'action' => 'updated',
        ]);

         // Redirect with success message
         return redirect()->route('admin.center.index')->with('success', 'Center updated successfully!');
    }


    public function destroyCenter($cid)
    {
        try {
            // Find the route by ID
            $centers = Center::findOrFail($cid);

            // Delete the route
            $centers->delete();

            // Log the deletion in the 'routelogs' table with the logged-in user's UID
            CenterLog::create([
                    'cid' => $centers->cid,
                    'uid' =>  session('uid') ,
                    'rid' => $centers->rid,
                    'lid' => $centers->lid,
                    'centername' => $centers->centername,
                    'authorizedperson' => $centers->authorizedperson,
                    'authorizedcontact' => $centers->authorizedcontact,
                    'selectedcontact' => $centers->selectedcontact,
                    'thirdpartycontact' => $centers->thirdpartycontact,
                    'description' => $centers->description,
                    'latitude' => $centers->latitude,
                    'longitude' => $centers->longitude,
                    'action' => 'deleted',
            ]);

            return redirect()->route('admin.center.index')->with('success', 'Center deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.center.index')->withErrors('Failed to delete center: ' . $e->getMessage());
        }
    }
}
