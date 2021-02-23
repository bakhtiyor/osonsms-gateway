# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/osonsms/smsgateway.svg?style=flat-square)](https://packagist.org/packages/osonsms/smsgateway)
[![Build Status](https://img.shields.io/travis/osonsms/smsgateway/master.svg?style=flat-square)](https://travis-ci.org/osonsms/smsgateway)
[![Quality Score](https://img.shields.io/scrutinizer/g/osonsms/smsgateway.svg?style=flat-square)](https://scrutinizer-ci.com/g/osonsms/smsgateway)
[![Total Downloads](https://img.shields.io/packagist/dt/osonsms/smsgateway.svg?style=flat-square)](https://packagist.org/packages/osonsms/smsgateway)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require osonsms/gateway
```
## Usage

Run following command to publish a migration file:
```bash
php artisan vendor:publish --provider="OsonSMS\SMSGateway\SMSGatewayServiceProvider" --tag="migrations"
```
Run ```php artisan migrate``` to create a necessary package table.
 
To create a config file in order to specify OsonSMS credentials run following command:
```bash
php artisan vendor:publish --provider="OsonSMS\SMSGateway\SMSGatewayServiceProvider" --tag="config"
```
Open config/smsgateway.php config file and specify following parameters:
* login - Login from OsonSMS
* hash  - Hash string 
* sender_name - SMS Sender Name assigned to you

You can send SMS in your Laravel code using folowing code:
```
$txn_id = uniqid();
$result = SMSGateway::Send('927777777', 'This is my test message from Laravel!', $txn_id);
if ($result)
    echo "SMS has been sent succesfully";
else
    echo "When sending SMS an error occurred";
``` 

You can find the logs of your SMS in the table called ```osonsms_log```.

To check your balance use following code ```SMSGateway::getBalance()``` which returns a decimal number, indicating your balance in Somoni.

If you have any further questions or recommendations, feel free to send us an email at info@osonsms.com. 

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email i@bakhtiyor.tj instead of using the issue tracker.

## Credits

- [Bakhtiyor Bahritidinov](https://github.com/osonsms)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).