<?php

namespace App\Providers;

use App\Http\Components\Presenter;
use App\Http\Components\Providers\AlertProvider;
use App\Http\Components\Providers\MenuProvider;
use App\Http\Components\Providers\UserProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


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

        $this->app->singleton('MenuProvider', function ($app) {
            return new MenuProvider();
        });

        $this->app->singleton('AlertProvider', function ($app) {
            return new AlertProvider();
        });

        $this->app->singleton('UserProvider', function ($app) {
            return new UserProvider();
        });

        $this->app->singleton('Presenter', function ($app) {
            return new Presenter();
        });
    }
}
