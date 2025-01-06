<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\LabLog;

class LabController extends Controller
{
    // Show the form
    public function indexLab()
    {
        try {
            $labs = Lab::all();

            if ($labs->isEmpty()) {
                return view('Admin.lab', compact('labs'))
                    ->with('warning', 'No labs available at the moment.');
            }

            foreach ($labs as $lab) {
                $systemUserName = optional($lab->systemuser)->name ?? 'No user assigned';
                Log::info("Lab {$lab->name} is managed by: {$systemUserName}");
            }

            return view('Admin.lab', compact('labs'));
        } catch (\Exception $e) {
            Log::error("Error fetching labs: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching labs.');
        }
    }

    // Handle form submission and save data to the database
    public function storeLabs(Request $request)
    {
        try {
            $request->validate([
                'uid' => 'required|exists:systemuser,uid',
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ]);

            // Create a new lab entry
            $labs = Lab::create($request->all());

            // Create a corresponding log entry in the 'lablogs' table
            LabLog::create([
                'lid' => $labs->lid,
                'uid' => $labs->uid,
                'name' => $labs->name,
                'address' => $labs->address,
                'action' => 'inserted', // Set action as 'insert'
            ]);

            return redirect()->route('admin.lab.index')->with('success', 'Lab added successfully!');
        } catch (\Exception $e) {
            Log::error("Error adding lab: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the lab.');
        }
    }

    public function updateLabs(Request $request, $lid)
    {
        try {
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
                'action' => 'updated', // Set action as 'update'
            ]);

            return redirect()->route('admin.lab.index')->with('success', 'Lab updated successfully!');
        } catch (\Exception $e) {
            Log::error("Error updating lab with ID {$lid}: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the lab.');
        }
    }

    public function destroyLab($lid)
    {
        try {
            $labs = Lab::findOrFail($lid);
            $labs->delete();

            // Create a corresponding log entry in the 'lablogs' table
            LabLog::create([
                'lid' => $labs->lid,
                'uid' => session('uid'),
                'name' => $labs->name,
                'address' => $labs->address,
                'action' => 'deleted', // Set action as 'delete'
            ]);

            return redirect()->route('admin.lab.index')->with('success', 'Lab deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Error deleting lab with ID {$lid}: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the lab.');
        }
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
}
