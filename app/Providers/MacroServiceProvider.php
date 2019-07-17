<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Determine if a given string matches a given pattern.
         *
         * @param  string|array  $pattern
         * @param  string  $value
         * @param  string  $exclude
         * @return bool
         */
        Str::macro('match', function ($pattern, $value, $exclude = '^[]()|?!') {
            $patterns = Arr::wrap($pattern);

            if (empty($patterns)) {
                return false;
            }

            foreach ($patterns as $pattern) {
                if ($pattern == $value) {
                    return true;
                }

                $pattern = preg_quote($pattern, '#');
                $pattern = str_replace('\*', '.*', $pattern);
                foreach (str_split($exclude) as $char) {
                    $pattern = str_replace('\\'.$char, $char, $pattern);
                }

                if (preg_match('#^'.$pattern.'\z#u', $value) === 1) {
                    return true;
                }
            }

            return false;
        });
    }
}
