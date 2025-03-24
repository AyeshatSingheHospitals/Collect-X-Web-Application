<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\LabAssign;
use App\Models\RouteAssign;

use App\Models\Systemuser;
use App\Models\Lab;
use App\Models\LabAssignLog;
use DB;

class LabAssignController extends Controller
{

//---------------------------------------------admin's function---------------------------------------------------


public function getUserNames()
{
    // Fetch all system users
    $users = Systemuser::where('status', 'active')->whereIn('role', ['Incharge', 'Supervisor','RO'])->select('uid', 'fname', 'lname', 'epf')->get();

    // Create a list of full names with EPF and UID
    $userDetails = $users->map(function ($user) {
        return [
            'uid' => $user->uid,
            'full_name' => $user->fname . ' ' . $user->lname,
            'epf' => $user->epf,
        ];
    });

    // Return the user details as a JSON response
    return response()->json($userDetails);
}

public function getLabNames()
{
    try {
        // Fetch all system users
        $labs = Lab::select('lid', 'name')->get();

        // Create a list of full names with IDs
        $labsDetails = $labs->map(function ($labs) {
            return [
                'lid' => $labs->lid,
                'name' => $labs->name,
            ];
        });

        return response()->json($labsDetails);
    } catch (\Exception $e) {
        Log::error("Error fetching lab names: " . $e->getMessage());
        return response()->json(['error' => 'An error occurred while fetching lab names.'], 500);
    }
}

    public function indexLabassign()
    {
        // Fetch lab assignments with related systemuser and lab data
        $labassigns = LabAssign::with(['systemuser', 'lab'])->get();

          //------------ Check for empty lab assignments
          if ($labassigns->isEmpty()) {
            return view('Admin.labassign', compact('labassigns'))
                ->with('warning', 'No lab assignments available at the moment.');
        }

        // Log warning if any lab or systemuser is missing
        foreach ($labassigns as $labassign) {
            if (!$labassign->systemuser) {
                Log::warning("Systemuser not found for LabAssign ID: {$labassign->id}");
            }
            if (!$labassign->lab) {
                Log::warning("Lab not found for LabAssign ID: {$labassign->id}");
            }
        }
            // ---------------------

        // Passing $labassigns to the view
        return view('Admin.labassign', compact('labassigns'));
    }

    public function storeLabassign(Request $request)
{
    try {
        // Validate the necessary fields
        $validated = $request->validate([
            'uid' => 'required|exists:systemuser,uid', // Validate user ID exists in `systemuser` table
            'uid_assign' => 'required|exists:systemuser,uid', // Assigned user ID exists in `systemuser` table
            'lid' => 'required|exists:lab,lid', // Validate lab ID exists in `lab` table
        ]);
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->validator->errors())->withInput();
    }

    try {
        // Ensure the assigned user exists
        $user = Systemuser::find($validated['uid_assign']);
        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Assigned user not found.'])->withInput();
        }

        // Check if the user being assigned has the role 'RO'
        if ($user->role == 'RO') {
            $existingLabAssign = LabAssign::where('uid_assign', $validated['uid_assign'])->first();
            if ($existingLabAssign) {
                return redirect()->back()->withErrors(['error' => 'RO user cannot be assigned more than one lab.'])->withInput();
            }
        }
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Error checking user role: ' . $e->getMessage()])->withInput();
    }

    try {
        // Create the new lab assignment
        $labassign = LabAssign::create([
            'uid' => $validated['uid'],
            'uid_assign' => $validated['uid_assign'],
            'lid' => $validated['lid'],
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Database error while creating lab assignment: ' . $e->getMessage()])->withInput();
    }

    try {
        // Create the log for this action
        LabAssignLog::create([
            'laid' => $labassign->laid, // Using the newly created `laid`
            'uid' => $labassign->uid,
            'uid_assign' => $labassign->uid_assign,
            'lid' => $labassign->lid,
            'action' => 'inserted',
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Failed to create log entry: ' . $e->getMessage()])->withInput();
    }

    return redirect()->back()->with('success', 'Lab assignment saved successfully!');
}

public function updateLabassign(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'uid' => 'required|exists:systemuser,uid',
        'uid_assign' => 'required|exists:systemuser,uid',
        'lid' => 'required|exists:lab,lid',
        'laid' => 'required|exists:labassign,laid',
    ]);

    try {
        // Find the existing lab assignment record
        $labassign = LabAssign::findOrFail($validated['laid']);

        // Check if any changes were made
        if (
            $labassign->uid == $validated['uid'] &&
            $labassign->uid_assign == $validated['uid_assign'] &&
            $labassign->lid == $validated['lid']
        ) {
            // No changes made, return with an info message
            return redirect()->back()->with('info', 'No changes were made.');
        }

        // Update the lab assignment if changes exist
        $labassign->update([
            'uid' => $validated['uid'],
            'uid_assign' => $validated['uid_assign'],
            'lid' => $validated['lid'],
        ]);

        // Create the log for this action
        LabAssignLog::create([
            'laid' => $labassign->laid, // Using the newly created `laid`
            'uid' => $labassign->uid,
            'uid_assign' => $labassign->uid_assign,
            'lid' => $labassign->lid,
            'action' => 'updated',
        ]);

        return redirect()->back()->with('success', 'Lab assignment updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Failed to update lab assignment.']);
    }
}


    public function destroyLabassign($id)
    {
        $labAssign = LabAssign::findOrFail($id);
        $labAssign->delete();

        // Log the deletion in the LabAssignLog
        LabAssignLog::create([
            'laid' => $labAssign->laid,
            'uid' => $labAssign->uid,
            'uid_assign' => $labAssign->uid_assign,
            'lid' => $labAssign->lid,
            'action' => 'deleted',
        ]);

        return redirect()->route('admin.labassign.index')->with('success', 'Lab assignment deleted successfully!');
    }



    //-----------------------------------------Supervisor's Functions------------------------------------------------------------

    public function getAssignedLabs(Request $request)
    {
        $uid = session('uid'); // Get the logged-in user's UID
        if (!$uid) {
            return response()->json(['error' => 'User not logged in'], 401);
        }

        // Fetch lab assignments for the logged-in user
        $assignedLabs = LabAssign::where('uid_assign', $uid)
            ->with('lab') // Eager load the related lab
            ->get();

        // Return the labs in a suitable format for the dropdown
        $labs = $assignedLabs->map(function ($assignment) {
            return [
                'lid' => $assignment->lab->lid,
                'name' => $assignment->lab->name,
            ];
        });

        return response()->json($labs);
    }

    public function getLabAssignments(Request $request)
    {

        // $labassigns = LabAssign::all();

        $labId = $request->input('lid'); // Get the selected lab ID from the request

        // Retrieve assigned officers for the selected lab
        $assignments = LabAssign::where('lid', $labId)
            ->with('systemuser') // Eager load the related systemuser
            ->whereHas('systemuser', function($query){
                $query->where('role','RO');
            })
            ->get();

        // Return the assignments as a JSON response
        return response()->json($assignments);
    }

}