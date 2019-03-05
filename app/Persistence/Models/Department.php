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
class Department extends Model implements ValidatableModelInterface
{

    use ValidatableModel;

    protected $primaryKey = 'id_department';

    protected $fillable = ['name','id_leader','id_parent','active','description'];

    protected function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'id_leader' => 'nullable|int|exists:employees,id_employee',
            'id_parent' => 'nullable|int|exists:departments,id_department',
            'active' => 'required|boolean',
            'description' => 'nullable|string'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAlternativeDepartmentOptions()
    {
        return Department::query('id_department', 'name')
            ->whereKeyNot($this->getKey())
            ->where('active','=','1')
            ->pluck('name', 'id_department')
            ->prepend('-','');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getDepartmentOptions(){
        return Department::query('id_department', 'name')
            ->pluck('name', 'name')
            ->prepend('-','');
    }

    public function employees(){
        return $this->hasMany(Employee::class,'id_department','id_department');
    }

    public function leader(){
        return $this->hasOne(Employee::class,'id_employee','id_leader');
    }

    public function parent() {
        return $this->belongsTo(static::class, 'id_department','id_parent',Department::class);
    }

    public function children() {
        return $this->hasMany(static::class, 'id_parent','id_department');
    }

}