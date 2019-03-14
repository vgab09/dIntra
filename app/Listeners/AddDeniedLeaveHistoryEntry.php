<?php

namespace App\Listeners;

use App\Events\LeaveRequestEvent;
use App\Events\NewLeaveRequest;
use App\Persistence\Models\LeaveRequestHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddDeniedLeaveHistoryEntry
{
    /**
     * Handle the event.
     *
     * @param  LeaveRequestEvent  $event
     * @return void
     */
    public function handle(LeaveRequestEvent $event)
    {
        $history = new LeaveRequestHistory();
        $history->id_leave_request = $event->leaveRequest->id_leave_request;
        $history->event = sprintf('%s elutasította a szabadság kérelmet. Az eluatsítás oka: %s',$event->creator->name,$event->leaveRequest->reason);
        $history->created_by = $event->creator->getKey();
        $history->save();
    }
}
