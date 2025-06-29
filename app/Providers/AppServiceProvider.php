<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

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
        if (app()->environment('local')) { // Log only in local environment
            DB::listen(function ($query) {
                Log::info('SQL Query: ', [
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time
                ]);
            });
        }
        Paginator::useBootstrap();
        
    }
    
    
}
