<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductReview;
use Illuminate\Support\Facades\DB;

class ProductReviewController extends Controller
{
    public function addProductReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'user_id' => 'required',
            'review' => 'required',
            'rating' => 'required|integer'
        ], [
            'product_id.required' => 'Product ID field is required.',
            'user_id.required' => 'User ID field is required.',
            'review.required' => 'Review field is required.',
            'rating.required' => 'Rating field is required.'
        ]);
    
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
    
        $userId = $request->user_id;
        $productId = $request->product_id;
        $review = $request->review;
        $rating = $request->rating;
        $isApproved = 0;
    
        try {
            DB::beginTransaction();
    
            $addReview = ProductReview::create([
                'product_id' => $productId,
                'user_id' => $userId,
                'review' => $review,
                'rating' => $rating,
                'is_approved' => $isApproved
            ]);
    
            DB::commit();
    
            if ($addReview) {
                return $this->successResponse($addReview, 'Product review added successfully');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add product review'
                ], 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }
    

    public function productReviewList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ], [
            'product_id.required' => 'Product ID field is required.',
        ]);
    
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        } 

        $productId = $request->product_id; 

        try {
            $query = ProductReview::where('product_id', $productId);
    
            if ($request->user_id) {
                $userId = $request->user_id;
                $query = $query->where('user_id', $userId);
            }
            if ($request->rating) {
                $rating = $request->rating;
                $query = $query->where('rating', $rating);
            }
    
            $productReviewList = $query->get();
    
            if (count($productReviewList) > 0) {
                return $this->successResponse($productReviewList, 'Product review list fetched successfully');
            } else {
                return $this->successResponse($productReviewList, 'No record found');
            }

        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
    

    public function updateProductReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_review_id' => 'required',
            'review' => 'required',
            'rating' => 'required|integer'
        ], [
            'product_review_id.required' => 'Product review ID field is required.',
            'review.required' => 'Review field is required.',
            'rating.required' => 'Rating field is required.'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $productReviewId = $request->product_review_id;
        $review = $request->review;
        $rating = $request->rating;

        try {
            DB::beginTransaction();

            $updateProductReview = ProductReview::where('id', $productReviewId)
                ->update(['review' => $review, 'rating' => $rating]);

            if ($updateProductReview) {
                DB::commit();
                $updatedReview = ProductReview::find($productReviewId);
                return $this->successResponse($updatedReview, 'Product review updated successfully');
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update product review'
                ], 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

}
