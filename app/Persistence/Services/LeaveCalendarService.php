<?php

namespace App\Persistence\Services;

use App\Http\Components\Calendar\Event;
use App\Persistence\Models\Employee;
use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveType;
use App\Persistence\Models\Workday;
use Carbon\Carbon;

class LeaveCalendarService
{

    /**
     * @param \DateTimeInterface|string $start date
     * @param \DateTimeInterface|string  date
     * @return Holiday[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
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
     * @return Workday[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
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
     * @param int|LeaveType|null $leaveType
     * @return LeaveRequest[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getLeaveRequests($start,$end,$user = null, $leaveType = null){
        $leaveRequest =  LeaveRequest::with(['employee','leaveType'])
            ->where('status','<>',LeaveRequest::STATUS_DENIED)
            ->whereDate('start_at','>=',$start)
            ->whereDate('end_at','<=',$end);

        if(isset($user)){
            $leaveRequest->where('id_employee','=',is_int($user) ? $user : $user->getKey());
        }

        if(isset($leaveType)){
            $leaveRequest->where('id_leave_type','=',is_int($leaveType) ? $leaveType : $leaveType->getKey());
        }

        return $leaveRequest->get();

    }

    /**
     * @param \DateTimeInterface|string $start
     * @param \DateTimeInterface|string $end
     * @return \Illuminate\Support\Collection
     */
    public function getWeekends($start,$end){

        $start = new Carbon($start);
        $end = new Carbon($end);

        $dates = collect();

        for($date = $start; $date->lte($end); $date->addDay()) {

            if($date->isWeekend()){
                $dates->push($date->format('Y-m-d'));
            }
        }

        return $dates;

    }

}