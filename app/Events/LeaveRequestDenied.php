<?php

namespace App\Events;

use App\Persistence\Models\Employee;
use App\Persistence\Models\LeaveRequest;
use Illuminate\Broadcasting\PrivateChannel;

class LeaveRequestDenied extends LeaveRequestEvent
{
    /**
     * Create a new event instance.
     *
     * @param LeaveRequest $leaveRequest
     * @param Employee $perimeter
     */
    public function __construct(LeaveRequest $leaveRequest, Employee $perimeter)
    {
        parent::__construct( $leaveRequest, $perimeter);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
