<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.10.28.
 * Time: 13:28
 */

namespace App\Persistence\Models;


use App\Events\LeaveRequestAccepted;
use App\Events\LeaveRequestDenied;
use App\Traits\ValidatableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Class LeaveRequest
 * @package App\Persistence\Models
 *
 * @property int id_leave_request
 * @property int id_employee
 * @property int id_leave_type
 * @property string start_at
 * @property string end_at
 * @property int days
 * @property string comment
 * @property string status
 * @property string reason
 * @property string created_at
 * @property string updated_at
 * @property Employee employee
 * @property LeaveType leaveType
 * @property LeavePolicy leavePolicy
 * @property LeaveRequestHistory[] history
 */
class LeaveRequest extends Model implements ValidatableModelInterface
{

    use ValidatableModel;

    public const STATUS_PENDING = 'pending';
    public const STATUS_DENIED = 'denied';
    public const STATUS_ACCEPTED = 'accepted';

    protected $primaryKey = 'id_leave_request';

    protected $fillable = ['id_employee','id_leave_type','start_at','end_at','days','comment','status','reason'];

    public function getValidationRules(): array
    {
        return [
            'id_employee' => 'required|int|exists:employees',
            'id_leave_type' => 'required|int|exists:leave_types',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'days' => 'required|int',
            'comment' => 'nullable|string',
            'status' => [
                'required',
                'string',
                Rule::in([self::STATUS_PENDING,self::STATUS_DENIED,self::STATUS_ACCEPTED])
            ],
            'reason' => 'required_unless:status,'.self::STATUS_PENDING.','.self::STATUS_ACCEPTED,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getStatuses(){
        $statuses = collect();
        $statuses->put(static::STATUS_PENDING,'Döntésre vár');
        $statuses->put(static::STATUS_ACCEPTED,'Elfogadva');
        $statuses->put(static::STATUS_DENIED,'Elutasítva');
        return $statuses;
    }

    /**
     * @return \Illuminate\Support\Collection|LeaveRequestHistory[]
     */
    public function getHistoryData(){

        return $this->load('history')->history;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leaveType(){
        return $this->belongsTo(LeaveType::class,'id_leave_type','id_leave_type',LeaveRequest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(){
        return $this->belongsTo(Employee::class,'id_employee','id_employee',LeaveRequest::class)->withDefault(['id_employee'=>0,'name'=>'Megszünt felhasználó']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(){
        return $this->hasMany(LeaveRequestHistory::class,'id_leave_request','id_leave_request')->orderByDesc('created_at');
    }

}