<?php

namespace App\Http\Components\FormHelper;


class FormCheckboxFieldHelper extends FormFieldHelper
{

    /**
     * @var bool
     */
    protected $checked;

    public const RADIO_TYPE = 'radio';
    public const CHECKBOX_TYPE = 'checkbox';

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
        parent::__construct($name,$type,$label);
        $this->setValue($value);
        $this->setChecked($checked);
    }

    public static function toCheckbox(string $name,string $label = '', $value = 1, $checked = null){
        return new static($name,static::CHECKBOX_TYPE,$label,$value,$checked);
    }

    public static function toRadio(string $name,string $label = '', $value = 1, $checked = null){
        return new static($name,static::RADIO_TYPE,$label,$value,$checked);
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
            case static::CHECKBOX_TYPE:
                break;
            case static::RADIO_TYPE:
                break;
            default:
                break;
        }

    }

}