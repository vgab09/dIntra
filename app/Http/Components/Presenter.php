<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 20:06
 */

namespace App\Http\Components;


use App\Http\Components\Alert\Alert;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Session\SessionManager;
class Presenter
{

    /**
     * @var ViewErrorBag
     */
    protected $viewErrorBag;

    /**
     * @var Collection
     */
    protected $alerts;

    public function init(){
        $this->alerts = collect();
    }


    public function getAlerts($group = false)
    {

        if ($group) {
            return $this->alerts->groupBy('type');
        }

        return $this->alerts;

    }

    public function addAlert($message, $type)
    {
        $this->alerts->push( new Alert($message,$type));
    }

    public function mergeSessionErrors($messages,$type){
        foreach ($messages as $message){
            $this->alerts->push(new Alert($message,$type));
        }
    }

    public function getLeftMenu(){
       return app('MenuProvider')->getLeftMenu();
    }
}