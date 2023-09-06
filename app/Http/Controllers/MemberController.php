<?php

namespace App\Http\Controllers;

use App\Http\Models\Member;
use App\Admin\Controllers\UtilsCommonHelper;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    use ResponseFormattingTrait;

    private function transformedMemberData($members, UtilsCommonHelper $commonController)
    {
        return $members->map(function ($member) use ($commonController) {
            $member->gender = $commonController->commonCodeGridFormatter('Gender', 'description_vi', $member->gender);
            $member->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $member->status);
            return $member;
        });
    }

    public function getList(Request $request, UtilsCommonHelper $commonController)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);

        $members = Member::orderBy($sorts[0]['field'], $sorts[0]['direction'])
            ->paginate($size, ['*'], 'page', $page);
        $transformedMembers = $this->transformedMemberData($members->getCollection(), $commonController);

        return response()->json($this->_formatCountResponse(
            $transformedMembers,
            $members->perPage(),
            $members->total()
        ));
    }

    public function getById($id, UtilsCommonHelper $commonController)
    {
        $member = Member::find($id);
        if (!$member) {
            $response = $this->_formatBaseResponse(404, null, 'Giải đấu không được tìm thấy');
            return response()->json($response, 404);
        }

        $transformedMembers = $this->transformedMemberData(collect([$member]), $commonController)->first();

        $response = $this->_formatBaseResponse(200, $transformedMembers, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
    public function searchMember(Request $request, UtilsCommonHelper $commonController)
    {
        $query = Member::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('vjgr_code')) {
            $query->orWhere('vjgr_code', 'like', '%' . $request->input('vjgr_code') . '%');
        }

        if ($request->has('nationality')) {
            $query->orWhere('nationality', 'like', '%' . $request->input('nationality') . '%');
        }

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);


        $members = $query->orderBy($sorts[0]['field'], $sorts[0]['direction'])
            ->paginate($size, ['*'], 'page', $page);

        $transformedMembers = $this->transformedMemberData(collect([$members]), $commonController)->first();


        return response()->json($this->_formatCountResponse(
            $transformedMembers,
            $members->perPage(),
            $members->total()
        ));
    }
}
