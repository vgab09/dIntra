<?php

namespace App\Persistence\Services;

use App\Http\Components\Calendar\Event;
use App\Persistence\Models\Employee;
use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\Workday;

class LeaveCalendarService
{

    public function __construct()
    {

    }

    /**
     * @param \DateTimeInterface|string $start date
     * @param \DateTimeInterface|string  date
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getHolidays($start, $end)
    {
        return Holiday::query()
            ->whereDate('start','>=',$start)
            ->whereDate('end','<=',$end)
            ->get();
    }

    /**
     * @param  \DateTimeInterface|string $start
     * @param  \DateTimeInterface|string $end
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getWorkDays($start,$end)
    {
        return Workday::query()
            ->whereDate('start','>=',$start)
            ->whereDate('end','<=',$end)
            ->get();
    }

    /**
     * @param  \DateTimeInterface|string $start
     * @param  \DateTimeInterface|string $end
     * @param int|Employee|null $user
     * @return LeaveRequest[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getLeaveRequests($start,$end,$user = null){
        $leaveRequest =  LeaveRequest::with(['employee','leaveType'])
            ->where('status','<>',LeaveRequest::STATUS_DENIED)
            ->whereDate('start_at','>=',$start)
            ->whereDate('end_at','<=',$end);

        if(isset($user)){
            $leaveRequest->where('id_employee','=',is_int($user) ? $user : $user->getKey());
        }

        return $leaveRequest->get();

    }

}