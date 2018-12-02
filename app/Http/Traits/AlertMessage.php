<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 19:31
 */

namespace App\Http\Traits;

use App\Http\Components\Alert\Alert;
trait AlertMessage
{

    protected function getAlerts($group = false)
    {
      app('Presenter')->getAlerts($group);
    }

    protected function alert($message, $type)
    {
      app('Presenter')->addAlert($message, $type);
    }

    protected function alertSuccess($message)
    {
        $this->alert($message, Alert::SUCCESS);
    }

    protected function alertInfo($message)
    {
        $this->alert($message, Alert::INFO);
    }

    protected function alertWarning($message)
    {
        $this->alert($message, Alert::WARNING);
    }

    protected function alertError($message)
    {
        $this->alert($message, Alert::ERROR);
    }

    protected function alertException(\Exception $e, $prefixMessage = '')
    {
        $this->alertError("{$prefixMessage}  {$e->getMessage()}");
    }

}