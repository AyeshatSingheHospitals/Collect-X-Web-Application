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

    public function editLabassign($id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid LabAssign ID.',
                ], 400);
            }

            $labAssign = LabAssign::with(['systemuser', 'lab'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $labAssign,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lab assignment details.',
            ], 500);
        }
    }

    /**
     * Update function to save edited details of a specific LabAssign.
     */
    public function updateLabassign(Request $request, $id)
    {
        $request->validate([
            'uid' => 'required|exists:systemuser,uid',
            'uid_assign' => 'required|exists:systemuser,uid',
            'epf' => 'required',
            'lab_name' => 'required|exists:lab,name',
            'lid' => 'required|exists:lab,lid',
        ]);

        try {
            DB::beginTransaction();

            $labAssigns = LabAssign::findOrFail($id);
            $labAssigns->uid = $request->input('uid');
            $labAssigns->uid_assign = $request->input('uid_assign');
            $labAssigns->lid = $request->input('lid');
            $labAssigns->save();

            LabAssignLog::create([
                'laid' => $labAssigns->laid, 
                'uid' => $labAssigns->uid,
                'uid_assign' => $labAssigns->uid_assign,
                'lid' => $labAssigns->lid,
                'action' => 'updated',
            ]);
           
            DB::commit();

            return redirect()
                ->route('admin.labassign.index')
                ->with('success', 'Lab assignment updated successfully!');
        
            }
            
          
        catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update lab assignment.']);
        }

        

    }

    public function destroyLabassign($id){

        $labAssigns = LabAssign::findOrFail($id);
        $labAssigns->delete();

           // update a corresponding log entry in the 'lablogs' table
           LabAssignLog::create([
            'laid' => $labAssigns->laid, 
            'uid' => $labAssigns->uid,
            'uid_assign' => $labAssigns->uid_assign,
            'lid' => $labAssigns->lid,        
            'action' => 'deleted', // Set action as 'insert'
        ]);

        return redirect()->route('admin.labassign.index')->with('success', 'Lab Assign deleted successfully!');
    }

}
