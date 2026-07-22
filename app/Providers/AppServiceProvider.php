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
                        'user',
                        'audittrail',
                        'audittemplate',
                        'auditgroup'
                    ]);
    
                } else if ($user->hasRole('Auditor')) {
    
                    $query->whereIn('sub_components', [
                        'audit'
                    ]);
    
                }
    
            }
    
            $models = $query->get();
            $components = $models->groupBy('components');
    
            $view->with('components', $components);
        });
    }
}