<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.03.03.
 * Time: 16:09
 */

namespace App\Http\Components\ToolbarLink;


interface ToolbarLinkableInterface
{
    /**
     * @param string $url
     * @return mixed
     */
    public function setUrl(string $url);

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @param string $text
     * @return mixed
     */
    public function setText(string $text);

    /**
     * @return string
     */
    public function getText():string;

}