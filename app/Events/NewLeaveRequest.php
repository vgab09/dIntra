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

class NewLeaveRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $leaveRequest;
    protected $history;
    protected $perimeter;

    /**
     * Create a new event instance.
     *
     * @param LeaveRequest $leaveRequest
     * @param LeaveRequestHistory $history
     * @param Employee $perimeter
     */
    public function __construct(LeaveRequest $leaveRequest,LeaveRequestHistory $history, Employee $perimeter)
    {
        $this->leaveRequest = $leaveRequest;
        $this->history = $history;
        $this->perimeter = $perimeter;
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
