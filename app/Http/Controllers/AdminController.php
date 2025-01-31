<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Systemuser;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    // Show the password change form
    public function showChangePasswordForm()
    {
        return view('admin.changepassword');
    }

    // Handle password change logic
    public function changePassword(Request $request)
    {
        // Validate the password input
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
       
            'confirm_password' => 'required|same:new_password',
        ]);

        // Get the currently authenticated user
        $user = Systemuser::where('uid', Session::get('uid'))->first();

        // Check if the current password is correct
        if ( !$user  || !Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->password = $request->new_password;
        $user->save();

          // Log the user out and clear session data
    Auth::logout();
    Session::flush();

        // Redirect with success message
        // return redirect()->route()->with('success', 'Password changed successfully!');

        // Redirect with success message after password change
return redirect()->route('admin.logout')->with('success', 'Password changed successfully!');

    }

}
