<?php

namespace App\Http\Components\ListHelper;

use Yajra\DataTables\Html\Column;

class ListFieldHelper
{

    /**
     * @var string The name of the model attribute from which we get the value
     */
    protected $name = '';

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
    protected $style = '';

    protected $hint = '';

    protected $show = true;

    protected $suffix = ''; // kg


    /**
     * ListFieldHelper constructor.
     *
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title)
    {
        $this->setName($name);
        $this->setTitle($title);
    }

    /**
     * Create new ListFieldHelper instance
     * @param string $name
     * @param string $title
     * @return ListFieldHelper
     */
    public static function to(string $name, string $title){
        return new static($name,$title);
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
     * @param bool $searchable
     * @return ListFieldHelper
     */
    public function setSearchable(bool $searchable): ListFieldHelper
    {
        $this->searchable = $searchable;
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
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * @param string $style
     * @return ListFieldHelper
     */
    public function setStyle(string $style): ListFieldHelper
    {
        $this->style = $style;
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

    /**
     * @return Column
     */
    public function convertToColumn(){
        $col = Column::make($this->getName())
            ->name($this->getName())
            ->title($this->getTitle())
            ->orderable($this->isOrderable())
            ->searchable($this->isSearchable())
            ->width($this->getWidth())
            ->visible($this->isShow());
        $col->search = '<input type="text">';
        return $col;
    }

}
