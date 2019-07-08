<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
    }
}
