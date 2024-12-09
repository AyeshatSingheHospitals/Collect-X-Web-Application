<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabAssign;
use App\Models\Systemuser;
use App\Models\Lab;
use App\Models\LabAssignLog;

class LabAssignController extends Controller
{

    public function indexLabassign()
{
    // Fetch lab assignments with related systemuser and lab data
    $labassigns = LabAssign::with(['systemuser', 'lab'])->get();

    // Passing $labassigns to the view
    return view('Admin.labassign', compact('labassigns'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'uid' => 'required|exists:systemuser,uid', // Validate user ID exists in `systemuser` table
            'uid_assign' => 'required|exists:systemuser,uid', // Assigned user ID exists in `systemuser` table
            'lid' => 'required|exists:lab,lid', // Validate lab ID exists in `lab` table
        ]);

        try {
            $labassigns=LabAssign::create([
                'uid' => $validated['uid'],
                'uid_assign' => $validated['uid_assign'],
                'lid' => $validated['lid'],
            ]);

            LabAssignLog::create([
                'laid' => $labassigns->laid, 
                'uid' => $labassigns->uid, // Validate user ID exists in `systemuser` table
                'uid_assign' => $labassigns->uid_assign, // Assigned user ID exists in `systemuser` table
                'lid' => $labassigns->lid,
                'action' => 'inserted',
]);


            return redirect()->back()->with('success', 'Lab assignment saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to save lab assignment.']);
        }
    }
}
