<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 10:09
 */

namespace App\Persistance\Models;


use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    protected $primaryKey = 'id_work_days';

    protected $fillable = ['name','start','end','description'];




}