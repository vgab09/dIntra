<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.04.
 * Time: 19:33
 */

namespace App\Http\Components\Providers;


use App\Persistence\Models\Employee;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

class UserProvider implements ProviderInterface
{

    /**
     * @var Employee
     */
    protected $currentUser;

    public function init(){
        $this->currentUser = Auth::user();
        if(empty($this->currentUser)){
            throw new AuthenticationException('Unauthenticated.');
        }
    }

    protected function buildUserMenu(){

        $this->init();

        $menu = Menu::new([
            Link::to('#', sprintf('%s <span class="caret"></span>',$this->currentUser->name))
                ->addClass('dropdown-toggle')
                ->setAttributes(['data-toggle' => 'dropdown', 'role' => 'button']),
                ]
            )
            ->submenu(
              Menu::new([
                      Link::toRoute('editProfile','Profil')
                          ->addClass('nav-link'),
                      Link::toRoute('logout','Kijelentkezés')
                          ->addClass('nav-link'),
                    ]
              )
              ->withoutParentTag()
              ->withoutWrapperTag()
              ->wrap('div',['class' => 'user-menu dropdown-menu'])
            )
            ->withoutParentTag()
            ->withoutWrapperTag()
            ->wrap('div',['class' => 'user-area dropdown']);


        return $menu;
    }


    public function provide(){
        return $this->buildUserMenu()->render();
    }

}