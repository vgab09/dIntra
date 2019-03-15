<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.02.03.
 * Time: 16:14
 */

namespace App\Http\Controllers\Employee;



use App\Http\Components\FormHelper\FormCheckboxFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Components\ToolbarLink\Link;
use App\Http\Components\ToolbarLink\ToolbarLinks;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Department;
use App\Persistence\Models\Designation;
use App\Persistence\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class EmployeeController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = Employee::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('employee',$model,[
            FormInputFieldHelper::toText('name','Név')->setRequired(),
            FormInputFieldHelper::toEmail('email','Email')->setRequired(),
            FormInputFieldHelper::toDate('date_of_birth','Születési dátum'),
            FormSelectFieldHelper::to('id_designation','Beosztás')->setRequired(),
            FormSelectFieldHelper::to('id_department','Osztály')->setRequired(),
            FormSelectFieldHelper::to('id_employment_form','EmploymentForm')->setRequired(),
            FormInputFieldHelper::toDate('hiring_date','Munkaviszony kezdete')->setRequired(),
            FormInputFieldHelper::toDate('termination_date','Munkaviszony vége'),
            FormCheckboxFieldHelper::toSwitch('active','Aktív'),
        ]);
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Plusz munkanapok szerkesztése');
    }

    protected function getFormHelperToInsert()
    {
        return parent::getFormHelperToInsert()->setTitle('Új munkanap hozzáadása');
    }


    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('employee',[
            ListFieldHelper::to('id_employee','#'),
            ListFieldHelper::to('designation.name','Beosztás')->setDefaultContent('-')->setSearchTypeSelect(Designation::getDesignationOptions()->prepend('-',''),'designations.id_designation'),
            ListFieldHelper::to('department.name','Osztály')->setDefaultContent('-')->setSearchTypeSelect(Department::getDepartmentOptions()),
            ListFieldHelper::to('employmentForm.name','EmploymentForm')->setDataName('employment_form.name'),
            ListFieldHelper::to('hiring_date','Munkaviszony kezdete')->setType('datetime'),
            ListFieldHelper::to('termination_date','Munkaviszony vége')->setType('datetime')->setDefaultContent('-'),
            ListFieldHelper::to('name','Név'),
            ListFieldHelper::to('email','Email'),
            ListFieldHelper::to('active','Aktív')->setType('bool'),
            ListFieldHelper::to('employmentForm.name','EmploymentForm')->setDataName('employment_form.name'),
            ListFieldHelper::to('date_of_birth','Születési dátum')->setType('date'),
            ListFieldHelper::to('hiring_date','Munkaviszony kezdete')->setType('datetime'),
            ListFieldHelper::to('termination_date','Munkaviszony vége')->setType('datetime')->setDefaultContent('-'),


        ])
            ->addTimeStamps()
            ->setTitle('Munkatársak')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_employee', route('editEmployee', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_employee', route('deleteEmployee', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            })
            ->setToolbarLinkInstance(
                ToolbarLinks::make()->addLinkIfCan('create_employee',route('newEmployee'),'<i class="fas fa-plus-circle"></i> Új hozzáadása')
            );
    }

    protected function collectListData()
    {
        return Employee::with('designation','department','employmentForm');
    }
}