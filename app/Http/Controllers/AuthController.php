<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Systemuser; // Your Systemuser model

class AuthController extends Controller
{

    public function login(Request $request)
{
    try {
        // Validate the input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string|in:Admin,Supervisor,Incharge', // Valid roles
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        

        // Hardcoded Admin Credentials
        if ($role === 'Admin' && $username === 'admin' && $password === env('ADMIN_PASSWORD', 'Singhe@123')) {
            Session::put('role', 'Admin');
            Session::put('username', $username);
            Session::put('fname', 'Admin');

            return redirect('/admin/dashboard')->with('success', 'Welcome Admin!');
        }

        // Retrieve the user by role and username
        $user = Systemuser::where('role', $role)
                          ->where('username', $username)
                          ->first();

        // Check if the user exists and is active
        if ($user && $user->status === 'active') {
            // Validate password
            if (Hash::check($password, $user->password)) {
                // Store user data in session
                Session::put('uid', $user->uid);
                Session::put('username', $user->username);
                Session::put('role', $user->role);
                Session::put('fname', $user->fname);
                Session::put('image', $user->image);


                // Redirect based on role
                if ($role === 'Admin') {
                    return redirect('/admin/dashboard')->with('success', 'Welcome Admin!');
                } elseif ($role === 'Supervisor') {
                    return redirect('/Supervisor/dashboard')->with('success', 'Welcome Supervisor!');
                } elseif ($role === 'Incharge') {
                    return redirect('/Incharge/dashboard')->with('success', 'Welcome Incharge!');
                }
            } else {
                return redirect('/')->with('error', 'Invalid credentials.');
            }
        } else {
            return redirect('/')->with('error', 'Account inactive or does not exist.');
        }
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Login error', [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect('/')->with('error', 'An unexpected error occurred during sign-in.');
    }
}

    /**
     * Logout the user
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
