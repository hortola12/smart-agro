<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Seed;
use App\Models\SeedTraceability;
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
            $pendingCount = 0;
            $pendingOrders = collect();
            $lowStockCount = 0;
            $lowStockSeeds = collect();
            $expiringCount = 0;
            $expiringBatches = collect();

            if (Schema::hasTable('orders')) {
                $pendingOrdersQuery = Order::where('status', 'pending');
                $pendingCount = $pendingOrdersQuery->count();
                $pendingOrders = $pendingOrdersQuery->latest()->take(5)->get();
            }

            if (Schema::hasTable('seeds')) {
                $lowStockQuery = Seed::where('stock', '<=', 5);
                $lowStockCount = $lowStockQuery->count();
                $lowStockSeeds = $lowStockQuery->orderBy('stock', 'asc')->take(5)->get();
            }

            if (Schema::hasTable('seed_traceabilities')) {
                $expiringQuery = SeedTraceability::with('seed')
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now()->addDays(60));
                $expiringCount = $expiringQuery->count();
                $expiringBatches = $expiringQuery->orderBy('expiry_date', 'asc')->take(5)->get();
            }

            $view->with([
                'adminPendingOrdersCount' => $pendingCount,
                'adminPendingOrders' => $pendingOrders,
                'adminLowStockCount' => $lowStockCount,
                'adminLowStockSeeds' => $lowStockSeeds,
                'adminExpiringBatchesCount' => $expiringCount,
                'adminExpiringBatches' => $expiringBatches,
                'adminTotalAlertsCount' => ($pendingCount + $lowStockCount + $expiringCount),
            ]);
        });
    }
}
