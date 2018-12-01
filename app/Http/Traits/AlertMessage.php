<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 19:31
 */

namespace App\Http\Traits;

trait AlertMessage
{

    protected function getAlerts($group = false)
    {
      App('Presenter')->getAlerts($group);
    }

    protected function alert($message, $type)
    {
        App('Presenter')->alert($message, $type);
    }

    protected function alertSuccess($message)
    {
        $this->alert($message, 'success');
    }

    protected function alertInfo($message)
    {
        $this->alert($message, 'info');
    }

    protected function alertWarning($message)
    {
        $this->alert($message, 'warning');
    }

    protected function alertError($message)
    {
        $this->alert($message, 'error');
    }

    protected function alertException(\Exception $e, $prefixMessage = '')
    {
        $this->alertError("{$prefixMessage}  {$e->getMessage()}");
    }

}