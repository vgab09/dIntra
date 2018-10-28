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

}