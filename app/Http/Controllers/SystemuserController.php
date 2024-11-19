<?php
namespace App\Http\Controllers;

use App\Models\Systemuser;
use Illuminate\Http\Request;

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
            'password' => bcrypt($request->password), // Hash the password
            'status' => $request->status,
            'image' => $request->image ? $request->image->store('images', 'public') : null, // Save image if uploaded
        ]);

        // Save the user to the database
        $users->save();

        // Redirect or return with success message
        return redirect()->route('admin.user.index')->with('success', 'User added successfully');
    }

    
    // SystemuserController.php
public function getUserNames()
{
    // Fetch all system users
    $users = Systemuser::select('fname', 'lname')->get();

    // Create a list of full names
    $userNames = $users->map(function($user) {
        return $user->fname . ' ' . $user->lname;
    });

    // Return the names as a JSON response
    return response()->json($userNames);
}

}
