<?php

namespace App\Persistence\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_employee';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_designation','id_department','id_employment_form','hiring_date','termination_date','name', 'email', 'password','date_of_birth','reporting_to_id_employee','active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function department(){
        return $this->hasOne(Department::class,'id_department','id_department');
    }

    public function designation(){
        return $this->hasOne(Designation::class,'id_designation','id_designation');
    }

    public function employmentForm(){
        return $this->hasOne(EmploymentForm::class,'id_employment_form','id_employment_form');
    }

    public function leavePolicies(){
        return $this->belongsToMany(LeavePolicy::class,'employee_has_leave_policies','id_employee','id_leave_policy',null,null,Employee::class);
    }

    public function leaveRequests(){
        return $this->hasMany(LeaveRequest::class,'id_employee','id_employee');
    }

    public function leaveRequestHistories(){
        return $this->hasMany(LeaveRequestHistory::class,'id_employee','id_employee');
    }



}
