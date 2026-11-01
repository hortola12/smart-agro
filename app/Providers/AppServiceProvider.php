<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
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
        View::composer(['layouts.app', 'home', 'search'], function ($view) {
            if (Schema::hasTable('categories')) {
                $view->with('navCategories', Category::withCount('seeds')->get());
            }
        });

        View::composer('layouts.admin', function ($view) {
            if (Schema::hasTable('orders')) {
                $view->with('adminPendingOrdersCount', Order::where('status', 'pending')->count());
            }
        });
    }
}
