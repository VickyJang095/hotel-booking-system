<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ưu tiên: session → config → 'vi'
        $locale = Session::get('locale', config('app.locale', 'vi'));

        // Chỉ chấp nhận locale hợp lệ
        $supported = ['vi', 'en'];
        if (!in_array($locale, $supported)) {
            $locale = 'vi';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
