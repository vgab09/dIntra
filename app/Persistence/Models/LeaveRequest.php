<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 13:28
 */

namespace App\Persistence\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Class LeaveRequest
 * @package App\Persistence\Models
 *
 * @property int id_leave_request
 * @property int id_employee
 * @property int id_leave_policy
 * @property string start_at
 * @property string end_at
 * @property int days
 * @property string comment
 * @property string status
 * @property string reason
 */
class LeaveRequest extends Model
{

    public const STATUS_PENDING = 'pending';
    public const STATUS_DENIED = 'denied';
    public const STATUS_ACCEPTED = 'accepted';

    protected $primaryKey = 'id_leave_request';

    protected $fillable = ['id_employee','id_leave_policy','start_at','end_at','days','comment','status','reason'];

    protected function getValidationRules(): array
    {
        return [
            'id_employee' => 'required|int|exists:employees',
            'id_leave_policy' => 'required|int|exists:leave_types',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'days' => 'required|int',
            'comment' => 'nullable|string',
            'status' => [
                'required',
                'string',
                Rule::in([self::STATUS_PENDING,self::STATUS_DENIED,self::STATUS_ACCEPTED])
            ],
            'reason' => 'nullable|string',
        ];
    }

    public function accept(){
        $user = Auth::user();
        $this->status = static::STATUS_ACCEPTED;
        $this->save();
        $history = $this->addNewHistoryEntry(sprintf('%s elfogadta a szabadság kérelmet',$user->name));
        event(new LeaveRequestAccepted($this,$history,$user));
    }

    public function denny(){
        $user = Auth::user();
        $this->status = static::STATUS_DENIED;
        $this->save();
        $history = $this->addNewHistoryEntry(sprintf('%s elutasította a szabadság kérelmet',$user->name));
        event(new LeaveRequestAccepted($this,$history,$user));
    }

    /**
     * @param string $event
     * @return LeaveRequestHistory
     */
    protected function addNewHistoryEntry(string $event){
        $history = new LeaveRequestHistory();
        $history->event = $event;
        $this->history()->save($history);

        return $history;
    }

    public function leavePolicy(){
        return $this->belongsTo(LeavePolicy::class,'id_leave_policy','id_leave_policy',LeaveRequest::class);
    }

    public function leaveType(){
        return $this->hasManyThrough(
            LeaveType::class,
            LeavePolicy::class,
            'id_leave_policy',
            'id_leave_type',
            'id_leave_policy',
            'id_leave_type');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'id_employee','id_employee',LeaveRequest::class)->withDefault(['id_employee'=>0,'name'=>'Megszünt felhasználó']);
    }

    public function history(){
        return $this->hasMany(LeaveRequestHistory::class,'id_leave_request','id_leave_request');
    }

}