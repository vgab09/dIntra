<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.08.
 * Time: 17:49
 */

namespace App\Helpers\Lists;

use App\Traits\MagicSetterGetter;
use Exception;

class ListFieldHelper
{

    /**
     * @var string the name of the model attribute from which we get the value
     */
    public $name = '';

    /**
     * @var string field display name
     */
    public $title = '';

    /**
     * @var string field width. At least one field should be set to 'auto' in order to grow with window size. (default 'auto', optional)
     */
    public $width = 'auto';

    /**
     * @var string Content position inside the column (default 'left', optional).
     */
    public $align = 'left';

    /**
     * @var bool If true, the column is orderable (default 'true', optional)
     */
    public $orderable = true;

    /**
     * @var bool If true, the column is searchable (default 'true', optional)
     */
    public $searchable = true;

    /**
     * @var string Initial search keywords
     */
    public $searchValue = '';

    /**
     * @var int If set, the field value will be truncated if it has more characters than the numeric value set (default 0: no limit, optional).
     */
    public $maxLength = 0;

    /**
     * @var string  Column format. {'string', 'bool', 'date', 'datetime', 'decimal', 'float', 'percent', 'price'} (default string)
     */
    public $type = 'string';

    /**
     * @var string  Style classes (format: "class1 class2") add to column (default empty, optional)
     */
    public $style = '';

    public $hint = '';

    public $show = true;

    public $suffix = ''; // kg

    public $pictogram = [];
//    public $pictogram = [ 0 => 'disabled.gif', 1 => 'enabled.gif' ]; //   /public/img/pictograms/


    public function __construct($name,array $attributes = [])
    {
        $this->name = $name;
        $this->fill($attributes);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @throws \Exception
     * @return $this
     *
     */
    public function fill(array $attributes){

        foreach ($attributes as $property => $value){
            if(property_exists($this,$property)){
                $this->$property = $value;
            }
            else{
                throw new Exception('Property not exists:'.strip_tags($property));
            }
        }

        return $this;

    }
}