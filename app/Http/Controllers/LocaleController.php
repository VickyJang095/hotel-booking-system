<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $supported = ['vi', 'en'];
        if (in_array($locale, $supported)) {
            Session::put('locale', $locale);
        }
        return back();
    }

    public function switchCurrency(Request $request, string $currency)
    {
        $supported = ['USD', 'VND', 'EUR', 'GBP', 'JPY', 'KRW', 'THB', 'SGD', 'AUD', 'CNY'];
        if (in_array($currency, $supported)) {
            Session::put('currency', $currency);
        }
        return response()->json(['success' => true, 'currency' => $currency]);
    }
}
