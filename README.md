## PHP Cron Expression Parser

[![Build Status](https://travis-ci.org/poliander/cron.svg?branch=master)](https://travis-ci.org/poliander/cron)
[![Code Coverage](https://scrutinizer-ci.com/g/poliander/cron/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/poliander/cron/?branch=master)
[![License](https://poser.pugx.org/poliander/cron/license)](https://packagist.org/packages/poliander/cron)
[![Latest Stable Version](https://poser.pugx.org/poliander/cron/v/stable)](https://packagist.org/packages/poliander/cron)
[![Total Downloads](https://poser.pugx.org/poliander/cron/downloads)](https://packagist.org/packages/poliander/cron)

Standard (V7) compliant crontab expression parser/validator with support for time zones; see "[man 5 crontab](http://www.unix.com/man-page/linux/5/crontab/)" for possible expressions.

#### Installation

Using composer, add a requirement for `poliander/cron` to your `composer.json` file:
```
composer require poliander/cron
```

#### Examples

Validate a certain crontab expression:
```php
$expression = new \Cron\CronExpression('15,45 */2 * * *');
$isValid = $expression->isValid(); // returns true
```

Check whether a given point in time is matching a certain cron expression:
```php
$expression = new \Cron\CronExpression('45 9 * * *');
$dt = new \DateTime('2014-05-18 09:45');
$isMatching = $expression->isMatching($dt); // returns true
```

Match an expression across different time zones:
```php
$expression = new \Cron\CronExpression('45 9 * * *', new DateTimeZone('Europe/Berlin'));
$dt = new \DateTime('2014-05-18 08:45', new DateTimeZone('Europe/London'));
$isMatching = $expression->isMatching($dt); // returns true
```

Calculate next timestamp matching a Friday, the 13th:
```php
$expression = new \Cron\CronExpression('* * 13 * fri');
$when = $expression->getNext();
```

#### Supported PHP versions

| cron | PHP |
| --- | ------------ |
| 1.2.* | 5.5 - 5.6 |
| 2.0.* | 7.0 |
| 2.1.* | 7.1 |
| 2.2.* | 7.2 |
| 2.3.* | 7.3 - 8.0 |

#### Changelog

| version | release notes |
| ------- | ------------- |
| 1.0.0 (2015-06-20) | initial release |
| 1.1.0 (2016-06-11) | dropped PHP 5.4 support |
| 1.2.0 (2016-12-11) | added PHP 7.1 support |
| 1.2.1 (2017-05-25) | ~~fixed #3~~ |
| 1.2.2 (2017-06-03) | fixed #3, #4 |
| 2.0.0 (2017-11-30) | dropped PHP 5.x, added PHP 7.2 support, added vendor namespace (closes #2) |
| 2.1.0 (2018-12-08) | dropped PHP 7.0, added PHP 7.3 support, updated PHPUnit dependency to 7.* |
| 2.2.0 (2019-12-03) | dropped PHP 7.1, added PHP 7.4 support, updated PHPUnit dependency to 8.* |
| 2.3.0 (2020-12-29) | dropped PHP 7.2, added PHP 8.0 support, updated PHPUnit dependency to 9.* |
