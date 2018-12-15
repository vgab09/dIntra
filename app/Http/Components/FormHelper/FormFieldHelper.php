<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.08.
 * Time: 17:49
 */

namespace App\Http\Components\FormHelper;


class FormFieldHelper
{
    /**
     * @var string Theoretically optional, but in reality each field has to have a label
     */
    protected $label;

    /**
     * @var string the name of the model attribute from which we get the value
     */
    protected $name;

    /**
     * @var string input type: 'text', 'password', 'select', 'textarea', 'radio', 'checkbox', 'file', 'color', number, date
     */
    protected $type;

    /**
     * @var bool If true, it will be required item. The label will append by a star
     */
    protected $required = false;

    /**
     * @var string The description displayed under the field.
     */
    protected $description;

    /**
     * @var string This is displayed when the mouse hovers the field.
     */
    protected $hint;

    /**
     * @var string This is displayed after the field (ie. to indicate the unit of measure)
     */
    protected $suffix;

    /**
     * @var string input placeholder text. To be displayed when the field is empty
     */
    protected $placeholder;

    /**
     * @var int Textural input field max lenght
     */
    protected $maxLength;

    /**
     * @var bool disable edit
     */
    protected $disabled = false;

    /**
     * @var array field error messages
     */
    protected $errors = [];

    /**
     * @var array Select options
     */
    protected $options = [];

    /**
     * @var mixed selected value
     */
    protected $selectedOption;

    /**
     * FormFieldHelper constructor.
     * @param $name string Field name
     * @param $type string Field Type
     * @param string $label Field label
     * @param array $options Field attributes
     * @throws \Exception
     */
    public function __construct($name, $type, $label = '', $options = [])
    {
        $this->setName($name);
        $this->setType($type);
        $this->setLabel($label);
        $this->fill($options);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return ListFieldHelper
     */
    public function setLabel(string $label): ListFieldHelper
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ListFieldHelper
     */
    public function setName(string $name): ListFieldHelper
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ListFieldHelper
     */
    public function setType(string $type): ListFieldHelper
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     * @return ListFieldHelper
     */
    public function setRequired(bool $required): ListFieldHelper
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ListFieldHelper
     */
    public function setDescription(string $description): ListFieldHelper
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * @param string $hint
     * @return ListFieldHelper
     */
    public function setHint(string $hint): ListFieldHelper
    {
        $this->hint = $hint;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     * @return ListFieldHelper
     */
    public function setSuffix(string $suffix): ListFieldHelper
    {
        $this->suffix = $suffix;
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
     * @param string $placeholder
     * @return ListFieldHelper
     */
    public function setPlaceholder(string $placeholder): ListFieldHelper
    {
        $this->placeholder = $placeholder;
        return $this;
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
     * @return ListFieldHelper
     */
    public function setMaxLength(int $maxLength): ListFieldHelper
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return ListFieldHelper
     */
    public function setDisabled(bool $disabled): ListFieldHelper
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Add one option to select
     * @param $name
     * @param $value
     * @param bool $selected default: false
     */
    public function AddSelectOption($name, $value, $selected = false)
    {
        $this->options[$value] = $name;
        if ($selected) {
            $this->setSelectedOption($value);
        }
    }

    /**
     * Add multiple option
     * @param iterable $options
     * Format: [[name=>'sth',value=>'val',selected=>false],[name=>'sth',value=>'val',selected=>false]]
     *
     */
    public function AddSelectOptions($options)
    {
        foreach ($options as $option) {
            $this->AddSelectOption($option['name'], $option['value']);
        }
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
     * Fill the model with an array of attributes.
     *
     * @param  array $attributes
     * @throws \Exception
     * @return $this
     *
     */
    public function fill(array $attributes)
    {

        foreach ($attributes as $property => $value) {

            $methodName = camel_case('set_' . $this, $property);

            if (method_exists($methodName)) {
                $this->$methodName($value);
            } else {
                throw new Exception('Property not exists:' . strip_tags($property));
            }
        }

        return $this;

    }
}