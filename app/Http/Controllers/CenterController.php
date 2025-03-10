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
        try {
            // Fetch data
            $centers = Center::all();
            $labs = Lab::all();
            $routes = Route::all();
            $users = Systemuser::all();

            // Return view with data
            return view('Admin.centers', compact('centers', 'labs', 'routes', 'users'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error loading centers: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->route('admin.center.index')
                ->withErrors('An unexpected error occurred while loading centers: ' . $e->getMessage());
        }
    }

    // Store a newly created center
    public function storeCenters(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'uid' => 'required|exists:systemuser,uid',
                'rid' => 'required|exists:route,rid',
                'lid' => 'required|exists:lab,lid',
                'centername' => 'required|string|max:255|unique:center',
                'authorizedperson' => 'required|string|max:255',
                'authorizedcontact' => 'required|digits:10|numeric|unique:center',
                'selectedcontact' => 'required|string|max:255',
                'thirdpartycontact' => 'required|digits:10|numeric',
                'description' => 'nullable|string',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
            ]);

            // Create center record
            $centers = Center::create($validated);

            // Log creation action (if you have a log model)
            CenterLog::create([
                'cid' => $centers->cid,
                'uid' => $centers->uid,
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
        } catch (\Exception $e) {
            return redirect()->route('admin.center.index')->withErrors('Failed to create center: ' . $e->getMessage());
        }
    }

    public function getRouteById($rid)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching route: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateCenters(Request $request, $cid)
    {
        try {
            // Validate the request data
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
    
            // Find the center to be updated
            $centers = Center::findOrFail($cid);
    
            // Check if any fields are actually changed
            $changesMade = false;
    
            if ($centers->uid != $request->uid) {
                $centers->uid = $request->uid;
                $changesMade = true;
            }
    
            if ($centers->rid != $request->rid) {
                $centers->rid = $request->rid;
                $changesMade = true;
            }
    
            if ($centers->lid != $request->lid) {
                $centers->lid = $request->lid;
                $changesMade = true;
            }
    
            if ($centers->centername != $request->centername) {
                $centers->centername = $request->centername;
                $changesMade = true;
            }
    
            if ($centers->authorizedperson != $request->authorizedperson) {
                $centers->authorizedperson = $request->authorizedperson;
                $changesMade = true;
            }
    
            if ($centers->authorizedcontact != $request->authorizedcontact) {
                $centers->authorizedcontact = $request->authorizedcontact;
                $changesMade = true;
            }
    
            if ($centers->selectedcontact != $request->selectedcontact) {
                $centers->selectedcontact = $request->selectedcontact;
                $changesMade = true;
            }
    
            if ($centers->thirdpartycontact != $request->thirdpartycontact) {
                $centers->thirdpartycontact = $request->thirdpartycontact;
                $changesMade = true;
            }
    
            if ($centers->description != $request->description) {
                $centers->description = $request->description;
                $changesMade = true;
            }
    
            if ($centers->latitude != $request->latitude) {
                $centers->latitude = $request->latitude;
                $changesMade = true;
            }
    
            if ($centers->longitude != $request->longitude) {
                $centers->longitude = $request->longitude;
                $changesMade = true;
            }
    
            // If no changes were made, return with a message
            if (!$changesMade) {
                return redirect()->back()->with('info', 'No changes were made.');
            }
    
            // Save the center if there are any changes
            $centers->save();
    
            // Create a log entry if updates were made
            CenterLog::create([
                'cid' => $centers->cid,
                'uid' => $centers->uid,
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
                'action' => 'updated', // Log action as 'updated'
            ]);
    
            // Redirect with a success message
            return redirect()->route('admin.center.index')->with('success', 'Center updated successfully!');
        } catch (\Exception $e) {
            // Return an error if an exception occurs
            return redirect()->route('admin.center.index')->withErrors('Failed to update center: ' . $e->getMessage());
        }
    }
    

    public function destroyCenter($cid)
    {
        try {
            // Find the center by ID
            $centers = Center::findOrFail($cid);

            // Delete the center
            $centers->delete();

            // Log the deletion in the 'centerlogs' table
            CenterLog::create([
                'cid' => $centers->cid,
                'uid' => session('uid'),
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

            // Return success message
            return redirect()->route('admin.center.index')->with('success', 'Center deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.center.index')->withErrors('Failed to delete center: ' . $e->getMessage());
        }
    }
}
