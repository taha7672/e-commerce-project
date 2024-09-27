<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ShoppingCart;
use App\Models\CartDetail;
use App\Models\SitesSetting;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class AddToCartController extends Controller
{
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'products' => 'required|array',
            'products.*.product_id' => 'required',
            'products.*.variant_id' => 'required',
            'products.*.price' => 'required',
            'products.*.quantity' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
            'products.required' => 'Products field is required.',
            'products.array' => 'Products field must be an array.',
            'products.*.product_id.required' => 'Product ID field is required for each product.',
            'products.*.variant_id.required' => 'Variant ID field is required for each product.',
            'products.*.price.required' => 'Price field is required for each product.',
            'products.*.quantity.required' => 'Quantity field is required for each product.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $userId = $request->user_id;
        $products = $request->products;

        try {
            DB::beginTransaction();
            $checkCart = ShoppingCart::where('user_id', $userId)->first();

            if (!empty($checkCart)) {
                $cartId = $checkCart->id;
            } else {
                $cart = ShoppingCart::create(['user_id' => $userId]);
                $cartId = $cart->id;
            }

            foreach ($products as $product) {
                $productId = $product['product_id'];
                $quantity = $product['quantity'];
                $price = $product['price'];
                $variantId = $product['variant_id'];

                $checkCartDetails = CartDetail::where(['cart_id' => $cartId, 'product_id' => $productId, 'variant_id' => $variantId])->first();

                if (!empty($checkCartDetails)) {
                    $cartDetailId = $checkCartDetails->id;
                    $newQuantity = $checkCartDetails->quantity + $quantity;
                    $newPrice = $price * $newQuantity;
                    CartDetail::where(['id' => $cartDetailId])->update(['quantity' => $newQuantity, 'price_at_purchase' => $newPrice]);
                } else {
                    CartDetail::create([
                        'cart_id' => $cartId,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price_at_purchase' => $quantity * $price,
                        'variant_id' => $variantId
                    ]);
                }
            }

            DB::commit();

            return $this->successResponse(null, 'Products added to cart successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    public function cartDetails(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'cart_id' => 'required',
        // ], [
        //     'cart_id.required' => 'Cart ID field is required.',
        // ]);
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
        ]);
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
        $userId = $request->user_id;
        $checkCart = ShoppingCart::where('user_id', $userId)->first();
        if (empty($checkCart)) {
            return $this->errorResponse('Cart is empty.', 422);
        }
        $cartId = $checkCart->id;
        try {
            $cartDetails = CartDetail::with(['product', 'variant'])->where(['cart_id' => $cartId])->get();
            $cartDetails->each(function ($cartDetail) {
                if ($cartDetail->product) {
                    $cartDetail->product->image = url($cartDetail->product->small_image);
                    $cartDetail->product->small_image = url($cartDetail->product->small_image);
                    $cartDetail->product->medium_image = url($cartDetail->product->medium_image);
                }
            });
            $details = ['cart_details' => $cartDetails, 'shipping_amount' => 0];

            // Calculate Shipping
            $totalCost = 0;
            foreach ($cartDetails as $cart) {
                $totalCost += $cart->price_at_purchase;
            }
            $shipping_amount = getSetting('shipping_amount');
            $free_shipping_threshold = getSetting('free_shipping_threshold');
            if (!empty($shipping_amount) && !empty($free_shipping_threshold)) {
                if ($free_shipping_threshold <= $totalCost) {
                    $shipping_amount = 0;
                }
                $details['shipping_amount'] = $shipping_amount;
            }

            return $this->successResponse($details, 'Cart Details fetch Successfully');
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }

    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'nullable|integer|min:0',
            'variant_id' => 'required|integer|min:0',
        ], [
            'user_id.required' => 'User ID field is required.',
            'product_id.required' => 'Product ID field is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity must be at least 0.',
            'variant_id.integer' => 'Variant ID must be an integer.',
            'variant_id.min' => 'Variant ID must be at least 0.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $userId = $request->user_id;
        $productId = $request->product_id;
        $quantity = $request->quantity;
        $variantId = $request->variant_id;

        $checkCart = ShoppingCart::where('user_id', $userId)->first();

        if (empty($checkCart)) {
            return $this->errorResponse('Cart is empty.', 422);
        }

        $cartId = $checkCart->id;

        try {
            DB::beginTransaction();

            $checkCartDetails = CartDetail::where(['cart_id' => $cartId, 'product_id' => $productId, 'variant_id' => $variantId])->first();

            if (!empty($checkCartDetails)) {
                if (isset($quantity) && $quantity > 0 && isset($variantId) && $variantId > 0) {
                    $priceAtPurchase = $checkCartDetails->price_at_purchase;
                    $unitPrice = $priceAtPurchase / $checkCartDetails->quantity;
                    $newPrice = $unitPrice * $quantity;
                    CartDetail::where(['cart_id' => $cartId, 'product_id' => $productId, 'variant_id' => $variantId])->update(['quantity' => $quantity, 'price_at_purchase' => $newPrice]);
                } else {
                    CartDetail::where(['cart_id' => $cartId, 'product_id' => $productId, 'variant_id' => $variantId])->delete();
                }
            }


            DB::commit();
            return $this->successResponse(null, 'Item updated/removed from Cart.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    public function applyCouponToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'coupon_code' => 'required',
        ], [
            'cart_id.required' => 'Cart ID field is required.',
            'coupon_id.required' => 'Coupon Code field is required.',
        ]);
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
        $cartId = $request->cart_id;
        $couponCode = $request->coupon_code;
        $cartDetails = CartDetail::where(['cart_id' => $cartId])->get();
        $couponDetails = Coupon::where('coupon_code', $couponCode)->first();
        $totalAmount = 0;
        $discountAmount = 0;
        $newAmount = 0;
        if (empty($couponDetails)) {
            return $this->errorResponse('Invalid Coupon.', 422);
        }
        if (count($cartDetails) > 0) {
            foreach ($cartDetails as $details) {
                $totalAmount += $details->price_at_purchase;
            }
        }
        $couponExpiryDate = $couponDetails->expiry_date;
        $discountType = $couponDetails->discount_type;
        $fixedDiscountAmount = $couponDetails->discount_amount;
        $discountPercentage = $couponDetails->discount_percentage;
        $givenDate = Carbon::parse($couponExpiryDate); // Replace with your date
        $currentDate = Carbon::now();

        if ($givenDate < $currentDate) {
            return $this->errorResponse('Invalid Coupon.', 422);
        }
        if (!empty($couponDetails->minimum_order_amount)) {
            $minimumAmount = $couponDetails->minimum_order_amount;
            if ($totalAmount < $minimumAmount) {
                return $this->errorResponse('Your order amount is not sufficent to apply coupon.', 422);
            }
        }
        if ($discountType == config('constants.Fixed')) {
            $discountAmount = $fixedDiscountAmount;
            $newAmount = $totalAmount - $discountAmount;
        }

        if ($discountType == config('constants.Percentage')) {
            $discountAmount = ($totalAmount * $discountPercentage) / 100;
            $newAmount = $totalAmount - $discountAmount;
        }

        $result = [
            "total_amount" => $totalAmount,
            "discount_amount" => round($discountAmount, 2),
            "after_discount_total_amount" => round($newAmount, 2)
        ];

        return $this->successResponse($result, 'Coupon Applied Successfully.');
    }
}
