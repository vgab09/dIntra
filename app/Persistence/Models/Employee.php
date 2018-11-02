<?php

namespace App\Persistence\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use Notifiable, HasRoles;

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
        return $this->belongsTo(Department::class,'id_department','id_department',Employee::class);
    }

    public function designation(){
        return $this->belongsTo(Designation::class,'id_designation','id_designation',Employee::class);
    }

    public function employmentForm(){
        return $this->belongsTo(EmploymentForm::class,'id_employment_form','id_employment_form',Employee::class);
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
