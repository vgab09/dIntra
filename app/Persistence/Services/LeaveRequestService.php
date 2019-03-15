<?php

namespace App\Persistence\Services;


use App\Events\LeaveRequestAccepted;
use App\Events\LeaveRequestDenied;
use App\Persistence\Models\Employee;
use App\Persistence\Models\LeavePolicy;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveRequestHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LeaveRequestService
{

    /**
     * @var Employee
     */
    private $employee;

    /**
     * @var Employee
     */
    private $user;

    /**
     * @var Carbon
     */
    private $startAt;

    /**
     * @var Carbon
     */
    private $endAt;

    /**
     * @var LeavePolicy
     */
    private $leavePolicy;

    /**
     * @var LeaveRequest
     */
    private $leaveRequest;

    /**
     * @var LeaveRequestHistory
     */
    private $leaveRequestHistory;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var int
     */
    private $days;

    public function __construct()
    {
        $this->setLeaveRequest(new LeaveRequest());
        $this->setUser(Auth::user());
    }

    /**
     * @param Employee $employee
     * @return LeaveRequestService
     */
    public function setEmployee(Employee $employee): LeaveRequestService
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * @param Employee $user
     * @return LeaveRequestService
     */
    public function setUser(Employee $user): LeaveRequestService
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Carbon $startAt
     * @param Carbon $endAt
     * @return LeaveRequestService
     */
    public function setDuration(Carbon $startAt, Carbon $endAt): LeaveRequestService
    {
        $this->startAt = $startAt;
        $this->endAt = $startAt;
        return $this;
    }

    /**
     * @param int $days
     * @return LeaveRequestService
     */
    public function setDays(int $days): LeaveRequestService
    {
        $this->days = $days;
        return $this;
    }

    /**
     * @param LeavePolicy $leavePolicy
     * @return LeaveRequestService
     */
    public function setLeavePolicy(LeavePolicy $leavePolicy): LeaveRequestService
    {
        $this->leavePolicy = $leavePolicy;
        return $this;
    }

    /**
     * @param LeaveRequest $leaveRequest
     * @return LeaveRequestService
     */
    public function setLeaveRequest(LeaveRequest $leaveRequest): LeaveRequestService
    {
        $this->leaveRequest = $leaveRequest;
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
        $this->comment = strip_tags($comment);
        return $this;
    }

    /**
     * @return LeaveRequest
     */
    public function build()
    {
        $this->leaveRequest->id_employee = $this->employee->id_employee;
        $this->leaveRequest->id_leave_policy = $this->leavePolicy->id_leave_policy;
        $this->leaveRequest->start_at = $this->startAt->toDateString();
        $this->leaveRequest->end_at = $this->startAt->toDateString();
        $this->leaveRequest->days = $this->days;
        $this->leaveRequest->comment = $this->comment;
        $this->leaveRequest->status = LeaveRequest::STATUS_PENDING;
        return $this->leaveRequest;
    }

    /**
     * Create new leave request and save
     * @return LeaveRequest
     * @throws \Throwable|ValidationException
     */
    public function createNew()
    {
        $this->build();
        $this->leaveRequest->validate();
        $this->leaveRequest->saveOrFail();

        return $this->leaveRequest;
    }


    /**
     * Accept and save leave request
     * @return LeaveRequest
     * @throws \Throwable|ValidationException
     */
    public function accept()
    {
        $this->leaveRequest->status = LeaveRequest::STATUS_ACCEPTED;
        $this->leaveRequest->validate();
        $this->leaveRequest->saveOrFail();

        event(new LeaveRequestAccepted($this->leaveRequest, $this->user));

        $this->leaveRequest->load('history');

        return $this->leaveRequest;
    }

    /**
     * Denny and save leave request
     * @return LeaveRequest
     * @throws \Throwable|ValidationException
     */
    public function denny()
    {

        $this->leaveRequest->status = LeaveRequest::STATUS_DENIED;
        $this->leaveRequest->validate();
        $this->leaveRequest->saveOrFail();

        event(new LeaveRequestDenied($this->leaveRequest, $this->user));

        $this->leaveRequest->load('history');

        return $this->leaveRequest;
    }


}