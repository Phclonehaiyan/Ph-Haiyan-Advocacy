<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
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
        try {
            if (! Schema::hasTable('site_settings')) {
                return;
            }

            $payload = SiteSetting::query()->value('payload');

            if (is_array($payload) && $payload !== []) {
                config()->set('site', array_replace_recursive(config('site', []), $payload));
            }
        } catch (\Throwable) {
            // Skip config overrides until the settings table is available.
        }
    }
}
