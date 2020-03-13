<?php

namespace Agyson\ZenzivaSms;

use Agyson\ZenzivaSms\Client as Sms;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Agyson\ZenzivaSms\Client', function () {
            $sms = new Sms(
                config('services.zenziva.userkey'),
                config('services.zenziva.passkey')
            );

            if ($subdomain = config('services.zenziva.subdomain')) {
                $sms->subdomain($subdomain);
            }

            if (config('services.zenziva.masking')) {
                $sms->masking();
            }

            if (in_array(config('services.zenziva.scheme'), ['http', 'https'])) {
                $sms->scheme(config('services.zenziva.scheme'));
            }

            return $sms;
        });
    }
}
