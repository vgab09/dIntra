<?php

namespace App\Persistence\Models;

use App\Traits\ValidatableModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Employee
 * @package App\Persistence\Models
 *
 * @property int id_employee
 * @property int id_designation
 * @property int id_department
 * @property int id_employment_form
 * @property string hiring_date
 * @property string termination_date
 * @property string name
 * @property string email
 * @property string date_of_birth
 * @property int reporting_to_id_employee
 * @property bool active
 *
 */
class Employee extends Authenticatable implements ValidatableModelInterface
{
    use Notifiable, HasRoles, ValidatableModel;

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

    protected function getValidationRules(): array
    {
        return [
            'id_designation' => 'nullable|int|exists:designations',
            'id_department' => 'nullable|int|exists:departments',
            'id_employment_form' => 'required|int|exists:employment_forms',
            'hiring_date' => 'required|date',
            'termination_date' => 'nullable|date',
            'name' => 'required|string',
            'date_of_birth' => 'required|date',
            'reporting_to_id_employee' => 'nullable|int|exists:employees',
            'active' => 'required|boolean',
            'email' => [
                'required',
                'email',
                $this->isUnique('email'),
                'max:127'
            ]
        ];
    }

    /**
     * Hash the employee's password if is it necessary.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

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
