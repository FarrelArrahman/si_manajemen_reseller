<?php

namespace App\Providers;

use App\View\Composers\OrderVerificationRequestComposer;
use App\View\Composers\ResellerVerificationRequestComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Reseller Verification Request Composer
        View::composer(['layouts.sidebar', 'users.index'], ResellerVerificationRequestComposer::class);
        // Order Verification Request Composer
        View::composer(['layouts.sidebar', 'users.index'], OrderVerificationRequestComposer::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
