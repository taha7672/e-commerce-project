<?php

use App\Models\Currency;
use App\Models\Order;
use App\Models\Setting;
use App\Models\SitesSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Notification;

if (!function_exists('generateModelSlug')) {
    /**
     * @param Model $model
     * @param String $title
     * @param Integer $id
     * @return $slug
     */
    function generateModelSlug(Model $model, $title, $id = null)
    {
        $count = 1;
        do {
            if ($count > 1) {
                $slug = Str::slug($title) . '-' . $count;
            } else {
                $slug = Str::slug($title);
            }
            $slug_exist = $model::whereSlug($slug)->where('id', '!=', $id)->first();
            $count++;
        } while ($slug_exist);
        return $slug;
    }
}

if (!function_exists('getSetting')) {
    function getSetting($key)
    {
        return SitesSetting::where('key', $key)->first()->value ?? '';
    }
}

if (!function_exists('uniqueNumber')) {
    function uniqueNumber(string|null $prefix = null, string|null $suffix = null, int $length = 3): string
    {
        $res = bin2hex(random_bytes($length));

        if ($prefix) {
            $res = $prefix . "-" . $res;
        }

        if ($suffix) {
            $res = $res . "-" . $suffix;
        }

        return strtoupper($res);
    }
}

if (!function_exists('uniqueOrderNumber')) {
    function uniqueOrderNumber(Order $order): string
    {
        return date('Ymd', strtotime($order->created_at)) . "-" . $order->id;
        // return uniqueNumber("PO", (string) $order->id);
    }
}

if (!function_exists('fetchNotification')) {
    function fetchNotification()
    {
        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->where('status', 0)
            ->get();


        $statusZeroCount = Notification::where('status', 0)
            ->count();
        return ['notifications' => $notifications, 'count' => $statusZeroCount];
    }
}

if (!function_exists('timeAgo')) {
    /**
     * Get the "time ago" format for a given datetime.
     *
     * @param string $datetime
     * @return string
     */
    function timeAgo($datetime)
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $datetime);
        return $carbonDate->diffForHumans();
    }
}

if (!function_exists('defaultCurrency')) {
    function defaultCurrency()
    {
        if(getSetting('currency')){
            $currency = Currency::find(getSetting('currency'));
        }else{
            $currency = Currency::first();
        }

        return $currency;
    }
}

if (!function_exists('priceSymbol')) {
    function priceSymbol(): string
    {
        static $symbol;

        if ($symbol) {
            return $symbol;
        }

        return $symbol = defaultCurrency()->currency_code;
    }
}

if (!function_exists('toPrice')) {
    function toPrice(float|null $amount, $baseId, int $precision = 2, int $mode = PHP_ROUND_HALF_UP)
    {
        static $rate;

        if (!$rate) {
            $rate = defaultCurrency()->exchange_rate_to_usd;
        }

        $model = $baseId
            ? Currency::find($baseId)
            : Currency::where('currency_code', 'TRY')->first();

        $base = $model->exchange_rate_to_usd;

        if ($base == 0) {
            Log::error("Currency `{$model->currency_code}` cannot be zero");
            return 0;
        }

        $result = ((float) $amount * $rate) / $base;
        return round($result, $precision, $mode);
    }
}

if (!function_exists('toCurrency')) {
    function toCurrency(float|null $amount, $baseId, int $precision = 2, int $mode = PHP_ROUND_HALF_UP)
    {
        $sym = priceSymbol();
        $amt = toPrice($amount, $baseId, $precision, $mode);
        return "{$sym} {$amt}";
    }
}
