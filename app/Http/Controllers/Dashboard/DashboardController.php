<?php

namespace App\Http\Controllers\Dashboard;


use App\Http\Components\Providers\FullCalendarProvider;
use App\Http\Controllers\Controller;
use App\Persistence\Models\Employee;
use App\Persistence\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(){

        return view('dashboard.dashboard',[
            'employeCount' => $this->countEmployees(),
            'pendingCount' => $this->countPendingLeaveRequest(),
        ]);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function getFullCalendarEvents(Request $request){
        $start = $request->get('start',Carbon::parse('first day of this month'));
        $end = $request->get('end',Carbon::parse('last day of this month'));

        $provider = new FullCalendarProvider($start,$end);
        return $provider->provide();
    }

    /**
     * @return int
     */
    protected function countEmployees(){
        return Employee::query()
            ->select('id_employee')
            ->where('active','=',1)
            ->whereNull('termination_date')
            ->count();
    }

    /**
     * @return int
     */
    protected function countPendingLeaveRequest(){
        return LeaveRequest::query()
            ->select('id_leave_request')
            ->where('status','=',LeaveRequest::STATUS_PENDING)
            ->count();
    }

}