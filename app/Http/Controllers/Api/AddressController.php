<?php

namespace App\Http\Controllers\Api;

use App\Models\BillingAddress;
use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function addBillingAddress(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => ['required', 'regex:/^[0-9]{10,20}$/'],
            'email' =>  ['required', 'string', 'email', 'max:255'],
            'address_line1' => 'required',
            'address_line2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
            'address_line1.required' => 'Address field is required.',
            'city.required' => 'City field is required.',
            'state.required' => 'State field is required.',
            'postal_code.required' => 'Postal Code field is required.'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        try {

            $input = $request->all();
            $addBillingAddress = BillingAddress::updateOrCreate(
                ['user_id' => $input['user_id']],
                $input
            );

            if ($addBillingAddress) {
                return $this->successResponse($addBillingAddress, 'Billing Address added successfully');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add billing address'
                ], 500);
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }


    public function getBillingAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $userId = $request->user_id;
        try {
            $billingAddressList = BillingAddress::where('user_id', $userId)->first();

            if ($billingAddressList) {
                return $this->successResponse($billingAddressList, 'Billing Address list fetched successfully');
            } else {
                return $this->successResponse($billingAddressList, 'No record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }

    public function addShippingAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => ['required', 'regex:/^[0-9]{10,20}$/'],
            'email' =>  ['required', 'string', 'email', 'max:255'],
            'address_line1' => 'required',
            'address_line2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
            'address_line1.required' => 'Address field is required.',
            'city.required' => 'City field is required.',
            'state.required' => 'State field is required.',
            'postal_code.required' => 'Postal Code field is required.',


        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
        $input = $request->all();
        try {

            $addShippingAddress = ShippingAddress::updateOrCreate(
                ['user_id' => $input['user_id']],
                $input
            );

            DB::commit();

            if ($addShippingAddress) {
                return $this->successResponse($addShippingAddress, 'Shipping Address added successfully');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add shipping address'
                ], 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    public function getShippingAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $userId = $request->user_id;
        try {
            $shippingAddressList = ShippingAddress::where('user_id', $userId)->first();

            if ($shippingAddressList) {
                return $this->successResponse($shippingAddressList, 'Shipping Address list fetched successfully');
            } else {
                return $this->successResponse($shippingAddressList, 'No record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
}
