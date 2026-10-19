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
