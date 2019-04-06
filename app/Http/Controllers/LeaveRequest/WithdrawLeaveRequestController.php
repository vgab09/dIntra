<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019.04.04.
 * Time: 15:57
 */

namespace App\Http\Controllers\LeaveRequest;


use App\Http\Components\Calendar\Event;
use App\Persistence\Models\Employee;
use App\Persistence\Services\LeaveCalendarService;
use App\Persistence\Services\LeaveRequestService;
use App\Traits\AlertMessage;
use Illuminate\Support\Facades\Auth;

class WithdrawLeaveRequestController
{
    use AlertMessage;

    /**
     * @var LeaveCalendarService $LeaveCalendarService
     */
    private $LeaveCalendarService;

    /**
     * @var LeaveRequestService $LeaveRequestService
     */
    private $LeaveRequestService;

    public function __construct()
    {
        $this->LeaveCalendarService = new LeaveCalendarService();
        $this->LeaveRequestService = new LeaveRequestService();
    }

    public function withdraw(){
        /**
         * @var Employee $user
         */
        $user = Auth::user();
        $leaveTypes = $user->getAssignedLeaveDaysCount();
        if($leaveTypes->isEmpty()){
            return $this->redirectError(route('dashboard'),'Nincs szabadság szabály hozzárendelve a felhasználóhoz. Szabadság így nem igényelhető.');
        }

        $start_at = '2018.12.01';
        $end_at = '2020.01.31';

        $holidays = $this->LeaveCalendarService->getHolidays($start_at,$end_at)->mapInto(Event::class);
        $workdays = $this->LeaveCalendarService->getWorkDays($start_at,$end_at)->mapInto(Event::class);
        $leaveRequests = $this->LeaveCalendarService->getLeaveRequests($start_at,$end_at,$user)->mapInto(Event::class);

        return view('leaves.withdraw',
            [
                'leaveTypes' =>$leaveTypes,
                'holidays' =>$holidays,
                'workdays' =>$workdays,
                'leaves' =>$leaveRequests
            ]);
    }

}