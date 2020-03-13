# Zenziva SMS Client

[Zenziva](https://zenziva.net) SMS client. Read their [docs](http://www.zenziva.id/dokumentasi) for more information.

## Installation

Run [composer](http://getcomposer.org)

```bash
composer require agyson/zenziva-sms:dev-master
```

## Usage

### Standalone usage

Make sure you already have Zenziva account.

```php
require 'vendor/autoload.php';

use Agyson\ZenzivaSms\Client as Sms;

$sms = new Sms('userkey', 'passkey');

// Simple usage
$sms->send('08123456789', 'Halo apa kabar?');

// Alternative way
$sms->to('08123456789')
    ->text('Halo apa kabar?')
    ->send();

// SMS masking
$sms->masking()->send('08123456789', 'Halo apa kabar?');

// For OTP
$sms->masking()->otp()->send('08123456789', 'This is OTP code');

// With custom sub-domain (if you choose paid for "SMS Center" plan)
$sms->subdomain('hello')
    ->to('08123456789')
    ->text('Halo apa kabar?')
    ->send();

// Change default URL
$sms->url('https://reguler.zenziva.co.id/apps/smsapi.php')
    ->to('08123456789')
    ->text('Halo')
    ->send();
```

### Use with Laravel Notification

Starts from Laravel 5.3, you can use Laravel Notification feature. You need to register the service provider. Open `config/app.php`, add this line inside `providers`.

```php
Agyson\ZenzivaSms\NotificationServiceProvider::class,
```

> Note: If you use Laravel 5.5 or higher, you can skip register service provider manually.

Insert this inside your `config/services.php`,

```php
'zenziva' => [
    'userkey' => 'your-userkey',
    'passkey' => 'your-password',
    'subdomain' => '',
    'masking' => false,
    'scheme' => 'https',
],
```

Add this method to your `User` model (or any notifiable model),

```php
public function routeNotificationForZenzivaSms()
{
    return $this->phone_number; // Depends on your users table field.
}
```

On your Notification class, add this inside via method. Like so

```php
use Agyson\ZenzivaSms\NotificationChannel as ZenzivaSms;

// ...

public function via($notifiable)
{
    return [ZenzivaSms::class];
}
```

Now, we are ready to use notification feature in Laravel 5.3

```php
use App\User;
use App\Notifications\PingNotification;

Route::get('/', function () {

    // Send notification to all users
    $users = User::all();
    \Notification::send($users, new PingNotification);

    // Or just to one user
    User::find(1)->notify(new PingNotification);
});
```

## License

MIT © [Agy Nurwicaksono](https://www.agyson.com)
