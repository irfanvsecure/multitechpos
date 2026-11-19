<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        // MCP endpoint rate limit (per IP)
        \Illuminate\Support\Facades\RateLimiter::for('mcp', fn ($request) => \Illuminate\Cache\RateLimiting\Limit::perMinute(max(10, (int) config('cms.mcp.rate_limit')))->by($request->ip()));

        // Contact details, stats ... from the settings table into config('site.*')
        \App\Cms\Settings::applyToConfig();

        // $base (sub-folder prefix) and $current (page path) for every view,
        // so Route::view() pages and error pages get them automatically.
        View::composer('*', function ($view) {
            $data = $view->getData();
            $request = request();

            if (! array_key_exists('base', $data)) {
                $view->with('base', rtrim($request->getBasePath(), '/'));
            }

            if (! array_key_exists('current', $data)) {
                $path = '/'.trim($request->path(), '/');
                $view->with('current', $path === '/' ? '/' : $path.'/');
            }
        });
    }
}
