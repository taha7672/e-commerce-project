<?php

                        return [

                            /*
                            |--------------------------------------------------------------------------
                            | Paddle Keys
                            |--------------------------------------------------------------------------
                            |
                            | The Paddle seller ID and API key will allow your application to call
                            | the Paddle API. The seller key is typically used when interacting
                            | with Paddle.js, while the "API" key accesses private endpoints.
                            |
                            */

                        'seller_id' => '14357',

                        'client_side_token' => 'test_213d326756702e64a116fab6f0a',

                        'api_key' => 'e2f7388cc43326a8c86d51a020ca637ce077bf9bf75a314444',

                        'retain_key' => 'test_213d326756702e64a116fab6f0a',

                        'webhook_secret' => '',

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
                        | verify you have the "intl" PHP extension installed on the system.
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

                        'sandbox' => env('PADDLE_SANDBOX', 1),

                    ];