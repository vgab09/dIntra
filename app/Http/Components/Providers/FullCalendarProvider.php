<?php

namespace App\Http\Components\Providers;


use App\Http\Components\Calendar\Event;
use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\Workday;

class FullCalendarProvider implements ProviderInterface
{
    private $events;

    public function getHolidays()
    {
        return Holiday::all();
    }

    public function getWorkDays()
    {
        return Workday::all();
    }

    public function getLeaveRequests(){
        return LeaveRequest::with(['employee','leaveType'])->where('status','<>',LeaveRequest::STATUS_DENIED)->get();
    }

    /**
     * @return Event[]
     */
    public function collectEvents(){
        foreach ($this->getHolidays() as $holiday){
            $this->events[] = Event::fromHoliday($holiday);
        }

        foreach ($this->getWorkDays() as $workDay){
            $this->events[] = Event::fromWorkDay($workDay);
        }

        foreach ($this->getLeaveRequests() as $leaveRequest){
            $this->events[] = Event::fromLeaveRequest($leaveRequest);
        }

        return $this->events;
    }


    /**
     * @return false|string
     */
    public function provide()
    {
        if(empty($this->events)){
            $this->collectEvents();
        }

        return json_encode($this->events);
    }
}