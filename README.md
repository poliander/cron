## PHP Cron Expression Parser

[![Build Status](https://travis-ci.org/poliander/cron.svg?branch=master)](https://travis-ci.org/poliander/cron)
[![Coverage Status](https://img.shields.io/coveralls/poliander/cron.svg)](https://coveralls.io/r/poliander/cron)
[![License](https://poser.pugx.org/poliander/cron/license)](https://packagist.org/packages/poliander/cron)
[![Total Downloads](https://poser.pugx.org/poliander/cron/downloads)](https://packagist.org/packages/poliander/cron)

Standard (V7) compatible crontab expression parser/validator with support for time zones; see "[man 5 crontab](http://www.unix.com/man-page/linux/5/crontab/)" for possible expressions.

### Examples

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

### Installation