<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.01.15.
 * Time: 18:31
 */

namespace App\Http\Components\FormHelper;


use Illuminate\Support\Collection;
use Illuminate\Contracts\Auth\Access\Gate;
use Spatie\Menu\Item;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

class FormDropDownFieldHelper extends FormFieldHelper
{

    /**
     * @var collection actions
     */
    protected $actions;

    /**
     * FormFieldHelper constructor.
     * @param $name string Field name
     * @param string $label Field label
     * @param Link[] $actions
     */
    public function __construct(string $name, string $label = '', $actions = [])
    {
        parent::__construct($name, $label);
        $this->actions = new Collection($actions);
    }

    /**
     * Create a new FormDropDownFieldHelper instance
     * @param $name string Field name
     * @param string $label Field label
     * @param Link[] $actions
     * @return FormDropDownFieldHelper
     */
    public static function to(string $name, string $label = '', $actions = []): FormDropDownFieldHelper{
        return new static( $name,  $label, $actions);
    }

    /**
     * Add an action to the dropdown if a (non-strict) condition is met.
     *
     * @param bool $condition
     * @param \Spatie\Menu\Item $item
     *
     * @return $this
     */
    public function addActionIf($condition, Item $item)
    {
        if (is_callable($condition) ? $condition() : $condition) {
            $this->actions->push($item);
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
    public function addActionLinkIfCan($authorization, string $url, string $text)
    {
        $abilityArguments = is_array($authorization) ? $authorization : [$authorization];
        $ability = array_shift($abilityArguments);

        return $this->addActionIf(app(Gate::class)->allows($ability, $abilityArguments), Link::to($url, $text));
    }


    /**
     * Render the form element with label, and divs
     * @return string
     */
    public function render()
    {
        // TODO: Implement render() method.
    }

    /**
     * Render only the form element
     * @return string
     */
    public function renderTag()
    {
       return Menu::new([
               $this->actions->shift()
                    ->addClass('btn btn-sm btn-primary'),
                Link::to('#', '')
                    ->addClass('btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split')
                    ->setAttributes(['data-toggle' => 'dropdown', 'role' => 'button']),
            ]
        )
            ->submenu(
                    Menu::new($this->actions->toArray())
                    ->addItemClass('dropdown-item')
                    ->withoutParentTag()
                    ->withoutWrapperTag()
                    ->wrap('div',['class' => 'dropdown-menu dropdown-menu-right'])
            )
            ->withoutParentTag()
            ->withoutWrapperTag()
            ->wrap('div',['class' => 'btn-group']);
    }

}