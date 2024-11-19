<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
// use App\Http\Controllers\RouteController;


class CenterController extends Controller
{

      // Show the form for creating a new center
      public function indexCenter()
      {
          $centers = Center::all();
          return view('Admin.centers', compact('centers')); // Make sure to create this Blade file
  
       }

    public function storeCenters(Request $request)
    {
        // Validate the form data
        $request->validate([

            // 'uid' => 'required|exists:systemuser,id',  Ensure the user ID exists
            'uid' => 'required|int|exists:systemuser,uid', // Adjust validation rules as needed

            'rid' => 'required|int|exists:route,rid',       // Ensure the route ID exists
            'centername' => 'required|string|max:255',
            'authorizedperson' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:15',
            'description' => 'nullable|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        // Save the validated data to the database
        // Center::create($validatedData);

        Center::create($request->all());

        // Redirect with success message
        // return redirect()->back()->with('success', 'Center created successfully!');

        return redirect()->route('admin.center.index')->with('success', 'Center created successfully.');

    }
}
