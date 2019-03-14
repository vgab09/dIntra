<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.01.29.
 * Time: 21:09
 */

namespace App\Http\Components\FormHelper;


interface FormFieldHelperInterface
{
    /**
     * Render form group elements with label, description, suffix, prefix
     * @return string
     */
    public function render();

    /**
     * Render only the html tag
     * @return string
     */
    public function renderTag();

    public function setErrors($errors);

    public function setName($name);

    public function getName();

    public function getValue();

    public function setValue($value);

}