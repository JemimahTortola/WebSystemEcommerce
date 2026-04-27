<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Categories
Route::get('/categories', fn() => response()->json(\App\Models\Category::withCount('products')->get()));
Route::post('/categories', [CategoryController::class, 'store']);

// Reviews
Route::get('/reviews', fn() => response()->json(\App\Models\Review::with(['user', 'product'])->get()));
