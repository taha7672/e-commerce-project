<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VariantAttribute;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VariantAttributeController extends Controller
{      
    public function variantAttributeList(Request $request)
    {
        try {
                $query = VariantAttribute::with('subCategory');
                if($request->name)
                {
                    $name = $request->name;
                    $query = $query->where('variant_attributes.attribute_name', 'LIKE', '%'.$name.'%'); 
                }
                $variantAttributes=$query->get();
                if(!empty($variantAttributes))
                {
                    if(count($variantAttributes)>0)
                    {
                    return $this->successResponse($variantAttributes,'Variant Attributes list fetch successfully');
                    }
                    else
                    {
                        return $this->successResponse($variantAttributes,'No Record found');
                    }
                }
                else
                {
                    return $this->successResponse($variantAttributes,'No Record found');
                }
            } catch (\Throwable $th) {
                return $this->serverException($th);
            }    
    }
}
