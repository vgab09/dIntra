<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019.03.17.
 * Time: 17:47
 */

namespace App\Http\Controllers\LeavePolicy;


use App\Http\Components\FormHelper\FormCheckboxFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Components\ToolbarLink\ToolbarLinks;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\LeavePolicy;
use App\Persistence\Models\LeaveType;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = LeavePolicy::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('leavePolicy',$model,[
           FormSelectFieldHelper::to('id_leave_type','Szabadság típusa',LeaveType::getLeaveTypeOptions()),
           FormInputFieldHelper::toText('name','Név'),
           FormInputFieldHelper::toNumber('days','Napok száma')->setDescription('Csak egész számok elfogadottak'),
            FormCheckboxFieldHelper::toSwitch('active','Engedélyezve'),
        ]);
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
       return ListHelper::to('leavePolicy',[
           ListFieldHelper::to('LeaveType.name','Szabadság típus')->setSearchTypeSelect(LeaveType::getLeaveTypeOptions()->prepend('-','')),
           ListFieldHelper::to('name','Név'),
           ListFieldHelper::to('days','Napok száma')->setSuffix(' nap'),
           ListFieldHelper::to('active','Engedélyezve')->setType(ListFieldHelper::BOOL_TYPE),
       ])
           ->setTitle('Szabadság szabályok')
           ->addRowActions(function ($model) {
               return FormDropDownFieldHelper::to('action')
                   ->addActionLinkIfCan('update_leave_policy', route('editLeavePolicy', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                   ->addActionLinkIfCan('delete_leave_policy', route('deleteLeavePolicy', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                   ->renderTag();
           })
           ->setToolbarLinkInstance(
               ToolbarLinks::make()->addLinkIfCan('create_leave_policy',route('newLeavePolicy'),'<i class="fas fa-plus-circle"></i> Új hozzáadása')
           );
    }

    /**
     * Get DataTable rows
     *
     * @return Model|Collection|Builder
     */
    protected function collectListData()
    {
        return LeavePolicy::with('LeaveType');
    }


}