<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019.04.28.
 * Time: 10:48
 */

namespace App\Http\Components\ListHelper;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

interface ListFieldHelperInterface extends Arrayable
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return ListFieldHelperInterface
     */
    public function setName(string $name): ListFieldHelperInterface;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return ListFieldHelperInterface
     */
    public function setTitle(string $title): ListFieldHelperInterface;

    /**
     * @return string
     */
    public function getWidth(): string;

    /**
     * @param string $width
     * @return ListFieldHelperInterface
     */
    public function setWidth(string $width): ListFieldHelperInterface;

    /**
     * @return string
     */
    public function getAlign(): string;

    /**
     * @param string $align
     * @return ListFieldHelperInterface
     */
    public function setAlign(string $align): ListFieldHelperInterface;

    /**
     * @return bool
     */
    public function isOrderable(): bool;

    /**
     * @param bool $orderable
     * @return ListFieldHelperInterface
     */
    public function setOrderable(bool $orderable): ListFieldHelperInterface;

    /**
     * @return bool
     */
    public function isSearchable(): bool;

    /**
     * Set column searchable. The default search html element is text.
     * @param bool $searchable
     * @return ListFieldHelperInterface
     */
    public function setSearchable(bool $searchable): ListFieldHelperInterface;

    /**
     * Set searchable to true, and set search type to select.
     * @param array|Collection|iterable $options Select options
     * @return ListFieldHelperInterface
     */
    public function setSearchTypeSelect($options);

    /**
     * Set searchable to true, and set search type to select. Fill options 1 => yes 0 => No.
     * @return ListFieldHelperInterface
     */
    public function setSearchTypeBool();

    /**
     * Set searchable to true, and set search type to text.
     * @return ListFieldHelperInterface
     */
    public function setSearchTypeText();

    /**
     * @param string $content
     */
    public function setSearchElement($content);

    /**
     * @return string
     */
    public function getSearchElement(): string;

    /**
     * @return int
     */
    public function getMaxLength(): int;

    /**
     * @param int $maxLength
     * @return ListFieldHelperInterface
     */
    public function setMaxLength(int $maxLength): ListFieldHelperInterface;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return ListFieldHelperInterface
     */
    public function setType(string $type): ListFieldHelperInterface;

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param string $class
     * @return ListFieldHelperInterface
     */
    public function setClass(string $class): ListFieldHelperInterface;

    /**
     * @return string
     */
    public function getHint(): string;

    /**
     * @param string $hint
     * @return ListFieldHelperInterface
     */
    public function setHint(string $hint): ListFieldHelperInterface;

    /**
     * @return bool
     */
    public function isShow(): bool;

    /**
     * @param bool $show
     * @return ListFieldHelperInterface
     */
    public function setShow(bool $show): ListFieldHelperInterface;

    public function hasSuffix(): bool;

    /**
     * @return string
     */
    public function getSuffix(): string;

    /**
     * @param string $suffix
     * @return ListFieldHelperInterface
     */
    public function setSuffix(string $suffix): ListFieldHelperInterface;

    /**
     * @return string
     */
    public function getDataName(): string;

    /**
     * @param string $dataName
     * @return ListFieldHelperInterface
     */
    public function setDataName(string $dataName): ListFieldHelperInterface;

    /**
     * @return string|null
     */
    public function getDefaultContent();

    /**
     * @param string $defaultContent
     * @return ListFieldHelperInterface
     */
    public function setDefaultContent(string $defaultContent): ListFieldHelperInterface;
}