<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('displayDate', function ($expression) {
            return "<?php try { echo filled($expression) ? \\Carbon\\Carbon::parse($expression)->format('d/m/Y') : '-'; } catch (\\Throwable \$e) { echo e($expression ?: '-'); } ?>";
        });

        Blade::directive('displaySerial', function ($expression) {
            return "<?php \$serialValue = $expression; echo filled(\$serialValue) ? e(is_numeric(\$serialValue) ? (((float) \$serialValue == floor((float) \$serialValue)) ? number_format((float) \$serialValue, 0, '.', '') : rtrim(rtrim((string) \$serialValue, '0'), '.')) : \$serialValue) : '-'; ?>";
        });

        Blade::directive('displayIdentifier', function ($expression) {
            return "<?php \$identifierValue = \\App\\Support\\ExcelValueFormatter::identifier($expression); echo filled(\$identifierValue) ? e(\$identifierValue) : '-'; ?>";
        });
    }
}
