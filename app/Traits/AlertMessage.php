<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 19:31
 */

namespace App\Traits;

use App\Http\Components\Alert\Alert;

trait AlertMessage
{

    /**
     * @param bool $group
     * @return Alert[]|\Illuminate\Support\Collection
     */
    public function getAlerts($group = false)
    {
      return app('AlertProvider')->getAlerts($group);
    }

    /**
     * @param string $message
     * @param string $type
     */
    protected function alert($message, $type)
    {
      app('AlertProvider')->addAlert($message, $type);
    }

    /**
     * @param string $message
     */
    protected function alertSuccess($message)
    {
        $this->alert($message, Alert::SUCCESS);
    }

    /**
     * @param string $message
     */
    protected function alertInfo($message)
    {
        $this->alert($message, Alert::INFO);
    }

    /**
     * @param string $message
     */
    protected function alertWarning($message)
    {
        $this->alert($message, Alert::WARNING);
    }

    /**
     * @param string $message
     */
    protected function alertError($message)
    {
        $this->alert($message, Alert::ERROR);
    }

    /**
     * @param \Exception $e
     * @param string $prefixMessage
     */
    protected function alertException(\Exception $e, $prefixMessage = '')
    {
        $this->alertError("{$prefixMessage}  {$e->getMessage()}");
    }

    /**
     * @param string|null $to
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectSuccess($to,$message)
    {
        return redirect($to)->with(Alert::SUCCESS,$message);
    }

    /**
     * @param string|null $to
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectInfo($to,$message)
    {
        return redirect($to)->with(Alert::INFO,$message);
    }

    /**
     * @param string|null $to
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWarning($to,$message)
    {
        return redirect($to)->with(Alert::WARNING,$message);
    }

    /**
     * @param string|null $to
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectError($to,$message)
    {
        return redirect($to)->with(Alert::ERROR,$message);
    }

}