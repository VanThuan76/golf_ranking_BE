<?php

namespace App\Http\Controllers;

use App\Http\Models\Group;
use App\Admin\Controllers\UtilsCommonHelper;
use App\Traits\GroupFormattingTrait;
use App\Traits\ResponseFormattingTrait;

class GroupController extends Controller
{

    use ResponseFormattingTrait, GroupFormattingTrait;
    public function getList(UtilsCommonHelper $commonController)
    {
        $groups = Group::all();

        $transformedGroups = [];
        foreach ($groups as $group) {
            $group = $this->_formatgroup($group, $commonController);
            $transformedGroups[] = $group;
        }

        $response = $this->_formatBaseResponse(200, $transformedGroups, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
