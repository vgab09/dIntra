<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 13:28
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{

    protected $primaryKey = 'id_leave_request';

    protected $fillable = ['id_employee','id_leave_policy','start_date','end_date','days','comment','status','reason'];

    public function leavePolicy(){
        return $this->hasOne(LeavePolicy::class,'id_leave_policy','id_leave_policy');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'id_employee','id_employee',Employee::class);
    }

    public function history(){
        return $this->hasMany(LeaveRequestHistory::class,'id_leave_request','id_leave_request');
    }

}