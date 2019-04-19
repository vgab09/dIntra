<?php

namespace App\Http\Components\Calendar;

use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\Workday;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface EventInterface extends Jsonable, JsonSerializable
{
    /**
     * @param Holiday $holiday
     * @return Event
     */
    public static function fromHoliday(Holiday $holiday);

    /**
     * @param Workday $workday
     * @return EventInterface
     */
    public static function fromWorkDay(Workday $workday);

    /**
     * @param LeaveRequest $leaveRequest
     * @return EventInterface
     */
    public static function fromLeaveRequest(LeaveRequest $leaveRequest);

    /**
     * @param Holiday $holiday
     * @return EventInterface
     */
    public function buildFromHoliday(Holiday $holiday);

    /**
     * @param Workday $workday
     * @return EventInterface
     */
    public function buildFromWorkDay(Workday $workday);

    /**
     * @param LeaveRequest $leaveRequest
     * @return EventInterface
     */
    public function buildFromLeaveRequest(LeaveRequest $leaveRequest);


    /**
     * @return array
     */
    public function toArray();

    /**
     * Convert the model instance to JSON.
     *
     * @param  int $options
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0);

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize();

}