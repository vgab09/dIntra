<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 13:13
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class Leave_policies extends Model
{
    protected $primaryKey = 'id_leave_policy';

    protected $fillable = ['id_leave_type','name','day','color','description','start_at','end_at','active'];





}