<?php

namespace App\Providers;

use App\Models\FeaturedPlan;
use App\Models\SubscriptionPlan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Stripe\Stripe;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          // Set your Stripe API key
          config(['services.stripe.key' => env('STRIPE_KEY')]);
          config(['services.stripe.secret' => env('STRIPE_SECRET')]);
          Stripe::setApiKey(config('services.stripe.secret'));

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        Paginator::useBootstrap();
    }
}
