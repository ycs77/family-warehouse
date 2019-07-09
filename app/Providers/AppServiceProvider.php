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
        Blade::component('components.breadcrumb', 'breadcrumb');

        // Custom Blade Directive
        Blade::directive('active', function ($routeName) {
            return "<?php echo \Illuminate\Support\Str::is($routeName, request()->route()->getName()) ? 'active' : ''; ?>";
        });
        Blade::directive('category_active', function ($category) {
            return "<?php echo {$category}->isSelfOrChild(request()->category) ? 'active' : ''; ?>";
        });

        // View share
        View::composer('layouts.app', function ($view) {
            $view->with('menuCategories', Category::whereIsRoot()->get());
        });
    }
}
