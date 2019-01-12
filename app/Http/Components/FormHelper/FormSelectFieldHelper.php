<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.24.
 * Time: 12:44
 */

namespace App\Http\Components\FormHelper;


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
    protected $selectedOption;

    /**
     * @var array
     */
    protected $selectAttributes;

    /**
     * @var array
     */
    protected $optionsAttributes;

    /**
     * @var array
     */
    protected $optionGroupsAttributes;

    /**
     * FormSelectFieldHelper constructor.
     * @param string $name
     * @param array $options
     * @param string|null $selectedValue
     * @param array $selectAttributes
     * @param array $optionsAttributes
     * @param array $optionGroupsAttributes
     */
    public function __construct(string $name, $options = [], $selectedValue = null, array $selectAttributes = [], array $optionsAttributes = [], array $optionGroupsAttributes = [])
    {
        $this->setName($name);
        $this->addSelectOptions($options);
        $this->setSelectedOption($selectedValue);
        $this->setSelectAttributes($selectAttributes);
        $this->setOptionsAttributes($optionsAttributes);
        $this->setOptionGroupsAttributes($optionGroupsAttributes);
    }

    /**
     * @param string $name
     * @param array $options
     * @param null $selected
     * @param array $selectAttributes
     * @param array $optionsAttributes
     * @param array $optingGroupsAttributes
     * @return FormFieldHelper
     */
    public static function to(string $name, $options = [], $selected = null, array $selectAttributes = [], array $optionsAttributes = [], array $optingGroupsAttributes = [])
    {
        return new static($name, $options, $selected, $selectAttributes, $optionsAttributes, $optingGroupsAttributes);
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
        foreach ($options as $option) {
            $this->addSelectOption($option['name'], $option['value']);
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


    public function render()
    {
        return Form::selectField($this);
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
            $this->getSelectAttributes(),
            $this->getOptionsAttributes(),
            $this->getOptionGroupsAttributes()
        );
    }
}