<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 13:13
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    protected $primaryKey = 'id_leave_policy';

    protected $fillable = ['id_leave_type','name','days','color','description','start_at','end_at','active'];

    public function leaveType(){
        return $this->belongsTo(LeaveType::class,'id_leave_type','id_leave_type',LeavePolicy::class);
    }

    public function leaveRequests(){
        return $this->belongsTo(LeaveRequest::class,'id_leave_policy','id_leave_policy',LeavePolicy::class);
    }

    public function employees(){
        return $this->belongsToMany(Employee::class,'employee_has_leave_policies','id_leave_policy','id_employee',null,null,LeavePolicy::class);
    }

}