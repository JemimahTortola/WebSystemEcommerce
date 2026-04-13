<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('address.create')->with('info', 'Please add your shipping address to complete your profile.');
    }

    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The email or password you entered is incorrect.'],
            ]);
        }

        Auth::login($user);

        $isNewUser = $user->created_at->diffInMinutes(now()) < 1;
        $message = $isNewUser ? 'Welcome to Lux Littles, ' . $user->first_name . '!' : 'Welcome back, ' . $user->first_name . '!';
        
        return redirect('/')->with('success', $message);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'You have been logged out.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('frontend.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/profile_images', $filename);
            $user->update(['profile_image' => $filename]);
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function showAddressForm()
    {
        $user = Auth::user();
        return view('frontend.auth.address', compact('user'));
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);

        return redirect('/')->with('success', 'Welcome to Lux Littles, ' . $user->first_name . '!');
    }

    public function skipAddress()
    {
        return redirect('/')->with('success', 'Welcome to Lux Littles! You can add your address later in your profile settings.');
    }

    public function settings()
    {
        $user = Auth::user();
        return view('frontend.settings.index', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'marketing_emails' => 'nullable|boolean',
        ]);

        $user->update([
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
            'marketing_emails' => $request->has('marketing_emails'),
        ]);

        return redirect()->route('settings')->with('success', 'Settings updated successfully!');
    }

    public function wishlist()
    {
        return view('frontend.wishlist.index');
    }

    public function exportData(Request $request)
    {
        $user = $request->user();
        
        $data = [
            'user' => $user->toArray(),
            'orders' => $user->orders()->with('items')->get()->toArray(),
            'cart_items' => $user->cart->items()->with('product')->get()->toArray() ?? [],
            'reviews' => $user->reviews()->with('product')->get()->toArray(),
            'created_at' => now()->toISOString(),
        ];

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="my-data-' . date('Y-m-d') . '.json"')
            ->header('Content-Type', 'application/json');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->route('settings')->with('error', 'Incorrect password.');
        }

        $user->delete();

        Auth::logout();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
