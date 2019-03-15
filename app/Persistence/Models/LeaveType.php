<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 10:34
 */

namespace App\Persistence\Models;


use App\Traits\ValidatableModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LeaveType
 * @package App\Persistence\Models
 *
 * @property int id_leave_type
 * @property string name
 */
class LeaveType extends Model implements ValidatableModelInterface
{
    use ValidatableModel;

    protected $primaryKey = 'id_leave_type';

    protected $fillable = ['name'];

    public function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function leavePolicies(){
        return $this->hasMany(LeavePolicy::class,'id_leave_type','id_leave_type');
    }

    public function leaveRequests(){
        return $this->hasMany(LeaveRequest::class,'id_leave_type','id_leave_type');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAlternativeLeaveTypeOptions()
    {
        return static::query()
            ->select('id_leave_type', 'name')
            ->whereKeyNot($this->getKey())
            ->pluck('name', 'id_leave_type');
    }

    public static function getLeaveTypeOptions(){
        return static::query()
            ->select('id_leave_type', 'name')
            ->pluck('name', 'name');
    }

}