<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\District;
use App\Models\Village;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LocalityController extends Controller
{
	
    public function provincesList(Request $request)
    {
        try {  
            $provinces = Province::all(); 
            if ($provinces->isNotEmpty()) {  
                return $this->successResponse($provinces, 'Province list fetched successfully');
            } else {
               
                return $this->successResponse($provinces, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
    public function districtsList($province_id, Request $request)
    {
        try {  
            $districts = District::with('villages')->where('province_id', $province_id)->get(); 
            if ($districts->isNotEmpty()) {  
                return $this->successResponse($districts, 'Districts list fetched successfully');
            } else {
               
                return $this->successResponse($districts, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
	
    public function villagesList($district_id, Request $request)
    {
        try {  
            $villages = Village::where('district_id', $district_id)->get(); 
            if ($villages->isNotEmpty()) {  
                return $this->successResponse($villages, 'Villages list fetched successfully');
            } else {
               
                return $this->successResponse($villages, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
}