<?php

namespace App\Http\Controllers\Permission;


use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role;

class RoleController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = Role::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('role',$model,[
            FormInputFieldHelper::to('name','text')->setRequired(),
            FormSelectFieldHelper::to('guard_name',Guard::getNames(Role::class))
        ]);
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('roles',[
            ListFieldHelper::to('name','Név'),
            ListFieldHelper::to('guard','Típus'),
        ])
            ->setTitle('Felhasználói csoportok')
            ->addTimeStamps();
    }
}