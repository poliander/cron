## PHP Cron Expression Parser

[![Build Status](https://travis-ci.org/poliander/cron.svg?branch=master)](https://travis-ci.org/poliander/cron)
[![Code Coverage](https://scrutinizer-ci.com/g/poliander/cron/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/poliander/cron/?branch=master)
[![License](https://poser.pugx.org/poliander/cron/license)](https://packagist.org/packages/poliander/cron)
[![Latest Stable Version](https://poser.pugx.org/poliander/cron/v/stable)](https://packagist.org/packages/poliander/cron)
[![Total Downloads](https://poser.pugx.org/poliander/cron/downloads)](https://packagist.org/packages/poliander/cron)

Standard (V7) compliant crontab expression parser/validator with support for time zones; see "[man 5 crontab](http://www.unix.com/man-page/linux/5/crontab/)" for possible expressions.

#### Installation

Using composer, include a dependency for `poliander/cron` in your `composer.json` file:
```
"require": {
    "poliander/cron": "1.*"
}
```

#### Examples

Validate a cron expression:
```php
$cron = new Cron('15,45 */2 * * *');
$isValid = $cron->isValid(); // returns true
```

Check whether given date/time matches a cron expression:
```php
$cron = new Cron('45 9 * * *', new DateTimeZone('Europe/Berlin'));
$dt = new DateTime('2014-05-18 08:45', new DateTimeZone('Europe/London'));
$isMatching = $cron->isMatching($dt); // returns true
```

Calculate next timestamp matching a Friday, the 13th:
```php
$cron = new Cron('* * 13 * fri');
$ts = $cron->getNext();
```

#### Changelog

| version | release notes |
| ------- | ------------- |
| 1.0.0 (2015-06-20) | initial release |
| 1.1.0 (2016-06-11) | dropped PHP 5.4 support |
| 1.2.0 (2016-12-11) | added PHP 7.1 support |
| 1.2.1 (2017-05-25) | ~~fixed #3~~ |
| 1.2.2 (2017-06-03) | fixed #3, #4 |
