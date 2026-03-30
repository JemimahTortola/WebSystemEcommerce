<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = User::where('is_admin', false);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->latest()->get();
        
        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function show($id)
    {
        $customer = User::with(['orders', 'orders.items'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }
}
