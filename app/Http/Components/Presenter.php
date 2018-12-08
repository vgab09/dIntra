<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 20:06
 */

namespace App\Http\Components;


use App\Http\Components\Alert\Alert;
use App\Http\Traits\AlertMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Session\SessionManager;
class Presenter
{
    use AlertMessage;

    public function getAlerts($group = false)
    {
        return app('AlertProvider')->getAlerts($group);
    }

    public function getLeftMenu(){
       return app('MenuProvider')->getLeftMenu();
    }

    public function getUserMenu(){
        app('UserProvider')->init();
        return app('UserProvider')->getUserMenu();
    }
}