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

    protected function getFormHelperToInsert($model)
    {
        return parent::getFormHelperToInsert($model)->setTitle('Új Szabadság típus hozzáadása');
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
}