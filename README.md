# OsonSMS package for sending SMS

[![Latest Version on Packagist](https://img.shields.io/packagist/v/osonsms/gateway.svg?style=flat-square)](https://packagist.org/packages/osonsms/gateway)
[![Total Downloads](https://img.shields.io/packagist/dt/osonsms/gateway.svg?style=flat-square)](https://packagist.org/packages/osonsms/gateway)

Here you can find a short instruction of how to install and use this package.

## Installation

You can install the package via composer:

```bash
composer require osonsms/gateway
```
## Usage

Run following command to publish a migration file:
```bash
php artisan vendor:publish --provider="OsonSMS\OsonSMSService\OsonSmsServiceProvider" --tag="migrations"
```
Run ```php artisan migrate``` to create a necessary package table.
 
To create a config file in order to specify OsonSMS credentials run following command:
```bash
php artisan vendor:publish --provider="OsonSMS\OsonSMSService\OsonSmsServiceProvider" --tag="config"
```
Open ```config/osonsmsservice.php``` config file and specify following parameters:
* login - Login from OsonSMS
* pass_salt_hash  - You pass salt hash string given after the registration, it is kind of api token 
* sender_name - SMS Sender Name assigned to you

Below you can find a console command created in Laravel in order to test OsonSMS service. 
Run ```php artisan app:test-osonsms-service``` in the terminal to see it in action, 
don't forget to run previous commands to publish migration file and create config file.     
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use JsonException;
use OsonSMS\OsonSMSService\OsonSmsService;
use RuntimeException;

class TestOsonSMSServiceCommand extends Command
{
    protected $signature = 'app:test-osonsms-service';
    public function __construct(private readonly OsonSmsService $osonSmsService) {
        parent::__construct();
    }
    public function handle(): void
    {
        try {
            // You need to provide senderName, phonenumber, message and txtId to sendSMS method in order to send SMS.
            $msgId = $this->osonSmsService->sendSMS(
                senderName: config('osonsmsservice.sender_name'),
                phonenumber: '927777777',
                message: 'Hello from Laravel. Your random code: ' . random_int(100, 1000),
                txnId: uniqid('test', true)
            );
            echo "SMS sent successfully with msg_id: $msgId" . PHP_EOL;
            // This is how you can check the sms status using msgId returned from sendSMS method.
            sleep(5); // Intentionally sleeping for 5 seconds in order to get the more accurate sms delivery status
            echo "SMS Status: " .  $this->osonSmsService->getSMSStatus($msgId) . PHP_EOL;
            // To get the balance of your account simply call getBalance() method
            echo "My Balance: " .  $this->osonSmsService->getBalance() . PHP_EOL;
        } catch (RuntimeException|JsonException $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}

``` 

You can find the logs of your SMS in the table called ```osonsms_log```.

If you have any further questions or recommendations, feel free to send us an email at info@osonsms.com. 

### Security

If you discover any security related issues, please email i@bakhtiyor.tj instead of using the issue tracker.

## Credits
- [Bakhtiyor Bahritidinov](https://github.com/osonsms)
