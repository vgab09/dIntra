<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.02.
 * Time: 14:35
 */

namespace App\Http\Components\Providers;


use App\Http\Components\Alert\Alert;

class AlertProvider
{

    /**
     * @var Collection
     */
    protected $alerts;

    public function __construct()
    {
        $this->alerts = collect();
    }


    public function addAlert($message, $type)
    {
        $this->alerts->push( new Alert($message,$type));
    }

    public function mergeSessionErrors($messages,$type){
        foreach ($messages as $message){
            $this->addAlert($message,$type);
        }
    }

    public function getAlerts($group = false)
    {

        if ($group) {
            return $this->alerts->groupBy('type');
        }

        return $this->alerts;

    }

}