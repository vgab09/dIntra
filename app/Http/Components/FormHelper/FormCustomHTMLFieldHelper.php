<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.03.03.
 * Time: 15:17
 */

namespace App\Http\Components\FormHelper;


class FormCustomHTMLFieldHelper implements FormFieldHelperInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $html = '';

    public function __construct($name,$html)
    {
        $this->setName($name);
        $this->setValue($html);
    }

    public static function to($name,$html){
        return new static($name,$html);
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->renderTag();
    }

    /**
     * @return string
     */
    public function renderTag()
    {
        return $this->html;
    }

    public function setErrors($errors)
    {
        return $this;
    }

    /**
     * @param string $name
     * @return FormCustomHTMLFieldHelper
     */
    public function setName($name): FormCustomHTMLFieldHelper
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->html;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->html = $value;
        return $this;
    }

    /**
     * @param $html
     * @return FormCustomHTMLFieldHelper
     */
    public function setHTML($html){
        return $this->setValue();
    }

    /**
     * @return string
     */
    public function getHTML(){
        return $this->getValue();
    }

}