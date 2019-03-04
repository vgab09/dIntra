<?php

namespace App\Http\Components\ToolbarLink;


class ToolbarLinks
{

    protected $items;

    /**
     * ToolbarLinks constructor.
     * @param array|iterable $items
     */
    public function __construct($items=[])
    {
        $this->items = collect($items);
    }

    /**
     * @param ToolbarLinkableInterface $link
     */
    public function addLink(ToolbarLinkableInterface $link){
        $this->items->push($link);
    }

    /**
     * Add a link to the toolbar if a (non-strict) condition is met.
     *
     * @param bool $condition
     * @param string url
     *
     * @return $this
     */
    public function addLinkIf($condition, string $url,string $text,$class='')
    {
        if (is_callable($condition) ? $condition() : $condition) {
            $this->addLink(Link::to($url,$text)->setClass($class));
        }

        return $this;
    }

    /**
     * @param string|array $authorization
     * @param string $url
     * @param string $text
     *
     * @return $this
     */
    public function addLinkIfCan($authorization, string $url, string $text,$class='')
    {
        $abilityArguments = is_array($authorization) ? $authorization : [$authorization];
        $ability = array_shift($abilityArguments);

        return $this->addLinkIf(app(Gate::class)->allows($ability, $abilityArguments), $url, $text, $class);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getItems(){
        return $this->items;
    }

    /**
     * @return string
     */
    public function render(){

        $content = '';

        foreach ($this->getItems() as $item){
            $content .= $item->render();
        }
        return $content;
    }

}