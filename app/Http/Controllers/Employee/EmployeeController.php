<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.02.03.
 * Time: 16:14
 */

namespace App\Http\Controllers\Employee;

use App\Http\Components\FormHelper\FormCheckboxFieldHelper;
use App\Http\Components\FormHelper\FormChosenSelectFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Components\ToolbarLink\ToolbarLinks;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Department;
use App\Persistence\Models\Designation;
use App\Persistence\Models\Employee;
use App\Persistence\Models\EmploymentForm;
use App\Persistence\Models\LeaveType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class EmployeeController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = Employee::class;
    }

    /**
     * @param Employee|Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('employee',$model,[
            FormInputFieldHelper::toText('name','Név')->setRequired(),
            FormInputFieldHelper::toEmail('email','Email')->setRequired(),
            FormInputFieldHelper::toDate('date_of_birth','Születési dátum'),
            FormSelectFieldHelper::to('id_designation','Beosztás',
                Designation::getActiveDesignationOptions()
                    ->pluck('name','id_designation')
                    ->prepend('-','')
            ),
            FormSelectFieldHelper::to('id_department','Osztály',Department::getActiveDepartmentOptions()
                ->pluck('name','id_department')
                ->prepend('-','')
            ),
            FormSelectFieldHelper::to('id_employment_form','Munkarend',
                EmploymentForm::getEmploymentFormOptions()
                    ->pluck('name','id_employment_form')
            )->setRequired(),
            FormInputFieldHelper::toDate('hiring_date','Munkaviszony kezdete')->setRequired(),
            FormChosenSelectFieldHelper::to('leavePolicies', 'Hozzárendelt szabadság szabályok',
                LeaveType::with('leavePolicies')
                    ->get()
                    ->pluck('leavePolicies', 'name')
                    ->map(
                        function ($item) {
                            return $item->pluck('name', 'id_leave_policy');
                        })
            )
                ->setMultiple(),
            FormChosenSelectFieldHelper::to('roles','Jogosultsági csoport',
                Role::all(['id','name'])
                    ->pluck('name','id')
                    ->toArray()
            )
                ->setMultiple(),
            FormInputFieldHelper::toDate('termination_date','Munkaviszony vége'),
            FormCheckboxFieldHelper::toSwitch('active','Aktív'),
        ]);
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Munkatárs szerkesztése');
    }

    protected function getFormHelperToInsert()
    {
        $helper = parent::getFormHelperToInsert()->setTitle('Új munkatárs hozzáadása');
        $helper->addField(
        FormInputFieldHelper::toPassword('password','Jelszó')->setRequired()->setDescription('Minimum 6 karakter, kis - nagy betű, szám és speciális karakter')->setValue('')
        );
        return $helper;
    }


    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('employee',[
            ListFieldHelper::to('id_employee','#'),

            ListFieldHelper::to('designation.name','Beosztás')
                ->setDefaultContent('-')
                ->setSearchTypeSelect(
                    Designation::getDesignationOptions()
                        ->pluck('name','name')
                        ->prepend('-','')),

            ListFieldHelper::to('department.name','Osztály')
                ->setDefaultContent('-')
                ->setSearchTypeSelect(
                    Department::getDepartmentOptions()
                        ->pluck('name','name')
                        ->prepend('-','')
                ),
            ListFieldHelper::to('employmentForm.name','Munkarend')
            ->setSearchTypeSelect(
                EmploymentForm::getEmploymentFormOptions()
                ->pluck('name','name')
                ->prepend('-','')),
            ListFieldHelper::to('name','Név'),
            ListFieldHelper::to('active','Aktív')
                ->setType('bool')
            ->setSearchTypeBool(),

            ListFieldHelper::to('date_of_birth','Születési dátum')->setType('date'),
        ])
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

    /**
     * Update resource
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|RedirectResponse
     * @throws \Exception
     */
    public function update($id)
    {
        $form = $this->getFormHelperToUpdate(Employee::findOrFail($id));

        if(!$form->validate() || !$this->save($form->getModel())){
            return $form->render();
        }

        return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres módosítás');
    }

    public function insert()
    {
        $form = $this->getFormHelperToInsert();

        if(!$form->validateAndSave() || !$this->save($form->getModel())){
            return $form->render();
        }

        return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres létrehozás');

    }

    /**
     * @param Employee $model
     * @return bool
     */
    protected function save($model)
    {
        $roles = request('roles');
        $leavePolicies = request('leavePolicies',[]);

        if(empty($roles)){
            $this->alertError('A felhasználót legalább egy csoporthoz hozzá kell rendelni.');
            return false;
        }

        $model->syncRoles($roles);
        $model->leavePolicies()->sync($leavePolicies);
        $model->save();

        return true;

    }


}