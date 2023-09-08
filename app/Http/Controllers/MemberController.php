<?php

namespace App\Http\Controllers;

use App\Http\Models\Member;
use App\Admin\Controllers\UtilsCommonHelper;
use App\Traits\MemberFormattingTrait;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    use ResponseFormattingTrait, MemberFormattingTrait;

    public function getById($id, UtilsCommonHelper $commonController)
    {
        $member = Member::find($id);
        if (!$member) {
            $response = $this->_formatBaseResponse(404, null, 'Giải đấu không được tìm thấy');
            return response()->json($response, 404);
        }

        $transformedMember = $this->_formatMember($member, $commonController);

        $response = $this->_formatBaseResponse(200, $transformedMember, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public function searchMember(Request $request, UtilsCommonHelper $commonController)
    {
        $query = Member::query();
        $filters = $request->input('filters', []);

        foreach ($filters as $filter) {
            $field = $filter['field'];
            $value = $filter['value'];

            if (!empty($value)) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);

        foreach ($sorts as $sort) {
            $field = $sort['field'];
            $direction = $sort['direction'];

            if (!empty($field) && !empty($direction)) {
                $query->orderBy($field, $direction);
            }
        }

        $members = $query->paginate($size, ['*'], 'page', $page);
        $transformedMembers = [];
        foreach ($members as $member) {
            $member = $this->_formatMember($member, $commonController);
            $transformedMembers[] = $member;
        }

        $totalPages = ceil($members->total() / $size);
        return response()->json($this->_formatCountResponse(
            $transformedMembers,
            $members->perPage(),
            $totalPages
        ));
    }
}
