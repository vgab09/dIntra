<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:44
 */

namespace App\Persistence\Models;


use App\Traits\ValidatableModel;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Designation
 * @package App\Persistence\Modelsű
 *
 * @property int id_department
 * @property string name
 * @property int id_leader
 * @property int id_parent
 * @property bool active
 * @property string description
 */
class Department extends Model
{

    use ValidatableModel;

    protected $primaryKey = 'id_department';

    protected $fillable = ['name','id_leader','id_parent','active','description'];

    protected function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'id_leader' => 'nullable|int|exists:employees',
            'id_parent' => 'nullable|int|exists:departments',
            'active' => 'required|boolean',
            'description' => 'nullable|string'
        ];
    }


    public function employees(){
        return $this->hasMany(Employee::class,'id_department','id_department');
    }

}