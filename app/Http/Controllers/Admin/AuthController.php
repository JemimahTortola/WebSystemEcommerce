<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The email or password you entered is incorrect.'],
            ]);
        }

        Auth::guard('admin')->login($admin);

        $isNewAdmin = $admin->created_at->diffInMinutes(now()) < 1;
        $message = $isNewAdmin ? 'Welcome to the Dashboard, ' . $admin->name . '!' : 'Welcome back, ' . $admin->name . '!';
        
        return redirect()->route('admin.dashboard')->with('success', $message);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
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
