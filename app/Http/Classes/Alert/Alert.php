<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 19:39
 */

namespace App\Http\Classes\Alert;


class Alert
{

    protected $message;

    protected $type;

    protected $types = ['info', 'success', 'warning', 'danger'];

    /**
     * Alert constructor.
     * @param string $message
     * @param string $type info, success, warning, danger
     */
    public function __construct($message, $type)
    {
        $this->message = $message;
        $this->type = $this->setType($type);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return SimpleAlert
     */
    public function setMessage(string $message): SimpleAlert
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return SimpleAlert
     */
    public function setType(string $type): SimpleAlert
    {

        if(!in_array($type,$this->types)){
            $type = 'information';
        }

        $this->type = $type;
        return $this;
    }

}