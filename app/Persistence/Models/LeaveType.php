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
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LeaveType
 * @package App\Persistence\Models
 *
 * @property int id_leave_type
 * @property string start_at
 * @property string end_at
 * @property string name
 * @property int created_at
 * @property int updated_at
 * @property int deleted_at
 * @property \Illuminate\Support\Collection leavePolicies
 * @property \Illuminate\Support\Collection leaveRequests
 */
class LeaveType extends Model implements ValidatableModelInterface
{
    use ValidatableModel, SoftDeletes;

    protected $primaryKey = 'id_leave_type';

    protected $fillable = ['name','start_at','end_at'];

    public function getValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leavePolicies(){
        return $this->hasMany(LeavePolicy::class,'id_leave_type','id_leave_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
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

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getLeaveTypeOptions(){
        return static::query()
            ->select('id_leave_type', 'name')
            ->pluck('name', 'name');
    }

}