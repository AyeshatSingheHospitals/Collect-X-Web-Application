<?php

namespace App\Http\Controllers;

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
          return view('Admin.centers', compact('centers','labs','routes','users')); 
  
       }

    public function storeCenters(Request $request)
    {
        // Validate the form data
        $request->validate([

            // 'uid' => 'required|exists:systemuser,id',  Ensure the user ID exists
            // 'rid' => 'required|int|exists:route,rid', 
          
            'uid' => 'required|string|max:255', 
            'rid' => 'required|string|max:255', 
            'lid' => 'required|string|max:255',

            'centername' => 'required|string|max:255',
            'authorizedperson' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:10',
            'description' => 'nullable|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        // Save the validated data to the database
        // Center::create($validatedData);

        $centers=Center::create($request->all());

         // Create a corresponding log entry in the 'routelogs' table
         CenterLog::create([
            'cid' => $centers->cid,
            'uid' => $centers->uid,
            'rid' => $centers->rid,
            'lid' => $centers->lid,

            'centername' => $centers->centername,
            'authorizedperson' => $centers->authorizedperson,
            'phonenumber' => $centers->phonenumber,
            'description' => $centers->description,
            'latitude' => $centers->latitude,
            'longitude' => $centers->longitude,
            'action' => 'inserted', // Set action as 'insert'
        ]);

        // Redirect with success message
        // return redirect()->back()->with('success', 'Center created successfully!');

        return redirect()->route('admin.center.index')->with('success', 'Center created successfully.');

    }
}
