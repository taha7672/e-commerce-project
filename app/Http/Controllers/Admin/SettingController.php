<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitesSetting;
use App\Models\Language;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings,admin');
    }

    public function index()
    {
        $settings = SitesSetting::with(['language', 'currency'])->get();
        $languages = Language::all();
        $currencies = Currency::all();
        return view('admin.settings.index', compact('settings', 'languages', 'currencies'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $requestData = $request->setting;
            foreach ($requestData as $key => $value) {
                $objSite = SitesSetting::where('key', $key)->first();
                if (empty($objSite)) {
                    $objSite = new SitesSetting();
                }
                $objSite->key = $key;
                $objSite->value = $value;
                $objSite->save();
            }

            DB::commit();
            // Return response based on request type
            if ($request->ajax()) {
                return response()->json(['success' => true, 'msg' =>__('messages.setting_updated')]);
            }

            return redirect()->back()->with('success', __('messages.setting_updated'));
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateLogo(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->hasFile('logo_url')) {
                $file = $request->file('logo_url');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/logos'), $filename);
                $path = 'uploads/logos/' . $filename;
            } else {
                $path = $request->logo_name;
            }

            $objSite = SitesSetting::where('key', 'logo_url')->first();
            if (empty($objSite)) {
                $objSite = new SitesSetting();
            }
            $objSite->key = 'logo_url';
            $objSite->value = $path;
            $objSite->save();


            DB::commit();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'msg' => 'Setting updated successfully']);
            }

            return redirect()->back()->with('success', 'Setting updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
