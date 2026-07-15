<?php

namespace App\Providers;

use App\Models\Models;
use Illuminate\Support\Facades\Auth;
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
        Schema::defaultStringLength(191);
    
        view()->composer('*', function ($view) {
    
            if (!Schema::hasTable('models')) {
                return;
            }
    
            $query = Models::query();
    
            if (auth()->check()) {
    
                $user = auth()->user();
    
                if ($user->hasRole('Admin')) {
    
                    $query->whereIn('sub_components', [
                        
                        'dashboard',
                        'audittrail'
                    ]);
    
                } elseif ($user->hasRole('User')) {
    
                    $query->whereIn('sub_components', [
                        'dashboard'
                    ]);
    
                }
    
            }
    
            $models = $query->get();
            $components = $models->groupBy('components');
    
            $view->with('components', $components);
        });
    }
}