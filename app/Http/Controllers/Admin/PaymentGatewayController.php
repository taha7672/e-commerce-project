<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payment-gateways');
    }

    public function index(){
        $paymentGateways = PaymentGateway::all();
        return view('admin.payment-gateways.index', compact('paymentGateways'));
    }

    public function create(){
        $gateways_type = config('gateways');
        return view('admin.payment-gateways.create', compact('gateways_type'));

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',  // Validating type column
            'credentials' => 'required',
            'status' => 'boolean', 
        ]);

        if ($request->has('is_default')) {
            PaymentGateway::query()->update(['is_default' => false]);
        }
        $data = $request->all();

        $data['credentials'] = json_encode( $data['credentials'] );
        if ($request->has('is_default')) {
            $data['is_default'] = true; 
        }
        else{
            $data['is_default'] = false; 
        }

        PaymentGateway::create($data);

        if($data['type'] == 'paddle'){
            $this->updateConfig($data);
        }


        return redirect()->route('admin.payment-gateways.index')->with('success', __('messages.payment_gateway_created'));
    }

    public function edit(PaymentGateway $paymentGateway){
        $gateways_type = config('gateways');
        $paymentGateway->credentials = json_decode($paymentGateway->credentials, true);
        return view('admin.payment-gateways.edit', compact('gateways_type','paymentGateway')); 
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',  // Validating type column
            'credentials' => 'required',
            'status' => 'boolean', 
        ]);
        if ($request->has('is_default')) {
            PaymentGateway::query()->update(['is_default' => false]);
        }

        $data = $request->all();
        $data['credentials'] = json_encode( $data['credentials'] );
        if ($request->has('is_default')) {
            $data['is_default'] = true; 
        }
        else{
            $data['is_default'] = false; 
        }
        $paymentGateway->update($data);  


        if($data['type'] == 'paddle'){
            $this->updateConfig($data);
        }


        return redirect()->route('admin.payment-gateways.index')->with('success', __('messages.payment_gateway_updated'));
    }
    
    protected function updateConfig($data){
        $data['credentials'] = json_decode($data['credentials'], true);
        $is_sandbox = $data['mode'] == 0 ? true: false;
        $content = "<?php

                        return [

                            /*
                            |--------------------------------------------------------------------------
                            | Paddle Keys
                            |--------------------------------------------------------------------------
                            |
                            | The Paddle seller ID and API key will allow your application to call
                            | the Paddle API. The seller key is typically used when interacting
                            | with Paddle.js, while the \"API\" key accesses private endpoints.
                            |
                            */

                        'seller_id' => '{$data['credentials']['api_secret']}',

                        'client_side_token' => '{$data['credentials']['retain_key']}',

                        'api_key' => '{$data['credentials']['api_key']}',

                        'retain_key' => '{$data['credentials']['retain_key']}',

                        'webhook_secret' => '{$data['credentials']['webhook_secret']}',

                        /*
                        |--------------------------------------------------------------------------
                        | Cashier Path
                        |--------------------------------------------------------------------------
                        |
                        | This is the base URI path where Cashier's views, such as the webhook
                        | route, will be available. You're free to tweak this path based on
                        | the needs of your particular application or design preferences.
                        |
                        */

                        'path' => env('CASHIER_PATH', 'paddle'),

                        /*
                        |--------------------------------------------------------------------------
                        | Cashier Webhook
                        |--------------------------------------------------------------------------
                        |
                        | This is the base URI where webhooks from Paddle will be sent. The URL
                        | built into Cashier Paddle is used by default; however, you can add
                        | a custom URL when required for any application testing purposes.
                        |
                        */

                        'webhook' => env('CASHIER_WEBHOOK'),

                        /*
                        |--------------------------------------------------------------------------
                        | Currency
                        |--------------------------------------------------------------------------
                        |
                        | This is the default currency that will be used when generating charges
                        | from your application. Of course, you are welcome to use any of the
                        | various world currencies that are currently supported via Paddle.
                        |
                        */

                        'currency' => env('CASHIER_CURRENCY', 'USD'),

                        /*
                        |--------------------------------------------------------------------------
                        | Currency Locale
                        |--------------------------------------------------------------------------
                        |
                        | This is the default locale in which your money values are formatted in
                        | for display. To utilize other locales besides the default en locale
                        | verify you have the \"intl\" PHP extension installed on the system.
                        |
                        */

                        'currency_locale' => env('CASHIER_CURRENCY_LOCALE', 'en'),

                        /*
                        |--------------------------------------------------------------------------
                        | Paddle Sandbox
                        |--------------------------------------------------------------------------
                        |
                        | This option allows you to toggle between the Paddle live environment
                        | and its sandboxed environment.
                        |
                        */

                        'sandbox' => env('PADDLE_SANDBOX', {$is_sandbox}),

                    ];";
        file_put_contents(config_path('cashier.php'), $content);

    }


    public function setDefault(PaymentGateway $paymentGateway)
    {
        PaymentGateway::query()->update(['is_default' => false]);
    
        $paymentGateway->update(['is_default' => true]);
    
        return redirect()->route('admin.payment-gateways.index')->with('success',  __('messages.payment_gateway_default'));
    }
    
    public function destroy(PaymentGateway $paymentGateway, Request $request){
        $paymentGateway->delete();
        return redirect()->back()->with('success', __('messages.payment_gateway_deleted'));
    }

}
