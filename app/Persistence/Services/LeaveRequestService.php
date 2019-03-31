<?php

namespace App\Persistence\Services;


use App\Events\LeaveRequestAccepted;
use App\Events\LeaveRequestDenied;
use App\Events\NewLeaveRequest;
use App\Persistence\Models\Employee;
use App\Persistence\Models\LeavePolicy;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveRequestHistory;
use App\Persistence\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LeaveRequestService
{

    /**
     * @var LeaveRequest
     */
    private $leaveRequest;

    /**
     * @var LeaveRequestHistory
     */
    private $leaveRequestHistory;

    /**
     * @var Employee
     */
    protected $user;

    /**
     * LeaveRequestService constructor.
     * @param LeaveRequest|null $instance
     */
    public function __construct(?LeaveRequest $instance = null)
    {
        $this->setLeaveRequest($instance);
        $user = Auth::user();

        if($user !== null){
            $this->setUser($user);
        }

    }

    /**
     * @param Employee $employee
     * @return LeaveRequestService
     */
    public function setEmployee(Employee $employee): LeaveRequestService
    {
        $this->leaveRequest->employee()->associate($employee);
        return $this;
    }

    /**
     * @param Carbon $startAt
     * @param Carbon $endAt
     * @return LeaveRequestService
     */
    public function setDuration(Carbon $startAt, Carbon $endAt): LeaveRequestService
    {
        $this->leaveRequest->start_at = $startAt;
        $this->leaveRequest->end_at = $endAt;
        $this->setDays($startAt->diffInDays($endAt));
        return $this;
    }

    /**
     * @param int $days
     * @return LeaveRequestService
     */
    public function setDays(int $days): LeaveRequestService
    {
        $this->leaveRequest->days = $days;
        return $this;
    }

    /**
     * @param LeaveType $leaveType
     * @return LeaveRequestService
     */
    public function setLeaveType(LeaveType $leaveType): LeaveRequestService
    {
        $this->leaveRequest->leaveType()->associate($leaveType);
        return $this;
    }

    /**
     * @param LeaveRequest|null $leaveRequest set null to create new LeaveRequest Instance
     * @return LeaveRequestService
     */
    public function setLeaveRequest(?LeaveRequest $leaveRequest = null): LeaveRequestService
    {
        $this->leaveRequest = ($leaveRequest === null) ?  new LeaveRequest() : $leaveRequest;
        return $this;
    }

    /**
     * @param LeaveRequestHistory $leaveRequestHistory
     * @return LeaveRequestService
     */
    public function setLeaveRequestHistory(LeaveRequestHistory $leaveRequestHistory): LeaveRequestService
    {
        $this->leaveRequestHistory = $leaveRequestHistory;
        return $this;
    }

    /**
     * @param string $comment
     * @return LeaveRequestService
     */
    public function setComment(string $comment): LeaveRequestService
    {
        $this->leaveRequest->comment = strip_tags($comment);
        return $this;
    }

    public function setStatus($status){
        $this->leaveRequest->status = $status;
    }

    /**
     * Create new leave request and save
     * @return LeaveRequest
     * @throws \Throwable|ValidationException
     */
    public function create()
    {
        $this->setStatus(LeaveRequest::STATUS_PENDING);
        $this->leaveRequest->validate();
        $this->leaveRequest->saveOrFail();
        event(new NewLeaveRequest($this->leaveRequest, $this->user));

        return $this->leaveRequest;
    }


    /**
     * Accept and save leave request
     * @return LeaveRequest
     * @throws \Throwable|ValidationException
     */
    public function accept()
    {
        $this->setStatus(LeaveRequest::STATUS_ACCEPTED);
        $this->leaveRequest->validate();
        $this->leaveRequest->saveOrFail();

        event(new LeaveRequestAccepted($this->leaveRequest, $this->user));

        $this->leaveRequest->load('history');

        return $this->leaveRequest;
    }

    /**
     * @param string $reason
     * @return LeaveRequest
     * @throws ValidationException
     * @throws \Throwable
     */
    public function denny($reason)
    {

        $this->setStatus(LeaveRequest::STATUS_DENIED);
        $this->leaveRequest->reason = strip_tags($reason);
        $this->leaveRequest->validate();
        $this->leaveRequest->saveOrFail();

        event(new LeaveRequestDenied($this->leaveRequest, $this->user));

        $this->leaveRequest->load('history');

        return $this->leaveRequest;
    }

    /**
     * @param Employee $user
     */
    public function setUser(Employee $user)
    {
        $this->user = $user;
    }


}