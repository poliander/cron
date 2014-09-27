<?php

namespace Cron;

/**
 * Cron expression parser and validator
 *
 * @author René Pollesch
 */
class Parser
{
    /**
     * Weekday look-up table
     *
     * @var array
     */
    protected static $weekdays = array(
        'sun' => 0,
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6
    );

    /**
     * Month name look-up table
     *
     * @var array
     */
    protected static $months = array(
        'jan' => 1,
        'feb' => 2,
        'mar' => 3,
        'apr' => 4,
        'may' => 5,
        'jun' => 6,
        'jul' => 7,
        'aug' => 8,
        'sep' => 9,
        'oct' => 10,
        'nov' => 11,
        'dec' => 12
    );

    /**
     * Cron expression
     *
     * @var string
     */
    protected $expression;

    /**
     * Time zone
     *
     * @var \DateTimeZone
     */
    protected $timeZone;

    /**
     * Matching register
     *
     * @var array|null
     */
    protected $register;

    /**
     * Class constructor sets cron expression property
     *
     * @param string $expression cron expression
     * @param \DateTimeZone $timeZone
     */
    public function __construct($expression = '* * * * *', \DateTimeZone $timeZone = null)
    {
        $this->setExpression($expression);
        $this->setTimeZone($timeZone);
    }

    /**
     * Set expression
     *
     * @param string $expression
     * @return self
     */
    public function setExpression($expression)
    {
        $this->expression = trim((string)$expression);
        $this->register = null;

        return $this;
    }

    /**
     * Set time zone
     *
     * @param \DateTimeZone $timeZone
     * @return self
     */
    public function setTimeZone(\DateTimeZone $timeZone = null)
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * Parse and validate cron expression
     *
     * @return bool true if expression is valid, or false on error
     */
    public function valid()
    {
        $result = true;

        if ($this->register === null) {
            if (sizeof($segments = preg_split('/\s+/', $this->expression)) !== 5) {
                $result = false;
            } else {
                $register = array();

                $minv = array(0, 0, 1, 1, 0);
                $maxv = array(59, 23, 31, 12, 7);
                $strv = array(false, false, false, self::$months, self::$weekdays);

                foreach ($segments as $s => $segment) {

                    // month names, weekdays
                    if ($strv[$s] !== false && isset($strv[$s][strtolower($segment)])) {
                        // cannot be used with lists or ranges, see crontab(5) man page
                        $register[$s][$strv[$s][strtolower($segment)]] = true;
                        continue;
                    }

                    // split up list into segments (e.g. "1,3-5,9")
                    foreach (explode(',', $segment) as $l => $listsegment) {

                        // parse steps notation
                        if (strpos($listsegment, '/') !== false) {
                            if (sizeof($stepsegments = explode('/', $listsegment)) === 2) {
                                $listsegment = $stepsegments[0];

                                if (is_numeric($stepsegments[1])) {
                                    if ($stepsegments[1] > 0 && $stepsegments[1] <= $maxv[$s]) {
                                        $steps = intval($stepsegments[1]);
                                    } else {
                                        // steps value is out of allowed range
                                        $result = false;
                                        break 2;
                                    }
                                } else {
                                    // invalid (non-numeric) steps notation
                                    $result = false;
                                    break 2;
                                }
                            } else {
                                // invalid steps notation
                                $result = false;
                                break 2;
                            }
                        } else {
                            $steps = 1;
                        }

                        // single value
                        if (is_numeric($listsegment)) {
                            if (intval($listsegment) < $minv[$s] || intval($listsegment) > $maxv[$s]) {
                                // value is out of allowed range
                                $result = false;
                                break 2;
                            }

                            if ($steps !== 1) {
                                // single value cannot be combined with steps notation
                                $result = false;
                                break 2;
                            }

                            $register[$s][intval($listsegment)] = true;
                            continue;
                        }

                        // asterisk indicates full range of values
                        if ($listsegment === '*') {
                            $listsegment = sprintf('%d-%d', $minv[$s], $maxv[$s]);
                        }

                        // range of values, e.g. "9-17"
                        if (strpos($listsegment, '-') !== false) {
                            if (sizeof($ranges = explode('-', $listsegment)) !== 2) {
                                // invalid range notation
                                $result = false;
                                break 2;
                            }

                            // validate range
                            foreach ($ranges as $r => $range) {
                                if (is_numeric($range)) {
                                    if (intval($range) < $minv[$s] || intval($range) > $maxv[$s]) {
                                        // start or end value is out of allowed range
                                        $result = false;
                                        break 3;
                                    }
                                } else {
                                    // non-numeric range notation
                                    $result = false;
                                    break 3;
                                }
                            }

                            // fill matching register
                            if ($ranges[0] === $ranges[1]) {
                                $register[$s][$ranges[0]] = true;
                            } else {
                                for ($i = $minv[$s]; $i <= $maxv[$s]; $i++) {
                                    if (($i - $ranges[0]) % $steps === 0) {
                                        if ($ranges[0] < $ranges[1]) {
                                            if ($i >= $ranges[0] && $i <= $ranges[1]) {
                                                $register[$s][$i] = true;
                                            }
                                        } elseif ($i >= $ranges[0] || $i <= $ranges[1]) {
                                            $register[$s][$i] = true;
                                        }
                                    }
                                }
                            }

                            continue;
                        }

                        // list segment cannot be parsed
                        $result = false;
                        break 2;
                    }
                }

                if ($result === true) {
                    if (isset($register[4][7])) {
                        $register[4][0] = true;
                    }

                    $this->register = $register;
                }
            }
        }

        return $result;
    }

    /**
     * Match current or given date/time against cron expression
     *
     * @param mixed $dtime \DateTime object, timestamp or null
     * @return bool
     */
    public function matching($dtime = null)
    {
        $result = false;

        if ($this->valid()) {
            if ($dtime instanceof \DateTime) {
                $dt = $dtime;
                $dt->setTimeZone($this->timeZone);
            } else {
                $dt = new \DateTime('now', $this->timeZone);

                if ((int)$dtime > 0) {
                    $dt->setTimestamp($dtime);
                }
            }

            list($minute, $hour, $day, $month, $weekday) = sscanf(
                $dt->format('i G j n w'),
                '%d %d %d %d %d'
            );

            if (isset($this->register[4][(int)$weekday]) &&
                isset($this->register[3][(int)$month]) &&
                isset($this->register[2][(int)$day]) &&
                isset($this->register[1][(int)$hour]) &&
                isset($this->register[0][(int)$minute])) {

                $result = true;
            }
        }

        return $result;
    }

    /**
     * Calculate next matching timestamp
     *
     * @param mixed $dtime \DateTime object, timestamp or null
     * @return int|bool next matching timestamp, or false on error
     */
    public function next($dtime = null)
    {
        $result = false;

        if ($this->valid()) {
            if ($dtime instanceof \DateTime) {
                $timestamp = $dtime->getTimestamp();
            } elseif ((int)$dtime > 0) {
                $timestamp = $dtime;
            } else {
                $timestamp = time();
            }

            $dt = new \DateTime('now', $this->timeZone);
            $dt->setTimestamp(ceil($timestamp / 60) * 60);

            list($pday, $pmonth, $pyear, $phour) = sscanf(
                $dt->format('j n Y G'),
                '%d %d %d %d'
            );

            while ($result === false) {
                list($minute, $hour, $day, $month, $year, $weekday) = sscanf(
                    $dt->format('i G j n Y w'),
                    '%d %d %d %d %d %d'
                );

                if ($pyear !== $year) {
                    $dt->setDate($year, 1, 1);
                    $dt->setTime(0, 0);
                } elseif ($pmonth !== $month) {
                    $dt->setDate($year, $month, 1);
                    $dt->setTime(0, 0);
                } elseif ($pday !== $day) {
                    $dt->setTime(0, 0);
                } elseif ($phour !== $hour) {
                    $dt->setTime($hour, 0);
                }

                list($pday, $pmonth, $pyear, $phour) = array($day, $month, $year, $hour);

                if (isset($this->register[3][$month]) === false) {
                    $dt->modify('+1 month');
                    continue;
                } elseif (isset($this->register[2][$day]) === false || isset($this->register[4][$weekday]) === false) {
                    $dt->modify('+1 day');
                    continue;
                } elseif (isset($this->register[1][$hour]) === false) {
                    $dt->modify('+1 hour');
                    continue;
                } elseif (isset($this->register[0][$minute]) === false) {
                    $dt->modify('+1 minute');
                    continue;
                }

                $result = $dt->getTimestamp();
            }
        }

        return $result;
    }
}
