<?php

namespace App\Admin\Controllers;


abstract class Constant
{
    const SCHEDULE_CLASS = array(   "Thứ 2" => "Thứ 2", 
                                    "Thứ 3" => "Thứ 3", 
                                    "Thứ 4" => "Thứ 4", 
                                    "Thứ 5" => "Thứ 5", 
                                    "Thứ 6" => "Thứ 6", 
                                    "Thứ 7" => "Thứ 7",
                                    "Chủ nhật" => "Chủ nhật");
    const RECORD_STATUS = array(0 => "Lưu nháp", 1 => "Hiệu lực", 2 => "Huỷ", 3 => "Lịch sử");
    const RECORDSTATUS_INSERT_AND_UPDATE = array(0 => "Lưu nháp", 1 => "Hiệu lực");  //Lưu nháp
    const RECORDSTATUS_UPDATE = array(1 => "Hiệu lực", 2 => "Huỷ");  //Hiệu lực

    //Hieu luc
    //Lich su la khi co ban ghi moi
}

