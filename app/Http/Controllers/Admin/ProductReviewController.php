<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{
    public function index()
    {
        try {
            $reviews = ProductReview::with('product')->get();
            return view('admin.reviews.index', compact('reviews'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Failed to load reviews: ' . $e->getMessage());
        }
    }
    
    public function approve($id)
    {
        try {
            $review = ProductReview::findOrFail($id);
            $review->is_approved = true;
            $review->save();
            return redirect()->route('admin.reviews.index')->with('success',__('messages.review_approved'));
        } catch (\Exception $e) {
            return redirect()->route('admin.reviews.index')->with('error', 'Failed to approve review: ' . $e->getMessage());
        }
    }
}
