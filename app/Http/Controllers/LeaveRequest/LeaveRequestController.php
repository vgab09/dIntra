<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.03.08.
 * Time: 7:03
 */

namespace App\Http\Controllers\LeaveRequest;


use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Employee;
use App\Persistence\Models\LeavePolicy;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveType;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestController extends BREADController
{
    public function __construct()
    {
        /**
         * @var
         */
        $this->modelClass = LeaveRequest::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        // TODO: Implement buildFormHelper() method.
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {

        return ListHelper::to('LeaveRequest', [
            ListFieldHelper::to('id_leave_request', '#'),
            ListFieldHelper::to('employees.name', 'Munkatárs'),
            ListFieldHelper::to('leave_types.name', 'Típus')->setSearchTypeSelect(LeaveType::getLeaveTypeOptions()->prepend('-', '')),
            ListFieldHelper::to('start_at', 'Kezdet')->setType('date'),
            ListFieldHelper::to('end_at', 'Vége')->setType('date'),
            ListFieldHelper::to('days', 'Napok száma'),
            ListFieldHelper::to('comment', 'Megjegyzés')->setMaxLength('60'),
        ])
            ->addTimeStamps()
            ->setTitle('Szabadság igények')
            ->addRowActions(function ($model) {
            return FormDropDownFieldHelper::to('action')
                ->addActionLinkIfCan('accept_leave_request','', '<i class="fas fa-pencil-alt"></i> Elfogadás')
                ->addActionLinkIfCan('denny_leave_request', '', '<i class="fas fa-trash-alt"></i> Elutasítás')
                ->renderTag();
        });
    }

    /**
     * Get DataTable rows
     *
     * @return \Eloquent|Collection|QueryBuilder
     */
    protected function collectListData()
    {
        return LeaveRequest::query()
            ->select(['leave_requests.*','leave_types.name as leave_types_name','employees.name as employees_name'])
            ->from('leave_requests')
            ->join('leave_policies','leave_requests.id_leave_policy','=','leave_policies.id_leave_policy')
            ->join('leave_types','leave_policies.id_leave_type','=','leave_types.id_leave_type')
            ->join('employees','leave_requests.id_employee','=','employees.id_employee')
            ->where('leave_requests.status','=',LeaveRequest::STATUS_PENDING);
    }

    /**
     * @param int $id_leave_request
     */
    public function accept($id_leave_request){

        /**
         * @var LeaveRequest $leaveRequest
         */
        $leaveRequest = LeaveRequest::findOrFail($id_leave_request);
        $leaveRequest->accept();
        $this->redirecetSucces();


    }

    /**
     * @param int $id_leave_request
     */
    public function showDennyForm($id_leave_request){
        
    }

    /**
     * @param int $id_leave_request
     */
    public function denny($id_leave_request){

    }


}