<?php

namespace App\Listeners;

use App\Events\LeaveRequestEvent;
use App\Events\NewLeaveRequest;
use App\Persistence\Models\LeaveRequestHistory;

class AddAcceptedLeaveHistoryEntry
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
        $history->event = sprintf('%s elfogadta a szabadság kérelmet',$event->creator->name);
        $history->created_by = $event->creator->getKey();
        $history->save();
    }
}
