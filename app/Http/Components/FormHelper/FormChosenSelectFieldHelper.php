<?php
/**
 * Created by PhpStorm.
 * User: gabor.virag
 * Date: 2019/04/11
 * Time: 16:51
 */

namespace App\Http\Components\FormHelper;


class FormChosenSelectFieldHelper extends FormSelectFieldHelper
{
    public const SELECT_TYPE = 'chosen';

    /**
     * FormChosenSelectFieldHelper constructor.
     * @param string $name
     * @param string $label
     * @param array|iterable $options
     * @param null|string $selectedValue
     */
    public function __construct(string $name, string $label = '', $options = [], ?string $selectedValue = null)
    {
        parent::__construct($name, $label, $options, $selectedValue);
        $this->addClass('chosen-select');
        $this->setSelectAttributes(array_merge($this->getSelectAttributes(),['multiple'=>'multiple']));
    }


}