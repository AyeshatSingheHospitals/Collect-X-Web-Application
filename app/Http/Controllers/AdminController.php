<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Systemuser;
use App\Models\SystemuserLog;

use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{

    // -------------------------------------Admin------------------------------------

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

                
    $loggedUid = session('uid',0); 

    // $user = Systemuser::findOrFail($id);
    SystemuserLog::create([
        'logged_uid' => $loggedUid,
        'uid' => $user->uid,
        'role' => $user->role,
        'fname' => $user->fname,
        'lname' => $user->lname,
        'contact' => $user->contact,
        'epf' => $user->epf,
        'username' => $user->username,
        'password' => $user->password, // Hash the password
        'status' => $user->status,
        'image' => $user->image,// Save image if uploaded
        'action' => 'changed password',
    ]);

          // Log the user out and clear session data
    Auth::logout();
    Session::flush();

        // Redirect with success message
        // return redirect()->route()->with('success', 'Password changed successfully!');

        // Redirect with success message after password change
return redirect()->route('admin.logout')->with('success', 'Password changed successfully!');

    }

        // -------------------------------------Incharge------------------------------------

    // Show the password change form
    public function showChangePasswordFormbyIncharge()
    {
        return view('incharge.changepassword');
    }

    // Handle password change logic
    public function changePasswordbyIncharge(Request $request)
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

                
    $loggedUid = session('uid',0); 

    // $user = Systemuser::findOrFail($id);
    SystemuserLog::create([
        'logged_uid' => $loggedUid,
        'uid' => $user->uid,
        'role' => $user->role,
        'fname' => $user->fname,
        'lname' => $user->lname,
        'contact' => $user->contact,
        'epf' => $user->epf,
        'username' => $user->username,
        'password' => $user->password, // Hash the password
        'status' => $user->status,
        'image' => $user->image,// Save image if uploaded
        'action' => 'changed password',
    ]);

          // Log the user out and clear session data
    Auth::logout();
    Session::flush();

        // Redirect with success message
        // return redirect()->route()->with('success', 'Password changed successfully!');

        // Redirect with success message after password change
return redirect()->route('incharge.logout')->with('success', 'Password changed successfully!');

    }

        // -------------------------------------Supervisor------------------------------------

    // Show the password change form
    public function showChangePasswordFormbySupervisor()
    {
        return view('supervisor.changepassword');
    }

    // Handle password change logic
    public function changePasswordbySupervisor(Request $request)
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


                
    $loggedUid = session('uid',0); 

    // $user = Systemuser::findOrFail($id);
    SystemuserLog::create([
        'logged_uid' => $loggedUid,
        'uid' => $user->uid,
        'role' => $user->role,
        'fname' => $user->fname,
        'lname' => $user->lname,
        'contact' => $user->contact,
        'epf' => $user->epf,
        'username' => $user->username,
        'password' => $user->password, // Hash the password
        'status' => $user->status,
        'image' => $user->image,// Save image if uploaded
        'action' => 'changed password',
    ]);
          // Log the user out and clear session data
    Auth::logout();
    Session::flush();

        // Redirect with success message
        // return redirect()->route()->with('success', 'Password changed successfully!');

        // Redirect with success message after password change
return redirect()->route('supervisor.logout')->with('success', 'Password changed successfully!');

    }

    
}
