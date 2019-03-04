<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.03.03.
 * Time: 16:07
 */

namespace App\Http\Components\ToolbarLink;


use Collective\Html\HtmlBuilder;

class Link implements ToolbarLinkableInterface
{

    /**
     * @var string
     */
    protected $class = '';

    /**
     * @var string
     */
    protected $elementId = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $text = '';

    /**
     * Link constructor.
     * @param string $url
     * @param string $text
     */
    public function __construct(string $url, string $text)
    {
        $this->setUrl($url);
        $this->setText($text);
    }

    public static function to(string $url, string $text){
        return new static($url,$text);
    }

    /**
     * @param string $url
     * @return Link
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $text
     * @return Link
     */
    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
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
     * @return Link
     */
    public function setClass(string $class): Link
    {
        $this->class = $class;
        return $this;
    }

    public function addClass($class): Link{
        $this->class .= ' '.$class;
        return $this;
    }

    /**
     * @return string
     */
    public function getElementId(): string
    {
        return $this->elementId;
    }

    /**
     * @param string $elementId
     * @return Link
     */
    public function setElementId(string $elementId): Link
    {
        $this->elementId = $elementId;
        return $this;
    }



    public function render(){
        return sprintf('<a id="%s" class="%s" href="%s">%s</a>',$this->getElementId(),$this->getClass(),$this->getUrl(),$this->getText());
    }
}