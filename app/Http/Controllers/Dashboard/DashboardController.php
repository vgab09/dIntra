<?php

namespace App\Http\Controllers\Dashboard;


use App\Http\Components\Providers\FullCalendarProvider;
use App\Http\Controllers\Controller;
use App\Persistence\Models\Employee;
use App\Persistence\Models\LeaveRequest;

class DashboardController extends Controller
{
    public function dashboard(){

        $provider = new FullCalendarProvider();
        $provider->collectEvents();

        return view('dashboard.dashboard',[
            'employeCount' => $this->countEmployees(),
            'pendingCount' => $this->countPendingLeaveRequest(),
            'fullCalendarProvider' => $provider
        ]);
    }

    protected function countEmployees(){
        return Employee::query()
            ->select('id_employee')
            ->where('active','=',1)
            ->whereNull('termination_date')
            ->count();
    }

    protected function countPendingLeaveRequest(){
        return LeaveRequest::query()
            ->select('id_leave_request')
            ->where('status','=',LeaveRequest::STATUS_PENDING)
            ->count();
    }

}