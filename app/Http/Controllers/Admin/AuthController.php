<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $user = \App\Models\User::where('email', $request->email)
            ->where('is_admin', true)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        Auth::login($user);

        $isNewUser = $user->created_at->diffInMinutes(now()) < 1;
        $message = $isNewUser ? 'Welcome to the Dashboard, ' . $user->name . '!' : 'Welcome back, ' . $user->name . '!';
        
        return redirect()->route('admin.dashboard')->with('success', $message);
    }

    public function logout()
    {
        Auth::logout();
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
