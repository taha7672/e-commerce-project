<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FavouriteList;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UserFavouriteController extends Controller
{
    public function addRemoveFavouriteProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'user_id' => 'required',
            'variant_id' => 'required',
        ], [
            'product_id.required' => 'Product ID field is required.',
            'user_id.required' => 'User ID field is required.',
            'variant_id.required' => 'Variant ID field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $productId = $request->product_id;
        $userId = $request->user_id;
        $variantId = $request->variant_id;

        try {
            DB::beginTransaction();

            $checkFavourite = FavouriteList::where(['product_id' => $productId, 'user_id' => $userId, 'product_variant_id' => $variantId])->first();

            if (!empty($checkFavourite)) {
                FavouriteList::where(['product_id' => $productId, 'user_id' => $userId, 'product_variant_id' => $variantId])->delete();
                DB::commit();
                return $this->successResponse(null, 'Product removed from favourite list successfully');
            } else {
                FavouriteList::create(['product_id' => $productId, 'user_id' => $userId, 'product_variant_id' => $variantId]);
                DB::commit();
                return $this->successResponse(null, 'Product added to favourite list successfully');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }


    public function favouriteProductList(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $userId = $request->user_id;
        try {
            $favouriteList = FavouriteList::with('product', 'productVariant')->where('user_id', $userId)->get();

            if (count($favouriteList) > 0) {
                $favouriteList->transform(function ($favourite) {
                    if (!empty($favourite->product->image)) {
                        $favourite->product->image = url($favourite->product->image);
                    }
                    return $favourite;
                });
                return $this->successResponse($favouriteList, 'User Favourite list fetch Successfully.');
            } else {
                return $this->successResponse($favouriteList, 'No Record Found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
}
