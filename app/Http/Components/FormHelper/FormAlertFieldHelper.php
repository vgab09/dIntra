<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.02.25.
 * Time: 21:47
 */

namespace App\Http\Components\FormHelper;

Use App\Http\Components\Alert\Alert;

class FormAlertFieldHelper implements FormFieldHelperInterface
{

    protected $alertInstance;

    public function __construct($message, $type)
    {
        $this->alertInstance = new Alert($message, $type);
    }

    public static function to($message, $type)
    {
        return new static($message, $type);
    }

    public static function toInfo($message)
    {
        return static::to($message, Alert::INFO);
    }

    public static function toSuccess($message)
    {
        return static::to($message, Alert::SUCCESS);
    }

    public static function toWarning($message)
    {
        return static::to($message, Alert::WARNING);
    }

    public static function toError($message)
    {
        return static::to($message, Alert::ERROR);
    }

    public function render()
    {
        return $this->renderTag();
    }

    public function renderTag()
    {
        return $this->alertInstance->render();
    }

    public function setErrors($errors)
    {
        return $this;
    }

    public function getName()
    {
        return $this->getType();
    }

    public function getType(){
        return $this->alertInstance->getType();
    }

    public function setName($name)
    {
        return $this->setType($name);
    }

    public function setType($type){
        $this->alertInstance->setType($type);
        return $this;
    }

    public function getMessage(){
        return $this->alertInstance->getMessage();
    }

    public function getValue()
    {
        return $this->getMessage();
    }

    public function setMessage($message)
    {
        $this->alertInstance->setMessage($message);
        return $this;
    }

    public function setValue($value)
    {
        $this->setMessage($value);
        return $this;
    }
}