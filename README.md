# PHP Cron Expression Parser

[![Build Status](https://scrutinizer-ci.com/g/poliander/cron/badges/build.png?b=main)](https://scrutinizer-ci.com/g/poliander/cron/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/poliander/cron/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/poliander/cron/?branch=main)
[![License](https://poser.pugx.org/poliander/cron/license)](https://www.gnu.org/licenses/gpl-3.0.en.html)
[![Latest Stable Version](https://poser.pugx.org/poliander/cron/v/stable)](https://packagist.org/packages/poliander/cron)
[![Total Downloads](https://poser.pugx.org/poliander/cron/downloads)](https://packagist.org/packages/poliander/cron)

Standard (V7) compliant crontab expression parser/validator with support for time zones; see "[man 5 crontab](http://www.unix.com/man-page/linux/5/crontab/)" for possible expressions.

<!-- toc -->

- [Installation](#installation)
- [Examples](#examples)
- [Supported PHP Versions](#supported-php-versions)
- [Changelog](#changelog)

<!-- tocstop -->

## Installation

Using composer, add a requirement for `poliander/cron` to your `composer.json` file:
```
composer require poliander/cron
```

## Examples

Validate a certain crontab expression:
```php
use Poliander\Cron\CronExpression;

$expression = new CronExpression('15,45 */2 * * *');
$isValid = $expression->isValid(); // returns true
```

Check whether a given point in time is matching a certain cron expression:
```php
use Poliander\Cron\CronExpression;

$expression = new CronExpression('45 9 * * *');
$dt = new \DateTime('2014-05-18 09:45');
$isMatching = $expression->isMatching($dt); // returns true
```

Match an expression across different time zones:
```php
use Poliander\Cron\CronExpression;

$expression = new CronExpression('45 9 * * *', new DateTimeZone('Europe/Berlin'));
$dt = new \DateTime('2014-05-18 08:45', new DateTimeZone('Europe/London'));
$isMatching = $expression->isMatching($dt); // returns true
```

Calculate next timestamp matching a Friday, the 13th:
```php
use Poliander\Cron\CronExpression;

$expression = new CronExpression('* * 13 * fri');
$when = $expression->getNext();
```

## Supported PHP Versions

| cron  | PHP       | Note        |
| ----- | --------- | ----------- |
| 1.2.* | 5.6       | end of life |
| 2.0.* | 7.0       | end of life |
| 2.1.* | 7.1       | end of life |
| 2.2.* | 7.2       | end of life |
| 2.3.* | 7.3       | end of life |
| 2.4.* | 7.4 - 8.2 | end of life |
| 3.0.* | 7.4 - 8.2 | end of life |
| 3.1.* | 8.1 - 8.3 |             |

## Changelog

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
| 2.3.1 (2021-10-04) | fixed #6 |
| 2.4.0 (2021-12-27) | dropped PHP 7.3, added PHP 8.1 support |
| 2.4.1 (2022-03-25) | ~~fixed #9~~ |
| 2.4.2 (2022-04-09) | fixed #9, #10, #11 |
| 2.4.3 (2022-04-10) | fixed #12 |
| 2.4.4 (2022-04-11) | fixed #13 |
| 2.4.5 (2022-12-17) | fixed #14 |
| 2.4.6 (2022-12-29) | added PHP 8.2 support |
| 2.4.7 (2023-01-20) | fixed #16 |
| 2.4.8 (2023-07-30) | fixed #18, #19 |
| 2.4.9 (2023-10-28) | fixed #22 |
| 3.0.0 (2022-04-09) | namespace changed to avoid package conflict (closes #8) |
| 3.0.1 (2022-04-10) | fixed #12 |
| 3.0.2 (2022-04-11) | fixed #13 |
| 3.0.3 (2022-12-17) | fixed #14 |
| 3.0.4 (2022-12-29) | added PHP 8.2 support |
| 3.0.5 (2023-01-20) | fixed #16 |
| 3.0.6 (2023-07-30) | fixed #18, #19 |
| 3.0.7 (2023-10-28) | fixed #22 |
| 3.1.0 (2023-11-23) | added PHP 8.3 support, dropped PHP 7/8.0 support, updated PHPUnit to 10.* |
