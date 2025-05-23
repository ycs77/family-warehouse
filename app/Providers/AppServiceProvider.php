<?php

namespace App\Providers;

use App\Category;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Register Components
        Blade::component('components.alert', 'alert');

        // Custom Blade Directive
        Blade::directive('active', function ($pattern) {
            return "<?php echo Str::match($pattern, request()->route()->getName()) ? 'active' : ''; ?>";
        });
        Blade::directive('category_active', function ($category) {
            return "<?php
                echo {$category}->isSelfOrChild(
                    request()->item &&
                    request()->item->category
                        ? request()->item->category
                        : request()->category
                ) ? 'active' : '';
            ?>";
        });

        // View share
        View::composer(['layouts.app', 'home'], function ($view) {
            $view->with('menuCategories', Category::whereIsRoot()->get());
        });
    }
}
