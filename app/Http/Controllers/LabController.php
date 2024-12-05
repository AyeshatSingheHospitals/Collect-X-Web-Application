<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\LabLog;


class LabController extends Controller
{
    // Show the form
    public function indexLab()
    {
        $labs = Lab::all();
        return view('Admin.lab', compact('labs'));
    }

    // Handle form submission and save data to the database
    public function storeLabs(Request $request)
    {
        $request->validate([
            'uid' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Create a new lab entry

        $labs=Lab::create([
            'uid' => $request->input('uid'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
        ]);

          // Create a corresponding log entry in the 'lablogs' table
          LabLog::create([
            'lid' => $labs->lid,
            'uid' => $labs->uid,
            'name' => $labs->name,
            'address' => $labs->address,
            'action' => 'inserted', // Set action as 'insert'
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.lab.index')->with('success', 'Lab added successfully!');
    }

    public function updateLabs(Request $request, $lid)
    {
        $request->validate([
            'uid' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

         // Find and update the lab
         $labs = Lab::findOrFail($lid);
         $labs->name = $request->name;
         $labs->address = $request->address;
         $labs->save();
       

        // Create a corresponding log entry in the 'lablogs' table
           LabLog::create([
            'lid' => $labs->lid,
            'uid' => $labs->uid,
            'name' => $labs->name,
            'address' => $labs->address,
            'action' => 'updated', // Set action as 'insert'
        ]);

        // Redirect with success message
        return redirect()->route('admin.lab.index')->with('success', 'Lab updated successfully!');
    }

    public function destroyLab($lid){

        $labs = Lab::findOrFail($lid);
        $labs->delete();

           // update a corresponding log entry in the 'lablogs' table
           LabLog::create([
            'lid' => $labs->lid,
            'uid' => $labs->uid,
            'name' => $labs->name,
            'address' => $labs->address,           
            'action' => 'deleted', // Set action as 'insert'
        ]);

        return redirect()->route('admin.lab.index')->with('success', 'Lab deleted successfully!');

        
    }

//     public function getLabNames()
// {
//     $labs = \App\Models\Lab::all(); // Assuming the model is named 'Lab'
//     return response()->json($labs);
// }

    public function getLabNames()
    {
        // Fetch all system users
        $labs = Lab::select('lid','name')->get();
        // $users = Systemuser::select('uid', 'fname', 'lname', 'epf')->get();

        // Create a list of full names with EPF
        $labsDetails = $labs->map(function ($labs) {
            return [
                'lid' => $labs->lid,
                'name' => $labs->name,
            ];
        });

        // Return the user details as a JSON response
        return response()->json($labsDetails);
    }

}
