<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.04.
 * Time: 19:33
 */

namespace App\Http\Components\Providers;


use App\Persistence\Models\Employee;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Spatie\Menu\Laravel\Menu;

class Userprovider
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
        $menu = Menu::new()
            ->wrap('div',['class' => 'user-area dropdown']);
    }


    public function getUserMenu(){
        return $this->buildUserMenu();
    }

}