<?php

namespace App\Providers;

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
            $needsMigration = false;
            if (\Illuminate\Support\Facades\Schema::hasTable('bulan_mutu_configs') &&
                !\Illuminate\Support\Facades\Schema::hasColumn('bulan_mutu_configs', 'is_active')) {
                $needsMigration = true;
            }
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $needsMigration = true;
            }

            if ($needsMigration) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Auto migration failed: ' . $e->getMessage());
        }
    }
}
