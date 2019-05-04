<?php

namespace App\Http\Components\Providers;


use App\Http\Components\Calendar\Event;
use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\Workday;
use App\Persistence\Repositories\LeaveCalendarRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FullCalendarProvider implements ProviderInterface
{
    private $events;
    protected $leaveCalendarRepository;

    /**
     * @var Carbon $start
     */
    protected $start;

    /**
     * @var Carbon $end
     */
    protected $end;

    /**
     * FullCalendarProvider constructor.
     * @param \DateTimeInterface|string $start
     * @param \DateTimeInterface|string $end
     */
    public function __construct($start,$end)
    {
        $this->leaveCalendarRepository = new LeaveCalendarRepository();
        $this->setStart($start);
        $this->setEnd($end);
    }

    /**
     * @return Carbon
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTimeInterface|string $start
     * @return FullCalendarProvider
     */
    public function setStart($start)
    {
        $this->start = Carbon::parse($start);
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTimeInterface|string $end
     * @return FullCalendarProvider
     */
    public function setEnd($end)
    {
        $this->end = Carbon::parse($end);
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getHolidays()
    {
        return $this->leaveCalendarRepository->getHolidays($this->getStart(),$this->getEnd());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getWorkDays()
    {
        return $this->leaveCalendarRepository->getWorkDays($this->getStart(),$this->getEnd());
    }

    /**
     * @return LeaveRequest[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getLeaveRequests(){
        return $this->leaveCalendarRepository->getLeaveRequests($this->getStart(),$this->getEnd());
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