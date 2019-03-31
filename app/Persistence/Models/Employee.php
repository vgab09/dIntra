<?php

namespace App\Persistence\Models;

use App\Traits\ValidatableModel;
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
        return LeaveType::query()
            ->select(DB::raw('leave_types.*'))
            ->distinct()
            ->join('leave_policies as lp', 'leave_types.id_leave_type', '=', 'lp.id_leave_type')
            ->join('employee_has_leave_policies as ehlp', 'lp.id_leave_policy', '=', 'ehlp.id_leave_policy')
            ->join('employees as e', 'ehlp.id_employee', '=', 'e.id_employee')->where('e.id_employee', '=', $this->getKey())
            ->get();
    }

    public function getAssignedLeaveDaysCount()
    {
        return LeaveType::query()
            ->selectRaw('leave_policies.id_leave_type,leave_types.name,SUM(leave_policies.days) as assigned')
            ->join('leave_policies', 'leave_policies.id_leave_type', '=', 'leave_types.id_leave_type')
            ->join('employee_has_leave_policies', 'employee_has_leave_policies.id_leave_policy', '=', 'leave_policies.id_leave_policy')
            ->where('employee_has_leave_policies.id_employee', '=', $this->getKey())
            ->whereNull('leave_policies.deleted_at')
            ->groupBy('leave_policies.id_leave_type', 'leave_types.name')
            ->get();
    }

    public function getUsedLeaveDaysCount()
    {
        return LeaveType::query()
            ->selectRaw('leave_requests.id_leave_type,leave_types.name,SUM(leave_requests.days) as used')
            ->join('leave_requests', 'leave_requests.id_leave_type', '=', 'leave_types.id_leave_type')
            ->where('leave_requests.id_employee', '=', $this->getKey())
            ->where('leave_requests.status', '<>', LeaveRequest::STATUS_DENIED)
            ->groupBy('leave_requests.id_leave_type', 'leave_types.name')
            ->get();

    }

    public function getAvailableLeaveDaysCount(){

        $assignedPolicies = $this->getAssignedLeaveDaysCount()->keyBy('id_leave_type');
        $usedRequests = $this->getUsedLeaveDaysCount()->keyBy('id_leave_type');

        foreach ($assignedPolicies as $id_leave_type => &$assigned){

            if($usedRequests->has($id_leave_type)){

                $assigned->available = $assigned->assigned - $usedRequests->get($id_leave_type)->used;
            }
            else{
                $assigned->available = $assigned->assigned;
            }
        }

        return $assignedPolicies;
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
