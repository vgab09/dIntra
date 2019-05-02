<?php

namespace App\Http\Controllers\EmploymentForm;


use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Components\ToolbarLink\ToolbarLinks;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\EmploymentForm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class EmploymentFormController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = EmploymentForm::class;
    }


    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('leavePolicy',$model,[
            FormInputFieldHelper::toText('name','Név'),
        ]);
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('employmentForm',[
            ListFieldHelper::to('id_employment_form','Azonosító'),
            ListFieldHelper::to('name','Név'),
        ])
            ->addTimeStamps()
            ->setTitle('Munkarend')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_employment_form', route('editEmploymentForm', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_employment_form', route('deleteEmploymentForm', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            })
            ->setToolbarLinkInstance(
                ToolbarLinks::make()->addLinkIfCan('create_employment_form',route('newEmploymentForm'),'<i class="fas fa-plus-circle"></i> Új hozzáadása')
            );;
    }

    /**
     * @param $id
     * @return bool|View
     */
    protected function confirmDelete($id)
    {
        /**
         * @var EmploymentForm $employmentForm
         */
        $employmentForm = EmploymentForm::query()->withCount('employees')->findOrFail($id);
        $form = FormHelper::to('confirmEmploymentForm')->setTitle('Törlési müvelet');

        if (!empty($employmentForm->employees_count)) {
            $form->addField(
                FormSelectFieldHelper::to('contractAction[employees]', 'Kapcsolódó munkatárs bejegyzések (' . $employmentForm->employees_count . '):',
                    [
                        'DELETE' => 'Törlése',
                        'Áthelyezése ide:' => $employmentForm->getAlternativeEmploymentOptions(),
                    ]
                )
            );
            return $form->render();
        }

        return true;
    }

    public function resolveContractAndDelete($id, Request $request)
    {
        $employmentForm = EmploymentForm::with(['employees'])->findOrFail($id);
        $resolve = $this->resolveRelationContract($employmentForm, $employmentForm->getAlternativeEmploymentOptions(), $request->get('contractAction', []));
        if ($resolve === true) {
            $employmentForm->delete();
            return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres törlés');
        }
        return $resolve;
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Munkarend szerkesztése');
    }

    protected function getFormHelperToInsert()
    {
        return parent::getFormHelperToInsert()->setTitle('Új Munkarend rögzítése');
    }
}