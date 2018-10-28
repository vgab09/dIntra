<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:44
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    protected $primaryKey = 'id_department';

    protected $fillable = ['name','id_leader','id_parent','active','description'];

}