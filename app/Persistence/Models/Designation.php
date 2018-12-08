<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:51
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Designation
 * @package App\Persistence\Models
 *
 * @property int id_designation
 * @property string name
 * @property string description
 * @property bool active
 */
class Designation extends Model
{

    protected $primaryKey = 'id_designation';

    protected $fillable = ['name','description','active'];

    protected function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'active' => 'required|boolean',
            'description' => 'nullable|string'
        ];
    }

    public function employees(){
        return $this->hasMany(Employee::classm,'id_designation','id_designation');
    }

}