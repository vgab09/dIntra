<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 10:34
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{

    protected $primaryKey = 'id_leave_type';

    protected $fillable = ['name'];

    public function leavePolicies(){
        return $this->belongsTo(LeavePolicy::class,'id_leave_type','id_leave_type',LeavePolicy::class);
    }

}