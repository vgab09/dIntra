<?php

namespace App\Http\Components\ListHelper;

use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;

class ListFieldHelper implements ListFieldHelperInterface
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
        $this->setDataName(Str::snake($name));
        $this->setTitle($title);
    }

    /**
     * Create new ListFieldHelper instance
     * @param string $name
     * @param string $title
     * @return ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setName(string $name): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setTitle(string $title): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setWidth(string $width): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setAlign(string $align): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setOrderable(bool $orderable): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setSearchable(bool $searchable): ListFieldHelperInterface
    {
        $this->searchable = $searchable;

        if (!$this->isSearchable()) {
            $this->searchElement = '';
        }

        return $this;
    }

    /**
     * Set searchable to true, and set search type to select.
     * @param array|Collection|iterable $options Select options
     * @return ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setSearchTypeBool()
    {
        $this->setSearchTypeSelect([''=>'-','1' => 'Igen', 0 => 'Nem']);
        return $this;
    }

    /**
     * Set searchable to true, and set search type to text.
     * @return ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setMaxLength(int $maxLength): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setType(string $type): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setClass(string $class): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setHint(string $hint): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setShow(bool $show): ListFieldHelperInterface
    {
        $this->show = $show;
        return $this;
    }

    public function hasSuffix(): bool{
        return !empty($this->suffix);
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
     * @return ListFieldHelperInterface
     */
    public function setSuffix(string $suffix): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setDataName(string $dataName): ListFieldHelperInterface
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
     * @return ListFieldHelperInterface
     */
    public function setDefaultContent(string $defaultContent): ListFieldHelperInterface
    {
        $this->defaultContent = $defaultContent;
        return $this;
    }



}
