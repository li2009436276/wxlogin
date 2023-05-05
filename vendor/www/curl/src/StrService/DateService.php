<?php


namespace Curl\StrService;

use DateTime;
class DateService
{
    /**
     * 时间间隔
     * @param $startDay
     * @param $endDay
     * @return mixed
     */
    public static function spaceDay($startDay,$endDay){

        $datetimeStart = new DateTime($startDay);
        $datetimeEnd = new DateTime($endDay);
        $days = $datetimeStart->diff($datetimeEnd)->days;
        return $days;
    }

    /**
     * 获取毫秒时间戳
     * @return int
     */
    public static function mtime() {

        $times = explode(' ',microtime());
        return intval(($times[1]+ $times[0])*1000);
    }

    /**
     * 数字转时间
     * @param $numeral
     * @return false|string
     */
    public static function numeralToTime($numeral) {

        preg_match('/\d{10}/',$numeral,$matches);
        return date('Y-m-d',$matches[0]);
    }
}