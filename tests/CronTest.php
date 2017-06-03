<?php

require_once __DIR__ . '/../src/Cron.php';

class CronTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parserTestProvider
     */
    public function testParser($expression, $valid = false, $dtime = 'now', $matching = false)
    {
        $ct = new Cron($expression, new DateTimeZone('Europe/Berlin'));
        $dt = new DateTime($dtime, new DateTimeZone('Europe/Berlin'));

        $this->assertEquals($valid, $ct->isValid());
        $this->assertEquals($matching, $ct->isMatching($dt->getTimeStamp()));
    }

    /**
     * @return array
     */
    public function parserTestProvider()
    {
        return array(
            array("* * * * *",                true, 'now', true),
            array(" *\t    *\t* *  * \t  ",   true, 'now', true),
            array("1 0 * * *",                true, '2017-05-26 00:01', true),
            array("0 * * * *",                true, '2013-02-13 12:00', true),
            array("* * 1 * *",                true, '2013-03-01 21:43', true),
            array("* * * * 7",                true, '2013-04-07 06:54', true),
            array("* 23 * * *",               true, '2012-05-23 23:23', true),
            array("* 23 * * *",               true, '2012-05-23 21:23', false),
            array("* * * * sat",              true, '1994-10-01 23:45', true),
            array("* * * * sat",              true, '1993-10-01 23:45', false),
            array("* * * aug *",              true, '2001-08-09 00:15', true),
            array("*/5 * * * *",              true, '2013-09-10 04:45', true),
            array("*/5 * * * *",              true, '2013-09-10 04:43', false),
            array("0-30 * * * *",             true, '2010-08-01 08:15', true),
            array("0-30 * * * *",             true, '2010-08-01 08:45', false),
            array("* 22-23 * * *",            true, '2006-05-07 22:30', true),
            array("10-50/10 * * * *",         true, '2005-11-12 14:40', true),
            array("10-30/5,45 * * * *",       true, '2009-08-10 15:15', true),
            array("10-30/5,45 * * * *",       true, '2009-09-11 15:45', true),
            array("5-15,45-55 * * * *",       true, '2010-08-02 10:05', true),
            array("5-10,15,20-25 * * * *",    true, '2011-07-16 07:04', false),
            array("5-10,15,20-25 * * * *",    true, '2011-07-16 07:07', true),
            array("5-10,15,20-25 * * * *",    true, '2011-07-16 07:15', true),
            array("5-10,15,20-25 * * * *",    true, '2011-07-16 07:17', false),
            array("50-10/5,20,30-40 * * * *", true, '2006-12-31 23:55', true),
            array("50-10/5,20,30-40 * * * *", true, '2006-12-31 23:56', false),
            array("50-10/5,20,30-40 * * * *", true, '2006-12-31 23:35', true),
            array("50-10/5,20,30-40 * * * *", true, '2007-01-01 00:45', false),
            array("50-10/5,20,30-40 * * * *", true, '2007-01-01 00:05', true),
            array("58-2/2 * * * *",           true, '2015-07-16 08:56', false),
            array("58-2/2 * * * *",           true, '2015-07-16 08:57', false),
            array("58-2/2 * * * *",           true, '2015-07-16 08:58', true),
            array("58-2/2 * * * *",           true, '2015-07-16 08:59', false),
            array("58-2/2 * * * *",           true, '2015-07-16 09:00', true),
            array("58-2/2 * * * *",           true, '2015-07-16 09:01', false),
            array("58-2/2 * * * *",           true, '2015-07-16 09:02', true),
            array("58-2/2 * * * *",           true, '2015-07-16 09:03', false),
            array("58-2/2 * * * *",           true, '2015-07-16 09:04', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 14:01', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 15:02', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 16:03', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 17:01', true),
            array("* 17-1/3 * * *",           true, '2014-01-30 18:02', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 19:03', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 20:01', true),
            array("* 17-1/3 * * *",           true, '2014-01-30 21:02', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 22:03', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 23:01', true),
            array("* 17-1/3 * * *",           true, '2014-01-30 00:01', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 01:02', false),
            array("* 17-1/3 * * *",           true, '2014-01-30 02:03', false),
            array("* * * * 7",                true, '2013-07-07 00:00', true),
            array("* * * * 0",                true, '2013-07-07 00:00', true),
            array("1-1 * * * *",              true, '2012-04-07 00:01', true),

            array("foobar"),
            array("* * * *"),
            array("a * * * *"),
            array("* * 0 * *"),
            array("* * * * 8"),
            array("*/ * * * "),
            array("*/foo * * * *"),
            array("* * */32 * *"),
            array("* * * 32 *"),
            array("// * * * *"),
            array("*5 * * * *"),
            array("5- * * * *"),
            array("/5 * * * *"),
            array("-6 * * * *"),
            array("60 * * * *"),
            array("* 24 * * *"),
            array("* * * * * *"),
            array("4/2 * * * *"),
            array("* * * * foo"),
            array("* * * bar *"),
            array("5-60 * * * *"),
            array("* 23-24 * * *"),
            array("*/60 0-12 * * *"),
            array("5-20/5 22-23- * * *"),
            array("50-10/5,,30-40 * * * *"),

            array("* * * * mon,tue"), // see crontab(5) man page
            array("* * * jan,feb *"), // see crontab(5) man page
        );
    }

    /**
     * @dataProvider getNextProvider
     */
    public function testGetNext($expression, $timestamp, $nextmatch)
    {
        $ct = new Cron($expression, new \DateTimeZone('Europe/Berlin'));
        $this->assertEquals($ct->getNext($timestamp), $nextmatch);
    }

    /**
     * @return array
     */
    public function getNextProvider()
    {
        return [
            ['* * 13 * fri', 1400407467, 1402610400],
            ['*/15 * * * *', 1400407520, 1400408100],
            ['1 0 * * *', 1495697149, 1495749660],
            ['1 0 * * *', 1496223073, 1496268060]
        ];
    }

    public function testIsMatchingWithDateTime()
    {
        $cron = new Cron('45 9 * * *', new DateTimeZone('Europe/Berlin'));
        $dt = new DateTime('2014-05-18 08:45', new DateTimeZone('Europe/London'));
        $this->assertEquals(true, $cron->isMatching($dt));
    }

    public function testGetNextWithDateTime()
    {
        $cron = new Cron('45 9 * * *', new DateTimeZone('Europe/Berlin'));
        $dt = new DateTime('2014-05-18 08:40', new DateTimeZone('Europe/London'));

        $this->assertEquals(1400399100, $cron->getNext($dt));
    }

    public function testGetNextWithoutParameter()
    {
        $cron = new Cron('* * * * *');
        $this->assertEquals(ceil((60 + time()) / 60) * 60, $cron->getNext());
    }

    public function testGetNextWithTimestamp()
    {
        $tz = new DateTimezone('Europe/Berlin');
        $dt = new DateTime('2014-12-31 23:42', $tz);
        $cron = new Cron('45 9 29 feb thu', $tz);

        $this->assertEquals(1709196300, $cron->getNext($dt->getTimestamp()));
    }

    public function testGetNextRepeatedly()
    {
        $t = 1496478227;
        $cron = new Cron('*/30 */2 * * *');

        $this->assertEquals(1496478600, $t = $cron->getNext($t));
        $this->assertEquals(1496484000, $t = $cron->getNext($t));
        $this->assertEquals(1496485800, $t = $cron->getNext($t));
        $this->assertEquals(1496491200, $t = $cron->getNext($t));
    }
}
