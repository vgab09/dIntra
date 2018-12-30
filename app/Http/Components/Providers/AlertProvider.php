<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.02.
 * Time: 14:35
 */

namespace App\Http\Components\Providers;


use App\Http\Components\Alert\Alert;
use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use phpDocumentor\Reflection\Types\Iterable_;

class AlertProvider implements ProviderInterface
{

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $alerts;

    public function __construct()
    {
        $this->alerts = collect();
    }


    public function addAlert($message, $type)
    {
        $this->alerts->push(new Alert($message, $type));
    }

    /**
     * @param string $type Alert type
     * @param iterable|ViewErrorBag|MessageBag|string $messages
     */
    public function merge($type, $messages)
    {

        if (empty($messages)) {
            return;
        }

        if ($messages instanceof MessageProvider) {
            foreach ($messages->getMessageBag()->all() as $message) {
                $this->addAlert($message, $type);
            }
        } elseif ($messages instanceof ViewErrorBag) {
            foreach ($messages->all() as $message) {
                $this->addAlert($message, $type);
            }
        } elseif (is_iterable($messages)) {
            array_walk_recursive($messages, function ($item, $key, $type) {
                $this->addAlert($item, $type);
            }, $type);
        } else {
            $this->addAlert($messages, $type);
        }


    }

    /**
     * @param bool $group
     * @return Alert[]|\Illuminate\Support\Collection
     */
    public function getAlerts($group = false)
    {

        if ($group) {
            return $this->alerts->groupBy('type');
        }

        return $this->alerts;

    }

    /**
     * Flush stored messages
     */
    public function flush()
    {
        $this->alerts = collect();
    }

    public function provide()
    {
        $renderedAlert = '';

        foreach ($this->getAlerts(false) as $alert) {
            $renderedAlert .= $alert->render();
        }

        return $renderedAlert;
    }


}