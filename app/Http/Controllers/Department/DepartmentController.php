<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.02.03.
 * Time: 16:14
 */

namespace App\Http\Controllers\Department;


use App\Http\Components\FormHelper\FormCheckboxFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Department;
use App\Persistence\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class DepartmentController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = Department::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('department',$model,[
           FormInputFieldHelper::toText('name','név')->setRequired(),
           FormSelectFieldHelper::to('id_leader','Vezető',Employee::all('id_employee','name')->pluck('name','id_employee')),
            FormSelectFieldHelper::to('id_parent','Szülő osztály',Department::all('id_department','name')->pluck('name','id_department')),
           FormCheckboxFieldHelper::toSwitch('active','Aktív'),
           FormInputFieldHelper::toTextarea('description','Leírás'),
        ]);
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('department',[
            ListFieldHelper::to('id_department','#'),
            ListFieldHelper::to('name','Név'),
            ListFieldHelper::to('parent.name','Szülő')
                ->setDefaultContent('-'),
            ListFieldHelper::to('leader.name','Vezető')
                ->setDefaultContent('-'),
            ListFieldHelper::to('active','Aktív')
                ->setType('bool')
                ->setSearchTypeBool(),
            ListFieldHelper::to('description','Leírás')
                ->setMaxLength('20')

        ])
            ->addTimeStamps()
            ->setTitle('Osztályok')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_department', route('editDepartment', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Megnyitás')
                    ->addActionLinkIfCan('update_department', route('editDepartment', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_department', route('deleteDepartment', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            });
    }

    protected function collectListData()
    {
        return Department::with(['leader','parent']);
    }
}