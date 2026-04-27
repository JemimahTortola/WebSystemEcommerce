<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class PageController extends Controller
{
    public function home()
    {
        $featuredProducts = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->where('products.is_active', true)
            ->limit(4)
            ->get();
            
        return view('home', compact('featuredProducts'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSend(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string'
        ]);
        
        try {
            // Get mail address from config, with fallback
            $mailFrom = config('mail.from.address', 'hello@flourista.com');
            
            // Only try to send if we have a valid email
            if ($mailFrom && filter_var($mailFrom, FILTER_VALIDATE_EMAIL)) {
                Mail::to($mailFrom)->send(new ContactMail($validated));
            }
            
            // Return JSON for AJAX or redirect for regular form
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your message has been sent successfully!',
                    'success' => true
                ]);
            }
            
            return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Contact form error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to send message. Please try again.',
                    'error' => 'Mail sending failed'
                ], 500);
            }
            
            return redirect()->route('contact')->with('error', 'Failed to send message. Please try again.');
        }
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function deliveryAreas()
    {
        return view('delivery-areas');
    }

    public function trackOrder(Request $request)
    {
        return view('track-order');
    }
}