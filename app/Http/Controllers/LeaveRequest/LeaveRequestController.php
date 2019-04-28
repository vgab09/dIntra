<?php

namespace App\Http\Controllers\LeaveRequest;


use App\Http\Components\Alert\Alert;
use App\Http\Components\FormHelper\FormButtonFieldHelper;
use App\Http\Components\FormHelper\FormCustomViewFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Components\ToolbarLink\ToolbarLinks;
use App\Http\Controllers\BREADController;
use App\Persistence\Services\LeaveRequestService;
use App\Persistence\Models\Employee;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveType;
use Exception;
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
     * Save resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     * @throws \Throwable
     */
    public function insert()
    {
        $leaveType = LeaveType::findOrFail(intval(request('id_leave_type')));
        $dateRange = request('date_range', false);
        $comment = htmlspecialchars(request('comment'));
        $id_user = request('id_user', false);
        $range = [];

        if (empty($dateRange)) {
            return $this->redirectError(url()->previous(), 'Az időtartalm kitöltése kötelező');
        }

        preg_match('/^(?P<start>([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])))\s(-|to)\s(?P<end>([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])))$/is', $dateRange, $range);

        if (empty($range['start']) || empty($range['end'])) {
            if (empty($dateRange)) {
                return $this->redirectError(url()->previous(), 'Nem megfelelő időtartam formátum. Helyes formátum: ÉÉÉÉ-HH-NN - ÉÉÉÉ-HH-NN');
            }
        }

        try {
            $start_at = new Carbon($range['start']);
            $end_at = new Carbon($range['end']);
            unset($range);
        } catch (\InvalidArgumentException $e) {
            return $this->redirectError(url()->previous(), 'Nem megfelelő időtartam formátum. A megadott dátumok nem értelmezhetőek. Helyes formátum: ÉÉÉÉ-HH-NN - ÉÉÉÉ-HH-NN');
        }

        /**
         * @var Employee $user
         */
        $user = $id_user ? Employee::findOrFail($id_user) : Auth::user();
        unset($id_user);

        $service = new LeaveRequestService();

        try {
            $service->setUser($user)
                ->setLeaveType($leaveType)
                ->setDuration($start_at, $end_at)
                ->setComment($comment)
                ->create();
        } catch (ValidationException $e) {
            return redirect(url()->previous())->withErrors($e->validator->getMessageBag());
        } catch (Exception $e) {
            return redirect(url()->previous())->withErrors($e->getMessage());
        }
        return $this->redirectSuccess(route('dashboard'), 'Sikeres szabadság igénylés');
    }


    /**
     * @param $id_leave_request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id_leave_request)
    {

        /**
         * @var LeaveRequest $leaveRequest
         */
        $leaveRequest = LeaveRequest::findOrFail($id_leave_request);

        /**
         * @var Employee $user
         */
        $user = Auth::user();
        $history = $user->can('show_leave_request_history') ? $leaveRequest->history : null;

        $toolbar = new ToolbarLinks();

        switch ($leaveRequest->status) {
            case LeaveRequest::STATUS_ACCEPTED:
            case LeaveRequest::STATUS_DENIED:
                $toolbar->addLinkIfCan(['accept_leave_request', 'denny_leave_request'], route('setPendingLeaveRequest', $leaveRequest->getKey()), '<i class="fas fa-undo-alt"></i> Visszavonás');
                break;
            default:
                $toolbar->addLinkIfCan('accept_leave_request', route('acceptLeaveRequest', $leaveRequest->getKey()), '<i class="far fa-check-circle"></i> Elfogadás');
                $toolbar->addLinkIfCan('denny_leave_request', route('showDennyLeaveRequestForm', $leaveRequest->getKey()), '<i class="far fa-times-circle"></i> Elutasítás');
                break;
        }

        return view('leaves.show', ['leaveRequest' => $leaveRequest, 'history' => $history, 'toolbar' => $toolbar]);
    }

    /**
     * (B)READ Browse data
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function indexPending(\Illuminate\Http\Request $request)
    {
        $list = $this->buildListHelper();
        $list->setTitle('Függő szabadság igények');
        $list->addRowActions(function ($model) {
            return FormDropDownFieldHelper::to('action')
                ->addActionLinkIfCan('accept_leave_request', route('acceptLeaveRequest', $model->getKey()), '<i class="far fa-check-circle"></i> Elfogadás')
                ->addActionLinkIfCan('list_leave_request', route('showLeaveRequest', $model->getKey()), '<i class="far fa-eye"></i> Megtekintés')
                ->addActionLinkIfCan('denny_leave_request', route('showDennyLeaveRequestForm', $model->getKey()), '<i class="far fa-times-circle"></i> Elutasítás')
                ->renderTag();
        });
        if ($request->ajax()) {
            return $list->createDataTables($this->collectListData()->where('leave_requests.status', '=', LeaveRequest::STATUS_PENDING))->make(true);
        }

        return $list->render();
    }

    public function index(\Illuminate\Http\Request $request)
    {

        $list = $this->buildListHelper();
        $list->addField(
            ListFieldHelper::to('status', 'Állapot')
                ->setSearchTypeSelect(LeaveRequest::getStatuses()
                    ->prepend('-', '')
                )
        );
        $list->addRowActions(function ($model) {
            $field = FormDropDownFieldHelper::to('action');
            $field->addActionLinkIfCan('list_leave_request', route('showLeaveRequest', $model->getKey()), '<i class="far fa-eye"></i> Megtekintés');

            switch ($model->status) {
                case LeaveRequest::STATUS_ACCEPTED:
                case LeaveRequest::STATUS_DENIED:
                    $field->addActionLinkIfCan(['accept_leave_request', 'denny_leave_request'], route('setPendingLeaveRequest', $model->getKey()), '<i class="fas fa-undo-alt"></i> Visszavonás');
                    break;
                case LeaveRequest::STATUS_PENDING:
                    $field->addActionLinkIfCan('accept_leave_request', route('acceptLeaveRequest', $model->getKey()), '<i class="far fa-check-circle"></i> Elfogadás');
                    $field->addActionLinkIfCan('denny_leave_request', route('showDennyLeaveRequestForm', $model->getKey()), '<i class="far fa-times-circle"></i> Elutasítás');
                    break;
            }
            return $field->renderTag();
        });

        if ($request->ajax()) {
            return $list->createDataTables($this->collectListData())->make(true);
        }

        return $list->render();

    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {

        return ListHelper::to('LeaveRequest', [
            ListFieldHelper::to('id_leave_request', '#'),
            ListFieldHelper::to('employee.name', 'Munkatárs'),
            ListFieldHelper::to('leaveType.name', 'Típus')->setSearchTypeSelect(LeaveType::getLeaveTypeOptions()->prepend('-', '')),
            ListFieldHelper::to('start_at', 'Kezdet')->setType('date'),
            ListFieldHelper::to('end_at', 'Vége')->setType('date'),
            ListFieldHelper::to('days', 'Napok száma'),
            ListFieldHelper::to('comment', 'Megjegyzés')->setMaxLength('60'),
        ])
            ->addTimeStamps()
            ->setTitle('Szabadság igények')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('list_leave_request', route('showLeaveRequest', $model->getKey()), '<i class="far fa-eye"></i> Megtekintés')
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
        return LeaveRequest::with('leaveType', 'employee');
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

        try {
            $service = new LeaveRequestService($leaveRequest);
            $service->accept();
        } catch (ValidationException $e) {
            return redirect(url()->previous())->withErrors($e->validator->getMessageBag());
        }
        return redirect(url()->previous())->with(Alert::SUCCESS, 'Szabadság elfogadva');

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

        try {
            $service = new LeaveRequestService($leaveRequest);
            $service->denny(request('reason', ''));
        } catch (ValidationException $e) {
            return redirect(url()->previous())->withErrors($e->validator->getMessageBag());
        }

        return $this->redirectInfo($this->getSuccessRedirectUrl(), 'Szabadság elutasítva');

    }

    public function setPending($id_leave_request){
        /**
         * @var LeaveRequest $leaveRequest
         */
        $leaveRequest = LeaveRequest::findOrFail($id_leave_request);

        try {
            $service = new LeaveRequestService($leaveRequest);
            $service->setPending();
        } catch (ValidationException $e) {
            return redirect(url()->previous())->withErrors($e->validator->getMessageBag());
        }
        return redirect(url()->previous())->with(Alert::SUCCESS, 'Szabadság döntésre vár');
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