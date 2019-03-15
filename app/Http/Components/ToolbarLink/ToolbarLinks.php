<?php

namespace App\Http\Components\ToolbarLink;


use Illuminate\Contracts\Auth\Access\Gate;

class ToolbarLinks
{

    protected $items;
    protected $baseTemplate;

    /**
     * ToolbarLinks constructor.
     * @param array|iterable $items
     */
    public function __construct($items = [])
    {
        $this->items = collect($items);
        $this->baseTemplate = 'toolbars.card_toolbar';
    }

    /**
     * @param array $items
     * @return ToolbarLinks
     */
    public static function make($items = [])
    {
        return new static($items);
    }

    /**
     * @return string
     */
    public function getBaseTemplate(): string
    {
        return $this->baseTemplate;
    }

    /**
     * @param string $baseTemplate
     * @return ToolbarLinks
     */
    public function setBaseTemplate(string $baseTemplate): ToolbarLinks
    {
        $this->baseTemplate = $baseTemplate;
        return $this;
    }


    /**
     * @param ToolbarLinkableInterface $link
     */
    public function addLink(ToolbarLinkableInterface $link)
    {
        $this->items->push($link);
    }

    /**
     * Add a link to the toolbar if a (non-strict) condition is met.
     *
     * @param bool $condition
     * @param string $url
     * @param string $text
     * @param string $class
     * @return $this
     */
    public function addLinkIf($condition, string $url, string $text, $class = 'nav-link')
    {
        if (is_callable($condition) ? $condition() : $condition) {
            $this->addLink(Link::to($url, $text)->setClass($class));
        }

        return $this;
    }

    /**
     * @param string|array $authorization
     * @param string $url
     * @param string $text
     *
     * @param string $class
     * @return $this
     */
    public function addLinkIfCan($authorization, string $url, string $text, $class = 'nav-link')
    {
        $abilityArguments = is_array($authorization) ? $authorization : [$authorization];
        $ability = array_shift($abilityArguments);

        return $this->addLinkIf(app(Gate::class)->allows($ability, $abilityArguments), $url, $text, $class);
    }

    /**
     * @return ToolbarLinkableInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        return view($this->baseTemplate, ['items' => $this->getItems()]);
    }

}