<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Systemuser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Display the sign-in form
    public function showSignInForm()
    {
        return view('auth.signin');
    }

    public function signIn(Request $request)
    {
        try {
            $role = $request->input('role');
            $username = $request->input('username');
            $password = $request->input('password');

            // Check for admin role using environment variables
            if ($role === 'Admin' && $username === env('ADMIN_USERNAME') && $password === env('ADMIN_PASSWORD')) {
                return redirect('/Admin/dashboard');
            }

            // Check Systemuser credentials
            $systemuser = Systemuser::where('role', $role)
                ->where('username', $username)
                ->first();

            if ($systemuser && Hash::check($password, $systemuser->password)) {
                // Set session data
                Session::put('userId', $systemuser->uid);
                Session::put('userName', $systemuser->fname . ' ' . $systemuser->lname);

                // Redirect based on role
                switch (strtolower($systemuser->role)) {
                    case 'Admin':
                        return redirect('/Admin/dashboard');
                    case 'Supervisor':
                        return redirect('/Incharge/dashboard');
                    default:
                        return redirect('/')->with('error', 'Unrecognized role.');
                }
            }

            // Log failed attempt and show error
            Log::warning('Failed login attempt', ['role' => $role, 'username' => $username]);
            return redirect('/')->with('error', 'Invalid credentials or role.');

        } catch (\Exception $e) {
            Log::error('Exception during sign in', ['exception' => $e]);
            return redirect('/')->with('error', 'An error occurred. Please try again.');
        }
    }
}

