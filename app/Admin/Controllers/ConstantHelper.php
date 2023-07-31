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
    public static function transactionDetailRecordStatus($value)
    {
        if (array_key_exists($value, Constant::RECORD_STATUS)) {
            return Constant::RECORD_STATUS[$value];
        } else {
            return '';
        }
    }
    public static function transactionGridRecordStatus($value)
    {
        if ($value === 0) {
            return "<span class='label label-warning'>Lưu nháp</span>";
        } elseif ($value === 1) {
            return "<span class='label label-success'>Hiệu lực</span>";
        } elseif ($value === 2) {
            return "<span class='label label-danger'>Huỷ</span>";
        } else {
            return '';
        }
    }
}
