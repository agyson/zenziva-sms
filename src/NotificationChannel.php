<?php

namespace Agyson\ZenzivaSms;

use Illuminate\Support\Collection;
use Agyson\ZenzivaSms\Client as Sms;
use Illuminate\Notifications\Notification;

class NotificationChannel
{
    /**
     * The Zenziva SMS client instance
     *
     * @var \Agyson\ZenzivaSms\Sms
     */
    protected $zenziva;

    /**
     * Create a new Zenziva SMS channel instance.
     *
     * @param \Agyson\ZenzivaSms\Sms $zenziva
     */
    public function __construct(Sms $zenziva)
    {
        $this->zenziva = $zenziva;
    }

    /**
     * Send the given notification
     *
     * @param  \Illuminate\Support\Collection  $notifiables
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send(Collection $notifiables, Notification $notification)
    {
        foreach ($notifiables as $notifiable) {
            if (! $to = $notifiable->routeNotificationFor('zenziva-sms')) {
                continue;
            }

            $this->zenziva->send([
                'to'   => $to,
                'text' => $notification,
            ]);
        }
    }
}
