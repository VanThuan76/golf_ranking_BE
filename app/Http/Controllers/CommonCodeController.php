<?php

namespace App\Http\Controllers;

use App\Http\Models\CommonCode;
use App\Traits\ResponseFormattingTrait;

class CommonCodeController extends Controller
{
    use ResponseFormattingTrait;
    public function getList()
    {
        $commonCodes = CommonCode::all();
        $response = $this->_formatBaseResponse(200, $commonCodes, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
