<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coupons,admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons=Coupon::get();
        return view('admin.coupons.index',compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'coupon_code' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_percentage' => 'required|integer',
            'discount_amount' => 'required|integer',
            'expiry_date' => 'required',
            'minimum_order_amount' => 'required|integer',
            'one_time_use' => 'required',
        ]);
        // dd($request->all());
        // dd($validatedData);

        DB::beginTransaction();

        try {

            // Create Coupon
            $coupon = Coupon::create([
                'coupon_code' => $validatedData['coupon_code'],
                'discount_type' => $validatedData['discount_type'] ?? null,
                'discount_percentage' => $validatedData['discount_percentage'],
                'discount_amount' => $validatedData['discount_amount'],
                'expiry_date' => $validatedData['expiry_date'],
                'minimum_order_amount' => $validatedData['minimum_order_amount'],
                'one_time_use' => $validatedData['one_time_use'],
                'currency_id' => defaultCurrency()->id,
            ]);

            DB::commit();

            return redirect()->route('admin.coupons.index')->with('success', __('messages.coupon_created'));
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => __('messages.something_went_wrong'),
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon=Coupon::where('id', $id)->first();
        return view('admin.coupons.edit',compact('coupon'));

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'coupon_code' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_percentage' => 'required|integer',
            'discount_amount' => 'required|integer',
            'expiry_date' => 'required',
            'minimum_order_amount' => 'required|integer',
            'one_time_use' => 'required',
        ]);


        DB::beginTransaction();

        try {
            // Update the product
            $coupon = Coupon::where('id', $id)->update([
                'coupon_code' => $validatedData['coupon_code'],
                'discount_type' => $validatedData['discount_type'] ?? null,
                'discount_percentage' => $validatedData['discount_percentage'],
                'discount_amount' => $validatedData['discount_amount'],
                'expiry_date' => $validatedData['expiry_date'],
                'minimum_order_amount' => $validatedData['minimum_order_amount'],
                'one_time_use' => $validatedData['one_time_use'],
                'currency_id' => defaultCurrency()->id,
            ]);

            DB::commit();

            return redirect()->route('admin.coupons.index')->with('success', __('messages.coupon_updated'));
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error updating coupon: ' . $e->getMessage());

            return redirect()->route('admin.coupons.index')->with('error', __('messages.something_went_wrong'));
  
        }
    }



}
