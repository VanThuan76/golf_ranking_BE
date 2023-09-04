<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;

class ConstantHelper
{
    public static function dateFormatter($dateIn)
    {
        if ($dateIn === null) {
            return "";
        }

        $carbonDateIn = Carbon::parse($dateIn)->setTimezone('Asia/Bangkok');
        return $carbonDateIn->format('d/m/Y - H:i:s');
    }
    public static function dayFormatter($dayIn)
    {
        if ($dayIn === null) {
            return "";
        }

        $carbonDayIn = Carbon::parse($dayIn)->setTimezone('Asia/Bangkok');
        return $carbonDayIn->format('d/m/Y');
    }
    public static function moneyFormatter($money)
    {
        return number_format($money, 0, ',', ',') . " VND";
    }
}
