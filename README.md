## PHP Cron Expression Parser

[![GPL v3](http://img.shields.io/:license-gpl3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0-standalone.html)
[![Build Status](https://travis-ci.org/poliander/cron.svg?branch=master)](https://travis-ci.org/poliander/cron)
[![Coverage Status](https://img.shields.io/coveralls/poliander/cron.svg)](https://coveralls.io/r/poliander/cron)
[![Total Downloads](https://poser.pugx.org/poliander/cron/downloads)](https://packagist.org/packages/poliander/cron)

Standard (V7) compatible crontab expression parser/validator with support for time zones; see "[man 5 crontab](http://www.unix.com/man-page/linux/5/crontab/)" for possible expressions.

### Examples

Validate a cron expression:

```php
$cron = new \Cron\Parser('15,45 */2 * * *');
$valid = $cron->valid(); // returns true
```

Check whether given date/time matches a cron expression:
```php
$cron = new \Cron\Parser('45 9 * * *', new \DateTimeZone('Europe/Berlin'));
$dt = new \DateTime('2014-05-18 08:45', new \DateTimeZone('Europe/London'));
$matching = $cron->matching($dt); // returns true
```

Calculate timestamp for next Friday, the 13th:
```php
$cron = new \Cron\Parser('* * 13 * fri');
$next = $cron->next();
```
