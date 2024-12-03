<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabAssign;
use App\Models\Systemuser;
use App\Models\Lab;


class LabAssignController extends Controller
{

    public function indexLabassign()
    {
        $labassigns = LabAssign::all();
        return view('Supervisor.labassign', compact('labassigns'));
    }

    public function storeLabassigns(Request $request)
    {
        // Validate the form data with custom error messages
        $request->validate([
            'uid' => 'required|exists:systemusers,uid',
            'lid' => 'required|exists:labs,id',
            'uid_assign' => 'required|exists:systemusers,uid',
        ], [
            'uid.required' => 'The user ID is required.',
            'lid.required' => 'The lab ID is required.',
            'uid_assign.required' => 'The assigning user ID is required.',
            'uid.exists' => 'The selected user does not exist.',
            'lid.exists' => 'The selected lab does not exist.',
            'uid_assign.exists' => 'The assigning user does not exist.',
        ]);

        try {
            // Create a new LabAssign record
            LabAssign::create([
                'uid' => $request->input('uid'),
                'lid' => $request->input('lid'),
                'uid_assign' => $request->input('uid_assign'),
            ]);

            return redirect()->route('admin.labassigns.index')->with('success', 'Lab assigned successfully!');
        } catch (\Exception $e) {
            // Handle any errors
            return redirect()->back()->withErrors(['error' => 'An error occurred while assigning the lab.']);
        }
    }
}
