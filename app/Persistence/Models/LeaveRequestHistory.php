<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 13:37
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class LeaveRequestHistory extends Model
{
    protected $primaryKey = 'id_leave_request_history';

    protected $fillable = ['id_request','event','created_by'];

}