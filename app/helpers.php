<?php

if (!function_exists('currencySymbol')) {
    function currencySymbol($currency)
    {
        return match ($currency) {
            'VND' => '₫',
            'USD' => '$',
            'EUR' => '€',
            default => '$',
        };
    }
}

if (!function_exists('currencyRate')) {
    function currencyRate($currency)
    {
        return match ($currency) {
            'VND' => 24000,
            'USD' => 1,
            'EUR' => 0.92,
            default => 1,
        };
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        $currency = session('currency', 'USD');
        $rate = currencyRate($currency);
        $symbol = currencySymbol($currency);

        $converted = $amount * $rate;

        return match ($currency) {
            'VND' => number_format($converted, 0, ',', '.') . $symbol,
            default => $symbol . number_format($converted, 0),
        };
    }
}