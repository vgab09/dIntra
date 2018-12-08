<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 10:09
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkDay
 * @package App\Persistence\Models
 *
 * @property int id_work_day
 * @property string name
 * @property string start
 * @property string end
 * @property string description
 *
 */
class WorkDay extends Model
{
    protected $primaryKey = 'id_work_day';

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