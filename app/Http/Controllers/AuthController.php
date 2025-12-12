<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the welcome page.
     */
    public function showWelcome()
    {
        return view('welcome');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Try admin login first (admin uses 'admin' as email)
        $adminAttempt = Auth::guard('admin')->attempt(['email' => $email, 'password' => $password], $request->filled('remember'));
        
        if ($adminAttempt) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in successfully as admin.');
        }

        // Try user login
        $userAttempt = Auth::guard('web')->attempt(['email' => $email, 'password' => $password], $request->filled('remember'));
        
        if ($userAttempt) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in successfully.');
        }

        // If both failed, return with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        // Logout from both guards
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }

    /**
     * Toggle dark mode for the authenticated user or admin.
     */
    public function toggleDarkMode(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $admin->dark_mode = !$admin->dark_mode;
            $admin->save();
        } elseif (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $user->dark_mode = !$user->dark_mode;
            $user->save();
        }

        return back();
    }
}

