<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The email or password you entered is incorrect.'],
            ]);
        }

        $user = Auth::user();

        if (!$user->is_admin) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Access denied. Admin privileges required.'],
            ]);
        }

        $request->session()->regenerate();

        $isNewAdmin = $user->created_at->diffInMinutes(now()) < 1;
        $message = $isNewAdmin ? 'Welcome to the Dashboard, ' . $user->name . '!' : 'Welcome back, ' . $user->name . '!';
        
        return redirect()->route('admin.dashboard')->with('success', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function adminProfile()
    {
        return view('admin.profile');
    }

    public function adminSettings()
    {
        return view('admin.settings');
    }
}
