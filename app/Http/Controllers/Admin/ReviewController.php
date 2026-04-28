<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews');
    }

    public function data(Request $request)
    {
        $query = Review::with(['product', 'user']);

        if ($request->search) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($reviews);
    }

    public function show(Review $review)
    {
        return response()->json($review->load(['product', 'user']));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'admin_comment' => 'nullable|string',
        ]);

        $review->update($validated);

        return response()->json([
            'message' => 'Review updated successfully!',
            'review' => $review,
        ]);
    }

    public function toggle(Review $review)
    {
        $review->update(['is_visible' => !$review->is_visible]);

        return response()->json([
            'message' => 'Review visibility updated!',
            'review' => $review,
        ]);
    }
}