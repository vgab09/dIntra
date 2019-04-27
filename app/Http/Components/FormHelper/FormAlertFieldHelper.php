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

    protected $name;

    public function __construct($message, $type, $name='alert')
    {
        $this->alertInstance = new Alert($message, $type);
        $this->setName($name);
    }

    public static function to($message, $type,$name='alert')
    {
        return new static($message, $type,$name);
    }

    public static function toInfo($message,$name='alert')
    {
        return static::to($message, Alert::INFO,$name);
    }

    public static function toSuccess($message,$name='alert')
    {
        return static::to($message, Alert::SUCCESS,$name);
    }

    public static function toWarning($message,$name='alert')
    {
        return static::to($message, Alert::WARNING,$name);
    }

    public static function toError($message,$name='alert')
    {
        return static::to($message, Alert::ERROR,$name);
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
        return $this->name;
    }

    public function getFieldName()
    {
        return $this->getName();
    }

    public function getType(){
        return $this->alertInstance->getType();
    }

    public function setName($name)
    {
        return $this->name = $name;
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