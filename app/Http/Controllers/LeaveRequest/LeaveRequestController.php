<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.03.08.
 * Time: 7:03
 */

namespace App\Http\Controllers\LeaveRequest;


use App\Http\Components\FormHelper\FormButtonFieldHelper;
use App\Http\Components\FormHelper\FormCustomViewFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Services\LeaveRequestService;
use App\Persistence\Models\Employee;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveType;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class LeaveRequestController extends BREADController
{
    public function __construct()
    {
        $this->modelClass = LeaveRequest::class;
    }

    /**
     * @param $id_leave_request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id_leave_request){

        /**
         * @var LeaveRequest $leaveRequest
         */
        $leaveRequest = LeaveRequest::findOrFail($id_leave_request);

        /**
         * @var Employee $user
         */
        $user = Auth::user();
        $history = $user->can('show_leave_request_history') ? $leaveRequest->history : null;

        return view('leaves.show',['leaveRequest' => $leaveRequest,'history'=>$history]);
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
                    ->addActionLinkIfCan('accept_leave_request', route('acceptLeaveRequest', $model->getKey()), '<i class="far fa-check-circle"></i> Elfogadás')
                    ->addActionLinkIfCan('list_leave_request', route('showLeaveRequest', $model->getKey()), '<i class="far fa-eye"></i> Megtekintés')
                    ->addActionLinkIfCan('denny_leave_request', route('showDennyLeaveRequestForm', $model->getKey()), '<i class="far fa-times-circle"></i> Elutasítás')
                    ->renderTag();
            });
    }

    /**
     * Get DataTable rows
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function collectListData()
    {
        return LeaveRequest::query()
            ->select(['leave_requests.*', 'leave_types.name as leave_types_name', 'employees.name as employees_name'])
            ->from('leave_requests')
            ->join('leave_types', 'leave_requests.id_leave_type', '=', 'leave_types.id_leave_type')
            ->join('employees', 'leave_requests.id_employee', '=', 'employees.id_employee')
            ->where('leave_requests.status', '=', LeaveRequest::STATUS_PENDING);
    }

    /**
     * @param $id_leave_request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function accept($id_leave_request)
    {

        /**
         * @var LeaveRequest $leaveRequest
         */
        $leaveRequest = LeaveRequest::findOrFail($id_leave_request);

        try{
            $service = new LeaveRequestService($leaveRequest);
            $service->accept();
        }
        catch (ValidationException $e){
            return redirect(route('showDennyLeaveRequestForm', $leaveRequest->getKey()))->withErrors($e->validator->getMessageBag());
        }
        return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Szabadság elfogadva');

    }

    /**
     * @param LeaveRequest $leaveRequest
     * @return FormHelper
     */
    protected function buildDennyForm(LeaveRequest $leaveRequest)
    {

        return FormHelper::to('dennyLeave', $leaveRequest, [
            FormCustomViewFieldHelper::to('leave_information', 'leaves.partials.leave_request_information', ['leaveRequest' => $leaveRequest]),
            FormInputFieldHelper::toTextarea('reason', 'Elutasítás oka:')
                ->setRequired()
                ->setHint('Az elutasítás okát kötelező megadni.')
        ])
            ->setTitle('Szabadság igény elutasítása')
            ->setSubmit(
                FormButtonFieldHelper::toSubmit('Elutasítás')
                    ->setIconClass('far fa-times-circle')
                    ->setClass('btn btn-primary')
            )
            ->setActionFromMethod(['LeaveRequest\LeaveRequestController@denny', $leaveRequest->getKey()]);
    }

    /**
     * @param $id_leave_request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function showDennyForm($id_leave_request)
    {
        return $this->buildDennyForm(LeaveRequest::findOrFail($id_leave_request))->render();
    }

    /**
     * @param $id_leave_request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function denny($id_leave_request)
    {

        /**
         * @var LeaveRequest $leaveRequest
         */
        $leaveRequest = LeaveRequest::findOrFail($id_leave_request);

        try{
            $service = new LeaveRequestService($leaveRequest);
            $service->denny(request('reason',''));
        }
        catch (ValidationException $e){
            return redirect(route('showDennyLeaveRequestForm', $leaveRequest->getKey()))->withErrors($e->validator->getMessageBag());
        }

        return $this->redirectInfo($this->getSuccessRedirectUrl(), 'Szabadság elutasítva');

    }


    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        // TODO: Implement buildFormHelper() method.
    }


}