<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\CurrencyService;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CurrencyService::class, function () {
            return new CurrencyService();
        });
    }

    public function boot(): void
    {
        // @price(120) → hiển thị theo locale hiện tại
        Blade::directive('price', function ($expression) {
            return "<?php echo app(\App\Services\CurrencyService::class)->formatPrice($expression); ?>";
        });
    }
}
