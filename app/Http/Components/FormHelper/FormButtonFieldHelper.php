<?php

namespace App\Http\Components\FormHelper;

use Collective\Html\FormFacade as Form;

class FormButtonFieldHelper extends FormFieldHelper
{

    public const SUBMIT_TYPE = 'submit';
    public const BUTTON_TYPE = 'button';


    /**
     * FormButtonFieldHelper constructor.
     * @param string $type
     * @param string $buttonText
     * @param string $name
     * @param string $label
     */
    public function __construct(string $type, string $buttonText, string $name, string $label = '')
    {
        parent::__construct($name, $label);
        $this->setType($type);
        $this->setValue($buttonText);
    }

    /**
     * @param string $name
     * @param $type
     * @param $buttonText
     * @param string $label
     * @return FormButtonFieldHelper
     */
    public static function to(string $type, $buttonText, string $name, $label = '')
    {
        return new static($type, $buttonText, $name, $label);
    }

    /**
     * @param string $buttonText
     * @param string $name
     * @param string $label
     * @return FormButtonFieldHelper
     */
    public static function toSubmit(string $buttonText, $name = 'submit', $label = '')
    {
        return self::to(static::SUBMIT_TYPE, $buttonText, $name, $label);
    }

    /**
     * @param string $buttonText
     * @param string $name
     * @param string $label
     * @return FormButtonFieldHelper
     */
    public static function toButton(string $buttonText, $name = 'button', $label = '')
    {
        return self::to(static::BUTTON_TYPE, $buttonText, $name, $label);
    }

    /**
     * Render the form element with label, and divs
     * @return string
     */
    public function render()
    {
        return Form::buttonField($this);
    }

    /**
     * Render only the form element
     * @return string
     */
    public function renderTag()
    {
        switch ($this->type) {
            case static::SUBMIT_TYPE:

                return Form::button($this->getValue(),$this->collectAttributes());
                break;

            case static::BUTTON_TYPE:
            default:

                return Form::button($this->getValue(),$this->collectAttributes());
                break;

        }
    }

}