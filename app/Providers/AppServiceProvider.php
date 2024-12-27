<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Делится заголовком страницы с представлениями
        View::composer('*', function ($view) {
            $title = $view->getFactory()->yieldContent('title');
            View::share('capturedTitle', $title);
        });
    }
}
