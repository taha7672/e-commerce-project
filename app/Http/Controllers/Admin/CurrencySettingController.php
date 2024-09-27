<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\SitesSetting;
use Illuminate\Http\Request;

class CurrencySettingController extends Controller
{

    public function __construct() {
        $this->middleware('permission:settings,admin');
    }
    
    public function index() {
        $currency = Currency::all();
        return view('admin.settings.currency.index', compact('currency'));
    }

    public function save(Request $request) {
        $request->validate([
            "currency_name.*" => "required",
            "currency_code.*" => "required|max:3",
            "exchange_rate_to_usd.*" => "required|numeric|gt:0",
        ]);

        $codes = [];
        foreach ($request->currency_code as $uuid => $c) {
            $codes[$uuid] = strtoupper($c);
        }

        if ( $this->hasDuplicate($codes) ) {
            return response()->json([
                "status" => "error",
                "error" => "Country Code should be unique",
            ], 400);
        }

        $models = Currency::whereIn('currency_code', array_values($codes))->get();
        $hash = $models->mapWithKeys(fn($m) => [$m->currency_code => $m]);

        foreach ($codes as $uuid => $code) {
            $cur = $hash[$code] ?? null;

            if ( !$cur ) {
                $cur = new Currency;
                $cur->currency_code = $code;
            }

            $cur->currency_name = $request->currency_name[$uuid];
            $cur->exchange_rate_to_usd = $request->exchange_rate_to_usd[$uuid];
            $cur->save();
        }

        return response()->json(["status" => "success"]);
    }

    protected function hasDuplicate(array $data) {
        $store = [];
        foreach ($data as $d) {
            if ( in_array($d, $store) ) {
                return true;
            }

            $store[] = $d;
        }

        return false;
    }

    public function delete(Request $request) {
        $request->validate([
            "code" => "required"
        ]);

        $cur = Currency::where('currency_code', $request->code)->first();
        if ($cur->currency_code == "TRY") {
            return response()->json([
                "status" => "error",
                "error" => "You cannot remove Turkish Currency ('{$cur->currency_code}')",
            ], 400);
        }

        $setting = SitesSetting::first();

        /** 
         * @see should not remove currency which is active in site setting 
         */
        if( $cur->id == $setting->currency->id ) {
            return response()->json([
                "status" => "error",
                "error" => "You cannot remove Active Currency '{$cur->currency_code}'",
            ], 400);
        }

        try {
            $cur->delete();

        }catch(\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    "status" => "error",
                    "error" => "You cannot remove Currency '{$cur->currency_code}' which is in use",
                ], 400);
            }

            return response()->json([
                "status" => "error",
                "error" => "Something went wrong",
            ], 400);
        }

        return response()->json(["status" => "success"]);
    }

}
