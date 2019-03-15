<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 13:37
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class LeaveRequestHistory
 * @package App\Persistence\Models
 *
 * @property int id_leave_request_history
 * @property int id_leave_request
 * @property string event
 * @property int created_by
 * @property string created_at
 * @property string updated_at
 */
class LeaveRequestHistory extends Model
{
    protected $primaryKey = 'id_leave_request_history';

    protected $fillable = ['id_leave_request','event','created_by'];

    protected $table = 'leave_request_history';

    public function getValidationRules(): array
    {
        return [
            'id_leave_request' => 'required|int|exists:leave_requests',
            'event' => 'nullable|string',
            'created_by' => 'required|int|exists:employees',
        ];
    }

    public function leaveRequest(){
        return $this->hasOne(LeaveRequest::class,'id_leave_request','id_leave_request',LeaveRequestHistory::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'id_employee','id_employee');
    }

}