<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RoleRedirectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show general admin/staff login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is inactive or not found.',
            ])->withInput();
        }

        // Validate role alignment (no customers allowed anymore)
        $isCustomer = $user->role && $user->role->name === 'Customer';
        if ($isCustomer) {
            return back()->withErrors([
                'email' => 'This page is for admin and staff login only.',
            ])->withInput();
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Record last login
            $user->update(['last_login_at' => now()]);

            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Helper to redirect authenticated users based on role.
     * 
     * Customer → portal dashboard
     * Owner/Super Admin → admin dashboard
     * All other staff → employee dashboard
     */
    protected function redirectUser($user)
    {
        return redirect(app(RoleRedirectService::class)->getDashboardRoute($user));
    }
}

