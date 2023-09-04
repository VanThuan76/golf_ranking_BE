<?php

namespace App\Http\Controllers;

use App\Http\Models\Group;
use App\Admin\Controllers\UtilsCommonHelper;
use App\Traits\ResponseFormattingTrait;

class GroupController extends Controller
{

    use ResponseFormattingTrait;
    public function getList(UtilsCommonHelper $commonController)
    {
        $groups = Group::all();

        $transformedGroups = $groups->map(function ($group) use ($commonController) {
            $group->gender = $commonController->commonCodeGridFormatter('Gender', 'description_vi', $group->gender);
            $group->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $group->status);

            return $group;
        });
        $response = $this->_formatBaseResponse(200, $transformedGroups, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
