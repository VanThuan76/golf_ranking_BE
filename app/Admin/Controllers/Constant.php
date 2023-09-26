<?php

namespace App\Admin\Controllers;


abstract class Constant
{
    const PAGE_STATUS = array( 1 => "Đã duyệt", 0 => 'Chưa duyệt', -1 => 'Xoá');
    const RECORD_STATUS = array(0 => "Lưu nháp", 1 => "Hiệu lực", 2 => "Huỷ", 3 => "Lịch sử");
    const RECORDSTATUS_INSERT_AND_UPDATE = array(0 => "Lưu nháp", 1 => "Hiệu lực");  //Lưu nháp
    const RECORDSTATUS_UPDATE = array(1 => "Hiệu lực", 2 => "Huỷ");  //Hiệu lực
}

