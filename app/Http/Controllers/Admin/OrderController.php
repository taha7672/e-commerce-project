<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Province;
use App\Models\District;
use App\Models\Village;
use App\Models\OrderShippingAddress;
use App\Models\OrderBillingAddress;
use App\Models\SitesSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

     public function __construct()
    {
        $this->middleware('permission:orders');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$orders= Order::get();
        $orders = Order::with(['items.product','user.shippingAddresses','user.billingAddresses'])->get();


        return view('admin.orders.index',compact('orders'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $order = Order::with(['items.product', 'orderShippingAddress','orderBillingAddress','coupon', 'payment'])
            ->where('orders.id', $id)->first();

        $provinces = Province::all();

        // shipping address
        $shippingProvince = $provinces->where('name', $order->orderShippingAddress?->state)->first();

        $shippingDistricts = District::where('province_id', $shippingProvince?->id)->get();
        $shippingDistrict = $shippingDistricts->where('district_name', $order->orderShippingAddress?->city)->first();

        $shippingVillages = Village::where('district_id', $shippingDistrict?->id)->get();

        // billing address
        $billingProvince = $provinces->where('name', $order->orderBillingAddress?->state)->first();

        $billingDistricts = District::where('province_id', $billingProvince?->id)->get();
        $billingDistrict = $billingDistricts->where('district_name', $order->orderBillingAddress?->city)->first();

        $billingVillages = Village::where('district_id', $billingDistrict?->id)->get();

        return view('admin.orders.view')->with([
            'order' => $order,
            'provinces' => $provinces,
            'shippingDistricts' => $shippingDistricts,
            'shippingVillages' => $shippingVillages,
            'billingDistricts' => $billingDistricts,
            'billingVillages' => $billingVillages,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    public function updateStatus(Request $request, string $id)
    {

        $validatedData = $request->validate([
            'status' => 'required'
        ]);

        $order=Order::find($id);
        $order->status =  $request->status;
        $order->update();
        return redirect()->back()->with('success', __('messages.order_status_updated'));

    }

     public function AddNote(Request $request, string $id)
    {


        $validatedData = $request->validate([
            'note' => 'required'
        ]);

        $order=Order::find($id);
        $order->note =  $request->note;
        $order->update();
        return redirect()->back()->with('success' , __('messages.order_note_added'));

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addNoteAjax(Request $request, $id) {
        $request->validate([
            'note' => 'required'
        ]);

        $order = Order::find($id);
        if ( !$order ) {
            return response()->json(["status" => "error", "error" =>  __('messages.order_not_found')], 404);
        }

        $order->note = $request->note;
        $order->update();

        return response()->json(["status" => "success"]);
    }

    public function updateStatusAjax(Request $request) {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);

        $order = Order::find($request->id);
        if ( !$order ) {
            return response()->json(["status" => "error", "error" => __('messages.order_not_found') ], 404);
        }

        $order->status = $request->status;
        $order->update();

        return response()->json(["status" => "success"]);
    }

    public function saveShippingAddress(Request $request) {
        $request->validate([
            'id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'state' => 'required',
            'city' => 'required',
            'village' => 'required',
            'address_line1' => 'required',
            // 'address_line2' => 'required',
            'postal_code' => 'required',
        ]);

        $inputs = $request->all();

        $province = Province::find($inputs['state']);
        $inputs['state'] = $province->name ?? "";

        $district = District::find($inputs['city']);
        $inputs['city'] = $district->district_name ?? "";

        $village = Village::where('id', $inputs['village'])->first();
        $inputs['address_line2'] = $village->village_name ?? "";
        unset($inputs['village']);

        $model = OrderShippingAddress::find($inputs['id']);
        if ( !$model ) {
            return response()->json(["status" => "error", "error" =>  __('messages.resourse_not_found')], 404);
        }
        unset($inputs['id']);

        $model->update($inputs);

        return response()->json(["status" => "success"]);
    }

    public function saveBillingAddress(Request $request) {
        $request->validate([
            'id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'state' => 'required',
            'city' => 'required',
            'village' => 'required',
            'address_line1' => 'required',
            // 'address_line2' => 'required',
            'postal_code' => 'required',
        ]);

        $inputs = $request->all();

        $province = Province::find($inputs['state']);
        $inputs['state'] = $province->name ?? "";

        $district = District::find($inputs['city']);
        $inputs['city'] = $district->district_name ?? "";

        $village = Village::where('id', $inputs['village'])->first();
        $inputs['address_line2'] = $village->village_name ?? "";
        unset($inputs['village']);

        $model = OrderBillingAddress::find($inputs['id']);
        if ( !$model ) {
            return response()->json(["status" => "error", __('messages.resourse_not_found')], 404);
        }
        unset($inputs['id']);

        $model->update($inputs);

        return response()->json(["status" => "success"]);
    }

    public function fetchShippingTable(Request $request, Order $order) {
        $order->load('items.product', 'orderShippingAddress', 'coupon');

        $provinces = Province::all();

        $shippingProvince = $provinces->where('name', $order->orderShippingAddress->state)->first();

        $shippingDistricts = District::where('province_id', $shippingProvince?->id)->get();
        $shippingDistrict = $shippingDistricts->where('district_name', $order->orderShippingAddress->city)->first();

        $shippingVillages = Village::where('district_id', $shippingDistrict?->id)->get();

        return view('admin.orders.shipping-table')->with([
            'order' => $order,
            'provinces' => $provinces,
            'shippingDistricts' => $shippingDistricts,
            'shippingVillages' => $shippingVillages,
        ]);
    }

    public function fetchBillingTable(Request $request, Order $order) {
        $order->load('items.product', 'orderBillingAddress', 'coupon');

        $provinces = Province::all();

        $billingProvince = $provinces->where('name', $order->orderBillingAddress->state)->first();

        $billingDistricts = District::where('province_id', $billingProvince?->id)->get();
        $billingDistrict = $billingDistricts->where('district_name', $order->orderBillingAddress->city)->first();

        $billingVillages = Village::where('district_id', $billingDistrict?->id)->get();

        return view('admin.orders.billing-table')->with([
            'order' => $order,
            'provinces' => $provinces,
            'billingDistricts' => $billingDistricts,
            'billingVillages' => $billingVillages,
        ]);
    }

    public function generateInvoice(Request $request, Order $order) {

        $order->load(['items.product', 'orderShippingAddress','orderBillingAddress','coupon', 'payment']);
        $data = [
            "company" => [
                "img" => public_path(getSetting('logo_url')),
                "name" => getSetting('brand_name'),
                "address" => getSetting('address'),
                "city" => getSetting('city'),
                "state" => getSetting('state'),
                "country" => getSetting('country'),
                "postal_code" => getSetting('postal_code'),
                "phone" => getSetting('phone_number'),
                "email" => getSetting('email'),
                "site" => getSetting('site_url'),
            ],
            "order" => $order,
        ];

        return Pdf::loadView('pdfs.invoice', $data)
            ->setPaper('a4')->setWarnings(false)
            ->download("invoice_{$order->order_num}.pdf");
    }

}
