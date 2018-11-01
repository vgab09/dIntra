<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 10:09
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $primaryKey = 'id_holiday';

    protected $fillable = ['name','start','end','description'];




}