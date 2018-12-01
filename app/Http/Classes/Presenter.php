<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 20:06
 */

namespace App\Http\Classes;


use App\Http\Classes\Alert\Alert;

class Presenter
{
    protected $alerts = [];

    public function getAlerts($group = false)
    {
        if (empty($this->alerts)) {
            return [];
        }

        $alerts = $this->alerts['alerts'];

        if ($group) {
            $alerts = collect($alerts)->groupBy('type')->toArray();
        }

        return $alerts;

    }

    public function alert($message, $type)
    {
        $this->alerts[] = new Alert($message,$type);
    }

}