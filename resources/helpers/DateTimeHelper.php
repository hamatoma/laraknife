<?php
namespace App\Helpers;

class DateTimeHelper
{
    /**
     * Returns the first day of the given year.
     * @param NULL|int $year the year to respect. If NULL the current year is taken
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
}