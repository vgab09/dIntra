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
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Guard;
use App\Persistence\Models\Permission;

class PermissionController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = Permission::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('role',$model,[
            FormInputFieldHelper::to('name','text')->setRequired(),
        ]);
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('permissions',[
            ListFieldHelper::to('id','Azonosító'),
            ListFieldHelper::to('name','Név')
        ])
            ->addTimeStamps()
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_permission', route('editPermission', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_permission', route('deletePermission', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            })
            ->setToolbarLinkInstance(
                ToolbarLinks::make()->addLinkIfCan('create_permission',route('newPermission'),'<i class="fas fa-plus-circle"></i> Új hozzáadása')
            )
            ->setTitle('Jogosultságok');

    }
}