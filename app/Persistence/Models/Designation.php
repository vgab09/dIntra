<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:51
 */

namespace App\Persistence\Models;


use App\Traits\ValidatableModel;
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
class Designation extends Model implements ValidatableModelInterface
{
    use ValidatableModel;

    protected $primaryKey = 'id_designation';

    protected $fillable = ['name','description','active'];

    public function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'active' => 'required|boolean',
            'description' => 'nullable|string'
        ];
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAlternativeDesignationOptions()
    {
        return static::query()
            ->select('id_designation', 'name')
            ->whereKeyNot($this->getKey())
            ->where('active','=','1')
            ->pluck('name', 'id_designation');
    }

    public static function getDesignationOptions(){
        return static::query()
            ->select('id_designation', 'name')
            ->pluck('name', 'name');
    }

    public function employees(){
        return $this->hasMany(Employee::class,'id_designation','id_designation');
    }

}