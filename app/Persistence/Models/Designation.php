<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:51
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{

    protected $primaryKey = 'id_designation';

    protected $fillable = ['name','description','active'];

}