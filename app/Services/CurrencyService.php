<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    const CACHE_TTL     = 360;    // 6 tiếng (phút)
    const FALLBACK_RATE = 25000;  // fallback nếu API lỗi

    public function getUsdToVndRate(): float
    {
        return Cache::remember('exchange_rate_usd_vnd', self::CACHE_TTL * 60, function () {
            try {
                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::timeout(5)->get('https://api.exchangerate-api.com/v4/latest/USD');
                if ($response->successful()) {
                    $rate = $response->json('rates.VND');
                    if ($rate && $rate > 0) {
                        Log::info('Exchange rate fetched', ['USD_VND' => $rate]);
                        return (float) $rate;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Exchange rate API failed', ['error' => $e->getMessage()]);
            }
            return (float) self::FALLBACK_RATE;
        });
    }

    public function convert(float $usdAmount): float
    {
        return $usdAmount * $this->getUsdToVndRate();
    }

    // Dùng trong Blade @price() — tự động theo locale
    public function formatPrice(float $usdAmount): string
    {
        if (app()->getLocale() === 'vi') {
            return $this->format($usdAmount);
        }
        return '$' . number_format($usdAmount, 0);
    }

    // Hiển thị đầy đủ VNĐ: 4.500.000 ₫
    public function format(float $usdAmount): string
    {
        return number_format($this->convert($usdAmount), 0, ',', '.') . ' ₫';
    }

    // Hiển thị rút gọn: 4,5 tr ₫
    public function formatShort(float $usdAmount): string
    {
        $vnd = $this->convert($usdAmount);
        if ($vnd >= 1_000_000) {
            return number_format($vnd / 1_000_000, 1) . ' tr ₫';
        }
        return number_format($vnd, 0, ',', '.') . ' ₫';
    }

    public function clearCache(): void
    {
        Cache::forget('exchange_rate_usd_vnd');
    }
}
