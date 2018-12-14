<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 20:06
 */

namespace App\Http\Components;


use App\Traits\AlertMessage;

class Presenter
{
    use AlertMessage;

    public function getAlerts($group = false)
    {
        return app('AlertProvider')->getAlerts($group);
    }

    public function renderAlerts()
    {
        return app('AlertProvider')->provide();
    }

    public function getLeftMenu(){
       return app('MenuProvider')->provide();
    }

    public function getUserMenu(){
        return app('UserProvider')->provide();
    }
}