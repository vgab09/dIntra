<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.02.03.
 * Time: 16:14
 */

namespace App\Http\Controllers\Employee;



use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
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
            FormInputFieldHelper::toDate('start','Kezdés')->setRequired(),
            FormInputFieldHelper::toDate('end','Vége')->setRequired(),
            FormInputFieldHelper::toTextarea('description','Megjegyzés'),
        ]);
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Plusz munkanapok szerkesztése');
    }

    protected function getFormHelperToInsert($model)
    {
        return parent::getFormHelperToInsert($model)->setTitle('Új munkanap hozzáadása');
    }


    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('employee',[
            ListFieldHelper::to('id_employee','#'),
            ListFieldHelper::to('designation.name','Beosztás')->setDefaultContent('-'),
            ListFieldHelper::to('department.name','Osztály')->setDefaultContent('-'),
            ListFieldHelper::to('employmentForm.name','EmploymentForm')->setDataName('employment_form.name'),
            ListFieldHelper::to('hiring_date','Munkaviszony kezdete')->setType('datetime'),
            ListFieldHelper::to('termination_date','Munkaviszony vége')->setType('datetime')->setDefaultContent('-'),
            ListFieldHelper::to('name','Név'),
            ListFieldHelper::to('email','Email'),
            ListFieldHelper::to('date_of_birth','Születési dátum')->setType('date'),

        ])
            ->addTimeStamps()
            ->setTitle('Munkatársak')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_employee', route('editEmployee', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_employee', route('deleteEmployee', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            });
    }

    protected function collectListData()
    {
        return Employee::with('designation','department','employmentForm');
    }
}