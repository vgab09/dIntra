<?php

namespace App\Providers;

use App\Http\Classes\Presenter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ViewErrorBag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $presenter = $this->app->make('Presenter');
        $presenter->init();
        View::share('presenter',$presenter);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Presenter', function ($app) {
            return new Presenter();
        });
    }
}
