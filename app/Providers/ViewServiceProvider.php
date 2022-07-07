<?php

namespace App\Providers;

use App\View\Composers\VerificationRequestComposer;
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
        // Verification Request Composer
        View::composer(['layouts.sidebar', 'users.index'], VerificationRequestComposer::class);
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
