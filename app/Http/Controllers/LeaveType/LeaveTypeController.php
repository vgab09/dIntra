<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.02.03.
 * Time: 16:14
 */

namespace App\Http\Controllers\LeaveType;


use App\Http\Components\FormHelper\FormCheckboxFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\LeaveType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LeaveTypeController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = LeaveType::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('leave_type',$model,[
           FormInputFieldHelper::toText('name','Név')->setRequired(),
        ]);
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Szabadság típusok szerkesztése');
    }

    protected function getFormHelperToInsert()
    {
        return parent::getFormHelperToInsert()->setTitle('Új Szabadság típus hozzáadása');
    }


    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('leave_type',[
            ListFieldHelper::to('id_leave_type','#'),
            ListFieldHelper::to('name','Név'),
        ])
            ->addTimeStamps()
            ->setTitle('Szabadság típusok')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_leave_type', route('editLeaveType', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_leave_type', route('deleteLeaveType', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            });
    }

    protected function collectListData()
    {
        return LeaveType::query();
    }

    /**
     * @param $id
     * @return bool|View
     */
    protected function confirmDelete($id)
    {
        /**
         * @var LeaveType $leaveType
         */
        $leaveType = LeaveType::query()->withCount('leavePolicies')->findOrFail($id);
        $form = FormHelper::to('confirmLeaveType')->setTitle('Törlési müvelet');

        if (!empty($leaveType->leave_policies_count)) {
            $form->addField(
                FormSelectFieldHelper::to('contractAction[leavePolicies]', 'Kapcsolódó Szabadság szabály bejegyzések (' . $leaveType->leave_policies_count . '):',
                    [
                        'DELETE' => 'Törlése',
                        'Áthelyezése ide:' => $leaveType->getAlternativeLeaveTypeOptions(),
                    ]
                )
            );
            return $form->render();
        }

        return true;
    }

    public function resolveContractAndDelete($id, Request $request)
    {
        $leaveType = LeaveType::with(['leavePolicies'])->findOrFail($id);
        if ($this->resolveRelationContract($leaveType, $leaveType->getAlternativeLeaveTypeOptions($leaveType->getKey()), $request->get('contractAction', []))) {
            $leaveType->delete();
            return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres törlés');
        } else {
            return $this->redirectError($this->getFailedRedirectUrl(), 'Sikertelen törlés');
        }
    }


}