<?php

namespace App\Persistence\Models;


use App\Traits\ValidatableModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Holiday
 * @package App\Persistence\Models
 *
 * @property int id_holiday
 * @property string name
 * @property string start
 * @property string end
 * @property string description
 *
 */
class Holiday extends Model implements ValidatableModelInterface
{
    use ValidatableModel;

    protected $primaryKey = 'id_holiday';

    protected $fillable = ['name','start','end','description'];

    protected function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'description' => 'nullable|string'
        ];
    }


}