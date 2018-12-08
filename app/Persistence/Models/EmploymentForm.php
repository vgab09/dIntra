<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:53
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class EmploymentForm
 * @package App\Persistence\Models
 *
 * @property int id_employment_form
 * @property string name *
 */
class EmploymentForm extends Model
{

    protected $primaryKey = 'id_employment_form';

    protected $fillable = ['name'];

    protected function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function employees(){
        return $this->hasMany(Employee::class,'id_employment_form','id_employment_form');
    }

}