<?php

namespace App\Providers;

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
        // Force Laravel to stop adding /public to the URLs it generates
        if (\Str::contains(request()->server('SCRIPT_NAME'), '/public/index.php')) {
            $cleanUrl = str_replace('/public', '', request()->root());
            \Illuminate\Support\Facades\URL::forceRootUrl($cleanUrl);
        }
    }
}
