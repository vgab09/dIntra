<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:53
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

class EmploymentForm extends Model
{

    protected $primaryKey = 'id_employment_form';

    protected $fillable = 'name';

    public function employees(){
        return $this->belongsTo(Employee::class,'id_employment_form','id_employment_form',EmploymentForm::class);
    }

}