<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Traits\MemberFormattingTrait;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    public function registerMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'handicap_vga' => 'required|string|max:255',
            'gender' => 'required|int',
            'date_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email|max:255',
            'phone_number' => 'required|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'guardian_email' => 'required|email|max:255',
            'guardian_phone' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 400);
        }

        $member = new Member();
        $member->name = $request->input('name');
        $member->handicap_vga = $request->input('handicap_vga');
        $member->gender = $request->input('gender');
        $member->date_of_birth = $request->input('date_of_birth');
        $member->nationality = $request->input('nationality');
        $member->email = $request->input('email');
        $member->phone_number = $request->input('phone_number');
        $member->guardian_name = $request->input('guardian_name');
        $member->relationship = $request->input('relationship');
        $member->guardian_email = $request->input('guardian_email');
        $member->guardian_phone = $request->input('guardian_phone');

        $member->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Tạo thành viên thành công',
            'data' => [
                'member' => $member,
            ],
        ], 201);
    }
}
