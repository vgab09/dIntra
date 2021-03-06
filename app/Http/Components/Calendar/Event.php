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
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Carbon;


class Event implements EventInterface
{
    protected $start;
    protected $end;
    protected $title;
    protected $rendering;
    protected $color;
    protected $textColor;

    /**
     * Event constructor.
     * @param LeaveRequest|Holiday|Workday $element
     */
    public function __construct($element)
    {
        switch (get_class($element)){
            case LeaveRequest::class:
                $this->buildFromLeaveRequest($element);
                break;
            case Holiday::class:
                $this->buildFromHoliday($element);
                break;
            case Workday::class:
                $this->buildFromWorkDay($element);
                break;
            default:
                break;
        }

    }

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
     * @return mixed
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param mixed $textColor
     * @return Event
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
        return $this;
    }




    /**
     * @param Holiday $holiday
     * @return Event
     */
    public static function fromHoliday(Holiday $holiday)
    {
        return new static($holiday);
    }

    /**
     * @param Workday $workday
     * @return Event
     */
    public static function fromWorkDay(Workday $workday)
    {
        return new static($workday);
    }


    public static function fromLeaveRequest(LeaveRequest $leaveRequest)
    {
        return new static($leaveRequest);
    }

    /**
     * @param Holiday $holiday
     * @return Event
     */
    public function buildFromHoliday(Holiday $holiday)
    {
        $this->setTitle($holiday->name);
        $this->setStart($holiday->start);
        $this->setEnd($holiday->end);
        $this->setColor('#e34f26');
        $this->setTextColor('#fff');

        return $this;
    }

    /**
     * @param Workday $workday
     * @return Event
     */
    public function buildFromWorkDay(Workday $workday)
    {
        $this->setTitle($workday->name);
        $this->setStart($workday->start);
        $this->setEnd($workday->end);
        $this->setColor('#28a745');
        $this->setTextColor('#4875b4');

        return $this;
    }


    /**
     * @param LeaveRequest $leaveRequest
     * @return $this
     */
    public function buildFromLeaveRequest(LeaveRequest $leaveRequest)
    {

        $this->setTitle($leaveRequest->employee->name . ' ' . $leaveRequest->leaveType->name);
        $this->setStart($leaveRequest->start_at);
        $this->setEnd($leaveRequest->end_at);

        if($leaveRequest->status == LeaveRequest::STATUS_PENDING){
            $this->setColor('#ffc107');
        }
        else{
            $this->setColor('#28a745');
            $this->setTextColor('#fff');
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->getTitle(),
            'start' => $this->getStart(),
            'end' => (new Carbon($this->getEnd()))->addDay()->toDateString(),             //Fullcalendar correction.
            'rendering' => $this->getRendering(),
            'color' => $this->getColor(),
            'textColor'=>$this->getTextColor(),
            'allDay' => 'true',
        ];
    }


    /**
     * Convert the model instance to JSON.
     *
     * @param  int $options
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