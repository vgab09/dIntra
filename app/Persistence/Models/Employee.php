<?php

namespace App\Persistence\Models;

use App\Traits\ValidatableModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
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
 * @property Collection leaveTypes
 * @property Collection leavePolicies
 *
 */
class Employee extends Authenticatable implements ValidatableModelInterface
{
    use Notifiable, HasRoles, ValidatableModel;

    protected $primaryKey = 'id_employee';

    private $assignedDaysLoaded = false;
    private $usedDaysLoaded = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_designation', 'id_department', 'id_employment_form', 'hiring_date', 'termination_date', 'name', 'email', 'password', 'date_of_birth', 'reporting_to_id_employee', 'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getValidationRules(): array
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
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLeaveTypes()
    {
        $this->leaveTypes = LeaveType::query()
            ->select(DB::raw('leave_types.*'))
            ->distinct()
            ->join('leave_policies as lp', 'leave_types.id_leave_type', '=', 'lp.id_leave_type')
            ->join('employee_has_leave_policies as ehlp', 'lp.id_leave_policy', '=', 'ehlp.id_leave_policy')
            ->join('employees as e', 'ehlp.id_employee', '=', 'e.id_employee')->where('e.id_employee', '=', $this->getKey())
            ->get();

        return $this->leaveTypes->keyBy('id_leave_type');
    }

    public function getAssignedLeaveDaysCount()
    {
        if (empty($this->leaveTypes)) {
            $this->getLeaveTypes();
        }

        $this->leaveTypes->map(function (LeaveType $leaveType) {
            $leaveType->load(['leavePolicies' => function ($query) {
                $query
                    ->select('leave_policies.*')
                    ->join('employee_has_leave_policies as ehlp', 'leave_policies.id_leave_policy', '=', 'ehlp.id_leave_policy')
                    ->join('employees as e', 'ehlp.id_employee', '=', 'e.id_employee')
                    ->where('e.id_employee', '=', $this->getKey())
                    ->where('leave_policies.active', '=', 1);
            }]);

            return $leaveType;
        });

        $this->leaveTypes->map(function (LeaveType $leaveType) {
            $leaveType->assigned = $leaveType->leavePolicies->sum('days');
            return $leaveType;
        });

        $this->assignedDaysLoaded = true;

        return $this->leaveTypes;
    }

    public function getUsedLeaveDaysCount()
    {

        if (empty($this->leaveTypes)) {
            $this->getLeaveTypes();
        }

        if(!$this->assignedDaysLoaded){
            $this->getAssignedLeaveDaysCount();
        }

        $this->leaveTypes->map(function (LeaveType $leaveType) {
            $leaveType->load(['leaveRequests' => function ($query) {
                $query
                    ->select('leave_requests.*')
                    ->where('leave_requests.id_employee', '=', $this->getKey())
                    ->where('leave_requests.status', '<>', LeaveRequest::STATUS_DENIED);
            }]);
            $leaveType->used = $leaveType->leaveRequests->sum('days');
            return $leaveType;
        });
        $this->usedDaysLoaded = true;
        return $this->leaveTypes;
    }

    public function getAvailableLeaveDaysCount()
    {
        if (empty($this->leaveTypes)) {
            $this->getLeaveTypes();
        }

        if(!$this->usedDaysLoaded){
            $this->getUsedLeaveDaysCount();
        }

        $this->leaveTypes->map(function (LeaveType $leaveType){
           $leaveType->available = $leaveType->assigned - $leaveType->used;
           return $leaveType;
        });


        return $this->leaveTypes;
    }

    /**
     * Hash the employee's password if is it necessary.
     *
     * @param  string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department', Employee::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'id_designation', 'id_designation', Employee::class);
    }

    public function employmentForm()
    {
        return $this->belongsTo(EmploymentForm::class, 'id_employment_form', 'id_employment_form', Employee::class);
    }

    public function leavePolicies()
    {
        return $this->belongsToMany(LeavePolicy::class, 'employee_has_leave_policies', 'id_employee', 'id_leave_policy', null, null, Employee::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'id_employee', 'id_employee');
    }

    public function leaveRequestHistories()
    {
        return $this->hasMany(LeaveRequestHistory::class, 'id_employee', 'id_employee');
    }


}
