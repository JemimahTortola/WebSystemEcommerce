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

    public function toggle(Review $review)
    {
        $review->update(['is_visible' => !$review->is_visible]);

        return response()->json([
            'message' => 'Review visibility updated!',
            'review' => $review,
        ]);
    }
}