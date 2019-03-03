<?php

namespace App\Providers;

use App\Http\Components\Presenter;
use App\Http\Components\Providers\AlertProvider;
use App\Http\Components\Providers\MenuProvider;
use App\Http\Components\Providers\UserProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Collective\Html\FormFacade as Form;

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
        $this->registerFormComponents();
        View::share('presenter',$this->app->make('Presenter'));

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

    private function registerFormComponents(){
        Form::component('inputField', 'form.partials.input', ['field']);
        Form::component('checkboxField', 'form.partials.checkbox', ['field']);
        Form::component('radioField', 'form.partials.radio', ['field']);
        Form::component('buttonField', 'form.partials.button', ['field']);
    }
}
