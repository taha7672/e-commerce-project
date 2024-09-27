<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use App\Models\SitesSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function getSiteSetting(Request $request)
    {
        $siteSetting = SitesSetting::all();
        foreach ($siteSetting as $setting) {
            if ($setting->key == 'logo_url') {
                if (!empty($setting->value)) {
                    $setting->value = url($setting->value);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Site setting fetched successfully',
            'data' => $siteSetting
        ]);
    }

    // update site setting
    public function updateSiteSetting(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $objSite = SitesSetting::where('key', $request->key)->first();
            if (empty($objSite)) {
                $objSite = new SitesSetting();
            }
            $objSite->key = $request->key;
            $objSite->value = $request->value;
            $objSite->save();


            DB::commit();
            return $this->successResponse($objSite, 'Site setting updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverException($e);
        }
    }
    public function currencyList(Request $request)
    {
        try {
            $currencyList = Currency::all();
            if ($currencyList->isNotEmpty()) {
                return $this->successResponse($currencyList, 'Currency list fetched successfully');
            } else {
                return $this->successResponse($currencyList, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
    public function languageList(Request $request)
    {
        try {
            $languageList = Language::all();
            if ($languageList->isNotEmpty()) {
                return $this->successResponse($languageList, 'Language list fetched successfully');
            } else {
                return $this->successResponse($languageList, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
}
