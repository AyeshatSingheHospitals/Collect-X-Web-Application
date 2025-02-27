<?php
namespace App\Http\Controllers;

use App\Models\Systemuser;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class SystemuserController extends Controller
{
    // Show the form to create a new user
    public function indexUser()
    {
        $users = Systemuser::all();
        return view('Admin.user', compact('users'));
    }

    // Store the new user in the database
    public function storeUsers(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'role' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact' => 'required|unique:systemuser,contact|digits:10', // Example for contact validation
            'epf' => 'required|unique:systemuser,epf',
            'username' => 'required|unique:systemuser,username|max:255',
            'password' => 'required|string|min:8',
            // 'status' => 'string',
             'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|max:5000', // Validation for image upload (if required)
        ]);

        // Create the new systemuser
        $users = new Systemuser([
            'role' => $request->role,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'contact' => $request->contact,
            'epf' => $request->epf,
            'username' => $request->username,
            'password' => $request->password, // Hash the password
            'status' => $request->status,
            'image' => $request->image ? $request->image->store('images', 'public') : null, // Save image if uploaded
        ]);

        // Save the user to the database
        $users->save();

        // Redirect or return with success message
        return redirect()->route('admin.user.index')->with('success', 'User added successfully');
    }

    public function editUser($id)
    {
        $user = Systemuser::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function updateUsers(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'role' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact' => 'required|digits:10|unique:systemuser,contact,' . $id . ',uid',
            'epf' => 'required|unique:systemuser,epf,' . $id . ',uid',
            'username' => 'required|unique:systemuser,username,' . $id . ',uid|max:255',
            'password' => 'nullable|string|min:8',
            'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|max:5000',
        ]);

        $user = Systemuser::findOrFail($id);

        // Update the user
        $user->update([
            'role' => $request->role,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'contact' => $request->contact,
            'epf' => $request->epf,
            'username' => $request->username,
            'status' => $request->status,
            'image' => $request->image ? $request->image->store('images', 'public') : $user->image,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully');
    }

    public function getUserNames()
{
    // Fetch all system users
    $users = Systemuser::where('status', 'active')->select('uid', 'fname', 'lname', 'epf')->get();

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


public function updatePassword(Request $request)
{
    // Validate the request
    $request->validate([
        'resetUserId' => 'required|exists:systemuser,uid', // Ensure the user exists
        'newPassword' => 'required|string|min:8',
        'confirmPassword' => 'required|same:newPassword', // Ensure passwords match
    ]);

    // Find the user
    $user = Systemuser::findOrFail($request->resetUserId);

    // Update the password
    $user->password = $request->newPassword;
    $user->save();

    // Redirect with success message
    return redirect()->route('admin.user.index')->with('success', 'Password updated successfully');
}

}