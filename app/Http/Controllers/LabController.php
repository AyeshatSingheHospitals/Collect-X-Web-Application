<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;


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
            'uid' => 'required|exists:systemuser,uid', 
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Create a new lab entry

            $labs=Lab::create($request->all());

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
            'uid' => 'required|exists:systemuser,uid', 
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

         // Find and update the lab
         $labs = Lab::findOrFail($lid);
         $labs->uid = $request->uid;
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
            'uid' => session('uid'),
            'name' => $labs->name,
            'address' => $labs->address,           
            'action' => 'deleted', // Set action as 'insert'
        ]);

        return redirect()->route('admin.lab.index')->with('success', 'Lab deleted successfully!');
    }

    public function getLabNames()
    {
        // Fetch all system users
        $labs = Lab::select('name')->get();

        // Create a list of full names with EPF
        $labsDetails = $labs->map(function ($labs) {
            return [
                'name' => $labs->name,
            ];
        });

        // Return the user details as a JSON response
        return response()->json($labsDetails);
    }
}
