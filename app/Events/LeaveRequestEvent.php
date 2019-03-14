<?php

namespace App\Events;

use App\Persistence\Models\Employee;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveRequestHistory;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
abstract class LeaveRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var LeaveRequest
     */
    public $leaveRequest;

    /**
     * @var Employee
     */
    public $creator;

    /**
     * Create a new event instance.
     *
     * @param LeaveRequest $leaveRequest
     * @param LeaveRequestHistory $history
     * @param Employee $perimeter
     */
    public function __construct(LeaveRequest $leaveRequest,Employee $creator)
    {
        $this->leaveRequest = $leaveRequest;
        $this->creator = $creator;
    }

}