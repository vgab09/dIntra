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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

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
        return FormHelper::to('department', $model, [
            FormInputFieldHelper::toText('name', 'Név')->setRequired(),
            FormSelectFieldHelper::to('id_leader', 'Osztály vezető', $this->getLeaderOptions()),
            FormSelectFieldHelper::to('id_parent', 'Szülő osztály', $this->getAlternativeDepartmentOptions($model->getKey())),
            FormCheckboxFieldHelper::toSwitch('active', 'Aktív'),
            FormInputFieldHelper::toTextarea('description', 'Leírás'),
        ]);
    }

    protected function getLeaderOptions()
    {
        return Employee::all('id_employee', 'name')
            ->pluck('name', 'id_employee')
            ->prepend('-', '');
    }

    protected function getAlternativeDepartmentOptions($self_id)
    {
        return Department::query('id_department', 'name')
            ->whereKeyNot($self_id)
            ->pluck('name', 'id_department')
            ->prepend('-', '');
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Osztály szerkesztése');
    }

    protected function getFormHelperToInsert($model)
    {
        return parent::getFormHelperToInsert($model)->setTitle('Új osztály hozzáadása');
    }


    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('department', [
            ListFieldHelper::to('id_department', '#'),
            ListFieldHelper::to('name', 'Név'),
            ListFieldHelper::to('pd.name', 'Szülő')
                ->setDefaultContent('-'),
            ListFieldHelper::to('e.name', 'Vezető')
                ->setDefaultContent('-'),
            ListFieldHelper::to('active', 'Aktív')
                ->setType('bool')
                ->setSearchTypeBool(),
            ListFieldHelper::to('description', 'Leírás')
                ->setMaxLength('20'),
        ])
            ->addTimeStamps()
            ->setTitle('Osztályok')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_department', route('editDepartment', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_department', route('deleteDepartment', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            });
    }

    protected function collectListData()
    {
        return Department::query()->select(['departments.id_department as id_department', 'departments.name as name', 'pd.name as pd.name', 'e.name as e.name', 'departments.active', 'departments.description', 'departments.created_at as created_at', 'departments.updated_at as updated_at'])
            ->from('departments')
            ->leftJoin('departments as pd', 'departments.id_parent', '=', 'pd.id_department')
            ->leftJoin('employees as e', 'departments.id_leader', '=', 'e.id_employee');
    }

    /**
     * @param $id
     * @return bool|View
     */
    protected function confirmDelete($id)
    {
        $department = Department::query()->withCount('children')->withCount('employees')->findOrFail($id);
        $form = FormHelper::to('confirmDesignation')->setTitle('Törlési müvelet');
        $alternativeDepartments = $this->getAlternativeDepartmentOptions($id);
        if (!empty($department->children_count)) {
            $form->addField(
                FormSelectFieldHelper::to('contractAction[\'children\']', 'Kapcsolódó osztály bejegyzések ('.$department->children_count.'):',
                    [
                        'DELETE' => 'Törlése',
                        'Áthelyezése ide:' => $alternativeDepartments,
                    ]
                )
            );
        }

        if (!empty($department->employees_count)) {
            $form->addField(
                FormSelectFieldHelper::to('contractAction[\'employees\']', 'Kapcsolódó felhasználó bejegyzések ('.$department->employees_count.'):',
                    [
                        'DELETE' => 'Törlése',
                        'Áthelyezése ide:' => $alternativeDepartments,
                    ]
                )
            );
        }

        if (count($form->getFormItems())) {
            return $form->render();
        }

        return true;
    }

    public function resolveContractAndDelete($id, Request $request)
    {
        $department = Department::with(['children','employees'])->findOrFail($id);
        $request->get('action'));
        /**
         * @var HasMany $relation
         */
        foreach ($department->getRelations() as $relation){
            $localProperty = $relation->getForeignKeyName();

            if()

        }


        $childrenAction = $request->get('action');
        $employeAction = $request->get('employeesAction');




    }
}