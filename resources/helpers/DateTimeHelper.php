<?php
namespace App\Helpers;

class DateTimeHelper
{
    /**
     * Returns the first day of the given year.
     * @param null|int $year the year to respect. If NULL the current year is taken
     * @return \DateTime the first day of the year
     */
    public static function firstDayOfYear(?int $year = null): \DateTime
    {
        if ($year == null) {
            $year = intval(\date('Y'));
        }
        $rc = new \DateTime();
        $rc->setDate($year, 1, 1);
        $rc->setTime(0, 0, 0);
        return $rc;
    }
    /**
     * Converts a time string in the format HH:MM to minutes from midnight.
     * @param string $time a string with a part in the format HH:MM
     * @return null|int null: the string does not match the format, otherwise the minutes from midnight
     */
    public static function timeToMinutes(?string $time): ?int
    {
        $rc = null;
        if ($time != null) {
            $matches = [];
            if (preg_match('/(\d{1,2}):(\d{2})/', $time, $matches)) {
                $rc = intval($matches[1]) * 60 + intval($matches[2]);
            }
        }
        return $rc;
    }
}