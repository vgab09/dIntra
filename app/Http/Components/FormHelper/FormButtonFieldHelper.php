<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.01.15.
 * Time: 19:05
 */

namespace App\Http\Components\FormHelper;
use Collective\Html\FormFacade as Form;

class FormButtonFieldHelper extends FormFieldHelper
{
    /**
     * Render the form element with label, and divs
     * @return string
     */
    public function render()
    {
        return $this->renderTag();
    }

    /**
     * Render only the form element
     * @return string
     */
    public function renderTag()
    {
        return Form::button();
    }

}