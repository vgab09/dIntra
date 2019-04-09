<?php

namespace App\Http\Controllers\Permission;


use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Components\ToolbarLink\ToolbarLinks;
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
        ])
            ->addTimeStamps()
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_role', route('editRole', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_role', route('deleteRole', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            })
            ->setToolbarLinkInstance(
                ToolbarLinks::make()->addLinkIfCan('create_role',route('newRole'),'<i class="fas fa-plus-circle"></i> Új hozzáadása')
            )
            ->setTitle('Felhasználói csoportok');

    }
}