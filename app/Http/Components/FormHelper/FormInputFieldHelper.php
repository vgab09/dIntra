<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.30.
 * Time: 11:19
 */

namespace App\Http\Components\FormHelper;

use Collective\Html\FormFacade as Form;

class FormInputFieldHelper extends FormFieldHelper
{
    /**
     * @var int Textural input field max lenght
     */
    protected $maxLength;

    /**
     * @var string input placeholder text. To be displayed when the field is empty
     */
    protected $placeholder;

    public const TEXT_TYPE = 'text';
    public const PASSWORD_TYPE = 'password';
    public const RANGE_TYPE = 'range';
    public const HIDDEN_TYPE = 'hidden';
    public const SEARCH_TYPE = 'search';
    public const TEL_TYPE = 'tel';
    public const EMAIL_TYPE = 'email';
    public const NUMBER_TYPE = 'number';
    public const DATE_TYPE = 'date';
    public const DATETIME_TYPE = 'datetime';
    public const DATETIMELOCAL_TYPE = 'datetimelocal';
    public const TIME_TYPE = 'time';
    public const URL_TYPE = 'url';
    public const WEEK_TYPE = 'week';
    public const FILE_TYPE = 'file';
    public const TEXTAREA_TYPE = 'textarea';
    public const IMAGE_TYPE = 'image';
    public const MONTH_TYPE = 'month';
    public const COLOR_TYPE = 'color';
    public const SUBMIT_TYPE = 'submit';

    /**
     * FormFieldHelper constructor.
     * @param $name string Field name
     * @param $type string Field Type
     * @param string $label Field label
     */
    public function __construct($name, $type, $label = '')
    {
        $this->setName($name);
        $this->setType($type);
        $this->setLabel($label);
    }

    /**
     * Create new FormFieldHelper instance
     * @param string $name
     * @param string $type
     * @param string $label
     * @return FormFieldHelper
     */
    public static function to(string $name, string $type, string $label){
        return new static($name,$type,$label);
    }

    public static function toText(string $name,string $label){
        return new static($name,static::TEXT_TYPE,$label);
    }

    public static function toPassword(string $name,string $label){
        return new static($name,static::PASSWORD_TYPE,$label);
    }

    public static function toRange(string $name,string $label){
        return new static($name,static::RANGE_TYPE,$label);
    }

    public static function toHidden(string $name,string $label){
        return new static($name,static::HIDDEN_TYPE,$label);
    }

    public static function toSearch(string $name,string $label){
        return new static($name,static::SEARCH_TYPE,$label);
    }

    public static function toTel(string $name,string $label){
        return new static($name,static::TEL_TYPE,$label);
    }

    public static function toEmail(string $name,string $label){
        return new static($name,static::EMAIL_TYPE,$label);
    }

    public static function toNumber(string $name,string $label){
        return new static($name,static::NUMBER_TYPE,$label);
    }

    public static function toDate(string $name,string $label){
        return new static($name,static::DATE_TYPE,$label);
    }

    public static function toDateTime(string $name,string $label){
        return new static($name,static::DATETIME_TYPE,$label);
    }

    public static function toDateTimeLocal(string $name,string $label){
        return new static($name,static::DATETIMELOCAL_TYPE,$label);
    }

    public static function toTime(string $name,string $label){
        return new static($name,static::TIME_TYPE,$label);
    }

    public static function toURL(string $name,string $label){
        return new static($name,static::URL_TYPE,$label);
    }

    public static function toWeek(string $name,string $label){
        return new static($name,static::WEEK_TYPE,$label);
    }

    public static function toFile(string $name,string $label){
        return new static($name,static::FILE_TYPE,$label);
    }

    public static function toTextarea(string $name,string $label){
        return new static($name,static::TEXTAREA_TYPE,$label);
    }

    public static function toImage(string $name,string $label){
        return new static($name,static::IMAGE_TYPE,$label);
    }

    public static function toMonth(string $name,string $label){
        return new static($name,static::MONTH_TYPE,$label);
    }

    public static function toColor(string $name,string $label){
        return new static($name,static::COLOR_TYPE,$label);
    }

    /**
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     * @return FormFieldHelper
     */
    public function setMaxLength(int $maxLength): FormFieldHelper
    {
        $this->maxLength = $maxLength;
        return $this;
    }


    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @return bool
     */
    public function hasPlaceholder(): bool
    {
        return !empty($this->placeholder);
    }

    /**
     * @param string $placeholder
     * @return FormFieldHelper
     */
    public function setPlaceholder(string $placeholder): FormFieldHelper
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function render(){
        return Form::inputField($this);
    }



}