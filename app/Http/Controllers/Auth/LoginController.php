<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        // If already logged in → straight to dashboard
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    /**
     * Handle login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Block disabled users
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status === 'disabled') {
            return back()
                ->withErrors(['email' => 'Your account is disabled. Contact the administrator.'])
                ->withInput();
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()
                ->intended('/')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // Failed
        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput();
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out.');
    }
}