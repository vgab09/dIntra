<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.01.24.
 * Time: 19:34
 */

namespace App\Http\Components\FormHelper;
use Collective\Html\FormFacade as Form;

class FormTextareaFieldHelper extends FormInputFieldHelper
{

    public const TEXTAREA_TYPE = 'textarea';

    /**
     * FormFieldHelper constructor.
     * @param $name string Field name
     * @param string $label Field label
     */
    public function __construct(string $name, string $label = '')
    {
        parent::__construct($name, static::TEXTAREA_TYPE, $label);
    }

    /**
     * Create new FormFieldHelper instance
     * @param string $name
     * @param string $label
     * @return FormTextareaFieldHelper
     */
    public static function to(string $name,$type='textarea',string $label = ''): FormTextareaFieldHelper
    {
        return new static($name,$label);
    }

    /**
     * Render only the html tag
     * @return string
     */
    public function renderTag()
    {
        return Form::textarea($this->getName(),$this->getValue(),$this->collectAttributes());
    }

}