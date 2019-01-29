<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.11.25.
 * Time: 19:39
 */

namespace App\Http\Components\Alert;


class Alert implements AlertInterface
{

    protected $message;

    protected $type;

    protected $types = ['info', 'success', 'warning', 'danger'];

    public const INFO = 'info';

    public const SUCCESS = 'success';

    public const WARNING = 'warning';

    public const ERROR = 'danger';

    /**
     * Alert constructor.
     * @param string $message
     * @param string $type info, success, warning, danger
     */
    public function __construct($message, $type)
    {
        $this->setMessage($message);
        $this->setType($type);
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
     * @return Alert
     */
    public function setMessage(string $message): Alert
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
     * @return Alert
     */
    public function setType(string $type): Alert
    {

        if (!in_array($type, $this->types)) {
            $type = 'information';
        }

        $this->type = $type;
        return $this;
    }

    /**
     * Render Alert message
     * @return string
     */
    public function render()
    {
        switch ($this->getType()) {
            case self::SUCCESS:
                return sprintf('<div class="alert alert-success" role="alert">%s <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>',$this->getMessage());
                break;
            case self::WARNING:
                return sprintf('<div class="alert alert-warning" role="alert">%s <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>',$this->getMessage());
                break;
            case self::ERROR:
                return sprintf('<div class="alert alert-danger" role="alert">%s <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>',$this->getMessage());
                break;
            case self::INFO:
            default:
                return sprintf('<div class="alert alert-primary" role="alert">%s<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>',$this->getMessage());
                break;
        }

    }

}