<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.02.
 * Time: 14:35
 */

namespace App\Http\Components\Providers;



use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

class MenuProvider implements ProviderInterface
{

    protected function buildLeftMenu(){
        $menu = Menu::new()
            ->addClass('nav navbar-nav')
            ->link('#', 'Szabadság igénylés')
            ->submenu(
                Link::to('#', '<i class="menu-icon fa fa-tasks"></i>Szabadság igények')
                    ->addClass('dropdown-toggle')
                    ->setAttributes(['data-toggle' => 'dropdown', 'aria-haspopup' => 'true','aria-expanded' => 'false']),
                Menu::new()
                    ->addClass('sub-menu children dropdown-menu')
                    ->link('#', '<i class="menu-icon fas fa-gavel"></i>Döntésre váró szabadságok')
                    ->link('#', '<i class="menu-icon fas fa-eye"></i>Szabadságok áttekintése')
                    ->addParentClass('menu-item-has-children dropdown')
            )
            ->submenu(
                Link::to('#', '<i class="menu-icon fas fa-scroll"></i>Szabadság szabályzás')
                    ->addClass('dropdown-toggle')
                    ->setAttributes(['data-toggle' => 'dropdown', 'aria-haspopup' => 'true','aria-expanded' => 'false']),
                Menu::new()
                    ->addClass('sub-menu children dropdown-menu')
                    ->link('#', '<i class="menu-icon fas fa-pencil-ruler"></i>Szabadság típusok')
                    ->link('#', '<i class="menu-icon fas fa-coffee"></i>Munkanapok')
                    ->link('#', '<i class="menu-icon fas fa-tree"></i>Munkaszüneti napok')
                    ->addParentClass('menu-item-has-children dropdown')
            )
            ->submenu(
                Link::to('#', '<i class="menu-icon fas fa-user-cog"></i>Munkatársak')
                    ->addClass('dropdown-toggle')
                    ->setAttributes(['data-toggle' => 'dropdown', 'aria-haspopup' => 'true','aria-expanded' => 'false']),
                Menu::new()
                    ->addClass('sub-menu children dropdown-menu')
                    ->link('#', '<i class="menu-icon fas fa-users"></i>Munkatársak')
                    ->link('#', '<i class="menu-icon fas fa-network-wired"></i>Osztályok')
                    ->link(route('designationIndex'), '<i class="menu-icon fas fa-project-diagram"></i>Beosztások')
                    ->addParentClass('menu-item-has-children dropdown')

            )
            ->submenu(
                Link::to('#', '<i class="menu-icon fas fa-cogs"></i>Beállítások')
                    ->addClass('dropdown-toggle')
                    ->setAttributes(['data-toggle' => 'dropdown', 'aria-haspopup' => 'true','aria-expanded' => 'false']),
                Menu::new()
                    ->addClass('sub-menu children dropdown-menu')
                    ->link('#', '<i class="menu-icon fas fa-object-ungroup"></i>Felhasználói csoportok')
                    ->link('#', '<i class="menu-icon fas fa-user-shield"></i>Jogosultságok')
                    ->addParentClass('menu-item-has-children dropdown')
            );

        return $menu;

    }


    public function provide(){
        return $this->buildLeftMenu()->render();
    }


}