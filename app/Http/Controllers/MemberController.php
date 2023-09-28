<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Traits\MemberFormattingTrait;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use App\Http\Models\User;

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
        $query = Member::where('status', 1);
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
    public function registerMember(Request $request, UtilsCommonHelper $commonController)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|int',
            'name' => 'required|string|max:255',
            'handicap_vga' => 'nullable|string|max:255',
            'gender' => 'required|int',
            'date_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'email' => 'nullable|email|unique:member,email|max:255',
            'phone_number' => 'required|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'guardian_email' => 'required|email|unique:member,email|max:255',
            'guardian_phone' => 'required|string|max:255',
        ]);

        $member = new Member();
        $member->name = $validatedData['name'];
        $member->handicap_vga = $validatedData['handicap_vga'];
        $member->gender = $validatedData['gender'];
        $member->date_of_birth = $validatedData['date_of_birth'];
        $member->nationality = $validatedData['nationality'];
        $member->email = $validatedData['email'];
        $member->phone_number = $validatedData['phone_number'];
        $member->guardian_name = $validatedData['guardian_name'];
        $member->relationship = $validatedData['relationship'];
        $member->guardian_email = $validatedData['guardian_email'];
        $member->guardian_phone = $validatedData['guardian_phone'];
        if (isset($validatedData['handicap_vga'])) {
            $member->handicap_vga = $validatedData['handicap_vga'];
        }
        if (isset($validatedData['guardian_email'])) {
            $member->email = $validatedData['guardian_email'];
        }
        $member->save();

        $memberId = $member->id;
            $user = User::find($validatedData['user_id']);
            if ($user) {
                $user->member_id = $memberId;
                $user->save();
            }

        $member = $this->_formatMember($member, $commonController);
        $response = $this->_formatBaseResponse(200, $member, 'Tạo thành viên thành công');
        return response()->json($response);
    }
}