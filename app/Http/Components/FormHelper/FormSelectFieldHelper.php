<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.24.
 * Time: 12:44
 */

namespace App\Http\Components\FormHelper;
use Collective\Html\FormFacade as Form;

class FormSelectFieldHelper extends FormFieldHelper
{

    public const SELECT_TYPE = 'select';

    /**
     * @var array Select options
     */
    protected $options = [];

    /**
     * @var mixed selected value
     */
    protected $selectedOption = [];

    /**
     * @var array
     */
    protected $selectAttributes = [];

    /**
     * @var array
     */
    protected $optionsAttributes = [];

    /**
     * @var array
     */
    protected $optionGroupsAttributes = [];

    /**
     * FormSelectFieldHelper constructor.
     * @param string $name
     * @param string $label
     * @param array $options
     * @param string|null $selectedValue
     */
    public function __construct(string $name,$label='', $options = [], $selectedValue = null)
    {
        parent::__construct($name,$label);
        $this->addSelectOptions($options);
        $this->setSelectedOption($selectedValue);
        $this->setType(static::SELECT_TYPE);
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $options
     * @param null $selected
     * @return FormFieldHelper
     */
    public static function to(string $name, $label='', $options = [], $selected = null)
    {
        return new static($name, $label, $options, $selected);
    }


    /**
     * Add one option to select
     * @param $name
     * @param $value
     * @param bool $selected default: false
     * @return FormSelectFieldHelper
     */
    public function addSelectOption($name, $value, $selected = false)
    {
        $this->options[$value] = $name;
        if ($selected) {
            $this->setSelectedOption($value);
        }

        return $this;
    }

    /**
     * Add multiple option
     * @param iterable $options
     * Format: [[name=>'sth',value=>'val',selected=>false],[name=>'sth',value=>'val',selected=>false]]
     * @return FormSelectFieldHelper
     *
     */
    public function addSelectOptions($options)
    {
        foreach ($options as $value => $name) {
            $this->addSelectOption($name, $value);
        }

        return $this;
    }

    /**
     * Set selected option
     * @param $value mixed Option value
     * @return bool true is success
     */
    public function setSelectedOption($value)
    {
        if (!empty($this->options[$value])) {
            $this->selectedOption = $value;
            return true;
        }
        return false;
    }

    /**
     * Return select options
     * [[name=>'sth',value=>'val',selected=>false],[name=>'sth',value=>'val',selected=>false]]
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $selectAttributes
     * @return FormSelectFieldHelper
     */
    public function setSelectAttributes(array $selectAttributes): FormSelectFieldHelper
    {
        $this->selectAttributes = $selectAttributes;
        return $this;
    }

    /**
     * @param array $optionsAttributes
     * @return FormSelectFieldHelper
     */
    public function setOptionsAttributes(array $optionsAttributes): FormSelectFieldHelper
    {
        $this->optionsAttributes = $optionsAttributes;
        return $this;
    }

    /**
     * @param array $optionGroupsAttributes
     * @return FormSelectFieldHelper
     */
    public function setOptionGroupsAttributes(array $optionGroupsAttributes): FormSelectFieldHelper
    {
        $this->optionGroupsAttributes = $optionGroupsAttributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getSelectAttributes(): array
    {
        return $this->selectAttributes;
    }

    /**
     * @return array
     */
    public function getOptionsAttributes(): array
    {
        return $this->optionsAttributes;
    }

    /**
     * @return array
     */
    public function getOptionGroupsAttributes(): array
    {
        return $this->optionGroupsAttributes;
    }

    public function collectAttributes()
    {
        return  array_merge(parent::collectAttributes(),$this->getSelectAttributes());
    }


    public function render()
    {
        return Form::inputField($this);
    }


    /**
     * Render only the form element
     * @return string
     */
    public function renderTag()
    {
        return Form::select(
            $this->getName(),
            $this->getOptions(),
            $this->getValue(),
            $this->collectAttributes(),
            $this->getOptionsAttributes(),
            $this->getOptionGroupsAttributes()
        );
    }
}