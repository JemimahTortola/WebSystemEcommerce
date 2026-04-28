<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'profile']);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        // Assign default customer role
        $customerRole = Role::where('name', 'customer')->first();
        if ($customerRole) {
            $user->roles()->attach($customerRole->id);
        } else {
            // If customer role doesn't exist, create and assign
            $newRole = Role::create(['name' => 'customer']);
            $user->roles()->attach($newRole->id);
        }

        Auth::login($user);

        return response()->json([
            'message' => 'Registration successful!',
            'redirect' => '/'
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is banned
            if ($user->isBanned()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                throw ValidationException::withMessages([
                    'email' => ['Your account has been suspended until ' . $user->banned_until->format('F j, Y') . '. Reason: ' . ($user->ban_reason ?? 'No reason provided')],
                ]);
            }
            
            $request->session()->regenerate();

            $redirect = $this->redirectTo();
            
            return response()->json([
                'message' => 'Login successful!',
                'redirect' => $redirect
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function profile(Request $request)
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
                'phone' => ['nullable', 'string', 'max:50'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            Auth::user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            return response()->json([
                'message' => 'Profile updated successfully!'
            ]);
        }

        return view('profile.index', ['user' => Auth::user()]);
    }

    public function notifications()
    {
        return view('notifications.index');
    }

    public function notificationsData()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }

    public function markNotificationRead(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        
        Auth::user()->notifications()
            ->where('id', $request->id)
            ->update(['is_read' => true]);
            
        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllNotificationsRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['message' => 'All notifications marked as read']);
    }

    protected function redirectTo()
    {
        return '/';
    }
}