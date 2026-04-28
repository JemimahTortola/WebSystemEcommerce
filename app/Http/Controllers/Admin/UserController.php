<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users');
    }

    public function data(Request $request)
    {
        // Get user IDs that have customer role
        $customerRole = DB::table('roles')->where('name', 'customer')->first();
        if (!$customerRole) {
            return response()->json(['data' => []]);
        }
        
        $customerUserIds = DB::table('user_roles')
            ->where('role_id', $customerRole->id)
            ->pluck('user_id')
            ->toArray();
        
        $query = User::whereIn('id', $customerUserIds)
            ->withCount('orders')
            ->select(['id', 'name', 'email', 'phone', 'is_online', 'last_activity_at', 'banned_until', 'created_at']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($users);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user,
        ]);
    }

    public function ban(Request $request, User $user)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365',
            'reason' => 'nullable|string|max:500',
        ]);

        $user->ban($validated['days'], $validated['reason']);

        return response()->json([
            'message' => "User banned for {$validated['days']} days",
            'user' => $user,
        ]);
    }

    public function unban(User $user)
    {
        $user->unban();

        return response()->json([
            'message' => 'User unbanned successfully!',
            'user' => $user,
        ]);
    }
}
