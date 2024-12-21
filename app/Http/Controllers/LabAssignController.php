<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabAssign;
use App\Models\Systemuser;
use App\Models\Lab;
use App\Models\LabAssignLog;
use DB;

class LabAssignController extends Controller
{

    public function indexLabassign()
    {
        // Fetch lab assignments with related systemuser and lab data
        $labassigns = LabAssign::with(['systemuser', 'lab'])->get();

        // Passing $labassigns to the view
        return view('Admin.labassign', compact('labassigns'));
    }

    public function storeLabassign(Request $request)
    {
        // Validate the necessary fields
        $validated = $request->validate([
            'uid' => 'required|exists:systemuser,uid', // Validate user ID exists in `systemuser` table
            'uid_assign' => 'required|exists:systemuser,uid', // Assigned user ID exists in `systemuser` table
            'lid' => 'required|exists:lab,lid', // Validate lab ID exists in `lab` table
        ]);

        try {
            // Create the new lab assignment
            $labassign = LabAssign::create([
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
                'action' => 'inserted',
            ]);

            return redirect()->back()->with('success', 'Lab assignment saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to save lab assignment.']);
        }
    }

    public function updateLabassign(Request $request)
    {
        $validated = $request->validate([
            'uid' => 'required|exists:systemuser,uid',
            'uid_assign' => 'required|exists:systemuser,uid',
            'lid' => 'required|exists:lab,lid',
            'laid' => 'required|exists:labassign,laid',
        ]);

        try {
            $labassign = LabAssign::findOrFail($validated['laid']);
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

}