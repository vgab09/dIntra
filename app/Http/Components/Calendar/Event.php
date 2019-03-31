<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019.03.31.
 * Time: 19:45
 */

namespace App\Http\Components\Calendar;


use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\Workday;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use JsonSerializable;

class Event implements Jsonable, JsonSerializable
{
    protected $start;
    protected $end;
    protected $title;
    protected $rendering;
    protected $color;

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRendering()
    {
        return $this->rendering;
    }

    /**
     * @param mixed $rendering
     * @return Event
     */
    public function setRendering($rendering)
    {
        $this->rendering = $rendering;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     * @return Event
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }



    /**
     * @param Holiday $holiday
     * @return Event
     */
    public static function fromHoliday(Holiday $holiday){
        $event = new static();
        $event->setTitle($holiday->name);
        $event->setStart($holiday->start);
        $event->setEnd($holiday->end);

        return $event;
    }

    /**
     * @param Workday $workday
     * @return Event
     */
    public static function fromWorkDay(Workday $workday){
        $event = new static();
        $event->setTitle($workday->name);
        $event->setStart($workday->start);
        $event->setEnd($workday->end);

        return $event;
    }


    public static function fromLeaveRequest(LeaveRequest $leaveRequest)
    {
        $event = new static();
        $event->setTitle($leaveRequest->employee->name . ' ' . $leaveRequest->leaveType->name);
        $event->setStart($leaveRequest->start_at);
        $event->setEnd($leaveRequest->end_at);

        return $event;
    }

    /**
     * @return array
     */
    public function toArray(){
        return [
            'title' => $this->getTitle(),
            'start' => $this->getStart(),
            'end' => $this->getEnd(),
            'rendering' => $this->getRendering(),
            'color' => $this->getColor(),
        ];
    }


    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0)
    {

        $json = json_encode($this->toArray(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}