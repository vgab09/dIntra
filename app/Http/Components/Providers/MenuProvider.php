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
            ->html('<h3 class="menu-title">Szabadság igények</h3>')
                ->linkIfCan('list_leave_request',route('indexLeaveRequest'), '<i class="menu-icon fas fa-gavel"></i>Döntésre váró szabadságok')
                ->linkIfCan('list_leave_request',route('indexLeaveRequest'), '<i class="menu-icon fas fa-eye"></i>Szabadságok áttekintése')

            ->html('<h3 class="menu-title">Szabadság szabályzás</h3>')
                ->linkIfCan('list_leave_type', route('indexLeaveType'),'<i class="menu-icon fas fa-pencil-ruler"></i>Szabadság típusok')
                ->linkIfCan('list_leave_policy', route('indexLeavePolicy'),'<i class="menu-icon fas fa-asterisk"></i>Szabadság szabályok')

                ->linkIfCan('list_workday',route('indexWorkday'), '<i class="menu-icon fas fa-coffee"></i>Munkanapok')
                ->linkIfCan('list_holiday',route('indexHoliday'), '<i class="menu-icon fas fa-tree"></i>Munkaszüneti napok')

            ->html('<h3 class="menu-title">Munkatársak</h3>')
                ->linkIfCan('list_employee',route('indexEmployee'), '<i class="menu-icon fas fa-users"></i>Munkatársak')
                ->linkIfCan('list_department',route('indexDepartment'), '<i class="menu-icon fas fa-network-wired"></i>Osztályok')
                ->linkIfCan('list_designation',route('indexDesignation'), '<i class="menu-icon fas fa-project-diagram"></i>Beosztások')
                ->linkIfCan('list_employment_form','#', '<i class="menu-icon fas fa-users-cog"></i>Munkarend')

            ->html('<h3 class="menu-title">Beállítások</h3>')
            ->link('#', '<i class="menu-icon fas fa-object-ungroup"></i>Felhasználói csoportok')
            ->link('#', '<i class="menu-icon fas fa-user-shield"></i>Jogosultságok');

        return $menu;

    }

    public function provide(){
        return $this->buildLeftMenu()->render();
    }


}