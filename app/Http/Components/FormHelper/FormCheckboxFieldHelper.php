<?php

namespace App\Http\Components\FormHelper;
use Collective\Html\FormFacade as Form;

class FormCheckboxFieldHelper extends FormFieldHelper
{

    /**
     * @var bool
     */
    protected $checked;

    public const RADIO_TYPE = 'radio';
    public const CHECKBOX_TYPE = 'checkbox';
    public const SWITCH_TYPE = 'switch';

    protected $wrapperClass = '';

    /**
     * FormCheckboxFieldHelper constructor.
     * @param string $name
     * @param string $type radio | checkbox
     * @param string $label
     * @param int $value
     * @param null $checked
     */
    public function __construct(string $name, $type = CHECKBOX_TYPE, string $label = '', $value = 1, $checked = null)
    {
        parent::__construct($name,$label);
        $this->setType($type);
        $this->setValue($value);
        $this->setChecked($checked);
        $this->setClass('custom-control-input');
    }

    public static function toCheckbox(string $name,string $label = '', $value = 1, $checked = null){
        return new static($name,static::CHECKBOX_TYPE,$label,$value,$checked);
    }

    public static function toSwitch(string $name,string $label = '', $value = 1, $checked = null){
        return new static($name,static::SWITCH_TYPE,$label,$value,$checked);
    }

    public static function toRadio(string $name,string $label = '', $value = 1, $checked = null){
        return new static($name,static::RADIO_TYPE,$label,$value,$checked);
    }

    /**
     * @return string
     */
    public function getWrapperClass(): string
    {
        return $this->wrapperClass;
    }

    /**
     * @param string $wrapperClass
     * @return FormCheckboxFieldHelper
     */
    public function setWrapperClass(string $wrapperClass): FormCheckboxFieldHelper
    {
        $this->wrapperClass = $wrapperClass;
        return $this;
    }



    /**
     * @return bool
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     * @return FormCheckboxFieldHelper
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
        return $this;
    }

    public function render()
    {
        switch ($this->type)
        {
            case static::RADIO_TYPE:
                $this->setWrapperClass('custom-control custom-radio');
                break;
            case static::SWITCH_TYPE:
                $this->setWrapperClass('custom-control custom-switch');
                break;
            case static::CHECKBOX_TYPE:
            default:
                $this->setWrapperClass('custom-control custom-checkbox');
                break;
        }

        return Form::checkboxField($this);

    }

    /**
     * Render only the form element
     * @return string
     */
    public function renderTag()
    {
        switch ($this->type)
        {
            case static::RADIO_TYPE:
                return Form::radio($this->getName(),$this->getValue(),$this->getChecked(),$this->collectAttributes());
                break;
            case static::CHECKBOX_TYPE:
            case static::SWITCH_TYPE:
            default:
                return Form::checkbox($this->getName(),$this->getValue(),$this->getChecked(),$this->collectAttributes());
                break;
        }
    }
}