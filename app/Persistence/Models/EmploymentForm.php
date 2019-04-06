<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 12:53
 */

namespace App\Persistence\Models;


use App\Traits\ValidatableModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmploymentForm
 * @package App\Persistence\Models
 *
 * @property int id_employment_form
 * @property string name
 * @property Employee[] employees
 */
class EmploymentForm extends Model implements ValidatableModelInterface
{
    use ValidatableModel;

    protected $primaryKey = 'id_employment_form';

    protected $fillable = ['name'];

    public function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function employees(){
        return $this->hasMany(Employee::class,'id_employment_form','id_employment_form');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAlternativeEmploymentOptions()
    {
        return $this->query('id_employment_form', 'name')
            ->whereKeyNot($this->getKey())
            ->pluck('name', 'id_employment_form');
    }

}