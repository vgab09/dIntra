<?php

namespace App\Http\Components\ListHelper;

use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use Yajra\DataTables\Html\Column;

class ListFieldHelper
{


    public const STRING_TYPE = 'string';
    public const BOOL_TYPE = 'bool';
    public const DATE_TYPE = 'date';
    public const DATETIME_TYPE = 'datetime';
    public const DECIMAL_TYPE = 'decimal';
    public const FLOAT_TYPE = 'float';
    public const PERCENT_TYPE = 'percent';
    public const PRICE_TYPE = 'string';

    /**
     * @var string The name of the model attribute from which we get the value
     */
    protected $name = '';

    /**
     * @var string field name on ajax request. Use snake naming instead of camelCase! currentState --> current_state
     */
    protected $dataName = '';

    /**
     * @var string Column display name
     */
    protected $title = '';

    /**
     * @var string field width. At least one field should be set to 'auto' in order to grow with window size. (default 'auto', optional)
     */
    protected $width = 'auto';

    /**
     * @var string Content position inside the column (default 'left', optional).
     */
    protected $align = 'left';

    /**
     * @var bool If true, the column is orderable (default 'true', optional)
     */
    protected $orderable = true;

    /**
     * @var bool If true, the column is searchable (default 'true', optional)
     */
    protected $searchable = true;

    /**
     * @var string search html element, input text, select, number, date
     */
    protected $searchElement = '';

    /**
     * @var int If set, the field value will be truncated if it has more characters than the numeric value set (default 0: no limit, optional).
     */
    protected $maxLength = 0;

    /**
     * @var string  Column format. {'string', 'bool', 'date', 'datetime', 'decimal', 'float', 'percent', 'price'} (default string)
     */
    protected $type = 'string';

    /**
     * @var string  Style classes (format: "class1 class2") add to column (default empty, optional)
     */
    protected $class = '';

    /**
     * @var string column helper
     */
    protected $hint = '';

    /**
     * @var bool column visibility
     */
    protected $show = true;

    /**
     * @var string cell suffix etc kg,
     */
    protected $suffix = ''; // kg

    /**
     * @var string|null
     */
    protected $defaultContent;


    /**
     * ListFieldHelper constructor.
     *
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title)
    {
        $this->setName($name);
        $this->setDataName($name);
        $this->setTitle($title);
    }

    /**
     * Create new ListFieldHelper instance
     * @param string $name
     * @param string $title
     * @return ListFieldHelper
     */
    public static function to(string $name, string $title)
    {
        return new static($name, $title);
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ListFieldHelper
     */
    public function setTitle(string $title): ListFieldHelper
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * @param string $width
     * @return ListFieldHelper
     */
    public function setWidth(string $width): ListFieldHelper
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlign(): string
    {
        return $this->align;
    }

    /**
     * @param string $align
     * @return ListFieldHelper
     */
    public function setAlign(string $align): ListFieldHelper
    {
        $this->align = $align;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOrderable(): bool
    {
        return $this->orderable;
    }

    /**
     * @param bool $orderable
     * @return ListFieldHelper
     */
    public function setOrderable(bool $orderable): ListFieldHelper
    {
        $this->orderable = $orderable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Set column searchable. The default search html element is text.
     * @param bool $searchable
     * @return ListFieldHelper
     */
    public function setSearchable(bool $searchable): ListFieldHelper
    {
        $this->searchable = $searchable;

        if (!$this->isSearchable()) {
            $this->searchElement = '';
        }

        return $this;
    }

    /**
     * Set searchable to true, and set search type to select.
     * @param array $options Select options
     * @return ListFieldHelper
     */
    public function setSearchTypeSelect($options)
    {
        $this->setSearchable(true);
        $this->searchElement = FormSelectFieldHelper::to($this->name,'', $options,null)
            ->setClass('form-control-sm form-control filter-input')
            ->setElementId($this->name.'-filter-input')
            ->renderTag();
        return $this;
    }

    /**
     * Set searchable to true, and set search type to select. Fill options 1 => yes 0 => No.
     * @return ListFieldHelper
     */
    public function setSearchTypeBool()
    {
        $this->setSearchTypeSelect([''=>'-','1' => 'Igen', 0 => 'Nem']);
        return $this;
    }

    /**
     * Set searchable to true, and set search type to text.
     * @return ListFieldHelper
     */
    public function setSearchTypeText()
    {
        $this->setSearchable(true);
        $this->searchElement = FormInputFieldHelper::to($this->name, FormInputFieldHelper::TEXT_TYPE)
            ->setClass('form-control-sm form-control filter-input')
            ->setElementId($this->name.'-filter-input')
            ->renderTag();
        return $this;
    }

    /**
     * @param string $content
     */
    public function setSearchElement($content){
        $this->searchElement = $content;
    }

    /**
     * @return string
     */
    public function getSearchElement(): string
    {

        if ($this->isSearchable() && empty($this->searchElement)) {
            $this->setSearchTypeText();
        }

        return $this->searchElement;
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
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return ListFieldHelper
     */
    public function setClass(string $class): ListFieldHelper
    {
        $this->class = $class;
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
     * @return bool
     */
    public function isShow(): bool
    {
        return $this->show;
    }

    /**
     * @param bool $show
     * @return ListFieldHelper
     */
    public function setShow(bool $show): ListFieldHelper
    {
        $this->show = $show;
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

    public function toArray()
    {
        $property = [
            'name' => $this->getName(),
            'data' => $this->getDataName(),
            'orderable' => $this->isOrderable(),
            'searchable' => $this->isSearchable(),
            'width' => $this->getWidth(),
        ];

        if($this->getDefaultContent() !== null){
            $property['defaultContent'] = $this->getDefaultContent();
        }

        return $property;
    }

    /**
     * @return string
     */
    public function getDataName(): string
    {
        return $this->dataName;
    }

    /**
     * @param string $dataName
     * @return ListFieldHelper
     */
    public function setDataName(string $dataName): ListFieldHelper
    {
        $this->dataName = $dataName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * @param string $defaultContent
     * @return ListFieldHelper
     */
    public function setDefaultContent(string $defaultContent): ListFieldHelper
    {
        $this->defaultContent = $defaultContent;
        return $this;
    }



}
