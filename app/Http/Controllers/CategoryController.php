<?php

namespace App\Http\Controllers;

use App\Http\Models\Category;
use App\Traits\ResponseFormattingTrait;

class CategoryController extends Controller
{
    use ResponseFormattingTrait;
    public function getList()
    {
        $category = Category::all();

        $response = $this->_formatBaseResponse(200, $category, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
