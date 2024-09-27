<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function couponList(Request $request)
    {

        try {
                $query = Coupon::where('id','>',0);
                if($request->type)
                {
                    $type = $request->type;
                    $query = $query->where('coupons.discount_type', $type);
                }
                $coupons=$query->get();
                if(!empty($coupons))
                {
                    if(count($coupons)>0)
                    {
                        $coupons->transform(function ($coupon) {
                            $coupon->image = url($coupon->image);
                            return $coupon;
                        });

                    return $this->successResponse($coupons,'Coupons list fetch successfully');
                    }
                    else
                    {
                        return $this->successResponse($coupons,'No Record found');
                    }
                }
                else
                {
                    return $this->successResponse($coupons,'No Record found');
                }
            } catch (\Throwable $th) {
                return $this->serverException($th);
            }

    }
    public function filterCouponList(Request $request)
    {
        try {
            $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();

            if (!empty($coupon)) {
                return $this->successResponse($coupon,'Coupon Data fetch successfully');
            } else {
                return $this->successResponse($coupon,'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
}
