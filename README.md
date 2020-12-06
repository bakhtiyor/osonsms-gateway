# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/osonsms/gateway.svg?style=flat-square)](https://packagist.org/packages/osonsms/gateway)
[![Build Status](https://img.shields.io/travis/osonsms/gateway/master.svg?style=flat-square)](https://travis-ci.org/osonsms/gateway)
[![Quality Score](https://img.shields.io/scrutinizer/g/osonsms/gateway.svg?style=flat-square)](https://scrutinizer-ci.com/g/osonsms/gateway)
[![Total Downloads](https://img.shields.io/packagist/dt/osonsms/gateway.svg?style=flat-square)](https://packagist.org/packages/osonsms/gateway)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require osonsms/gateway
```
In order to publish migration files run following command:
```bash
php artisan vendor:publish --provider="Osonsms\Gateway\GatewayServiceProvider" --tag="migrations"

```
## Usage

``` php
// Usage description here
```

### Testing

``` bash
composer test
```

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