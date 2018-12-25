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

    public function __construct(string $name, $type = CHECKBOX_TYPE, string $label = '', $value = 1, $checked = null)
    {
        parent::__construct($name,$type,$label);
        $this->setValue($value);
        $this->setChecked($checked);
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



}