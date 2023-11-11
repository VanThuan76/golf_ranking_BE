<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Traits\MemberFormattingTrait;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Carbon;

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
    public function getListNationality()
    {
        $nationalities = Member::pluck('nationality')->unique()->values()->all();

        $response = $this->_formatBaseResponse(200, $nationalities, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
    public function checkHandicapVgaExists(Request $request)
    {
        $handicapVga = $request->input('handicapVga');
        $member = Member::where('handicap_vga', $handicapVga)->first();
        if ($member) {
            $response = $this->_formatBaseResponse(400, [], 'Mã handicap VGA đã tồn tại');
            return response()->json($response);
        } else {
            $response = $this->_formatBaseResponse(200, [], 'Mã handicap VGA chưa tồn tại');
            return response()->json($response);
        }
    }

    public function searchMember(Request $request, UtilsCommonHelper $commonController)
    {
        $query = Member::query()->orderBy("current_rank", "asc");

        $filters = $request->input('filters', []);
        
        foreach ($filters as $filter) {
            $field = $filter['field'];
            $value = $filter['value'];
        
            if (!empty($value)) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);
        
        foreach ($sorts as $sort) {
            $field = $sort['field'];
            $direction = $sort['direction'];
        
            if (!empty($field) && !empty($direction)) {
                $query->orderBy($field, $direction);
            }
        }
        
        $members = $query->paginate($size);
        $transformedMembers = [];
        foreach ($members as $member) {
            $member = $this->_formatMember($member, $commonController);
            $transformedMembers[] = $member;
        }
        
        $totalPages = $members->lastPage();
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
            'vjgr_code' => 'required|string|max:255',
            'handicap_vga' => 'nullable|string|max:255',
            'gender' => 'required|int',
            'date_of_birth' => 'required|date_format:d/m/Y H:i:s|max:255',
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
        $member->vjgr_code = $validatedData['vjgr_code'];
        $member->handicap_vga = $validatedData['handicap_vga'];
        $member->gender = $validatedData['gender'];
        $member->date_of_birth = Carbon::createFromFormat('d/m/Y H:i:s', $validatedData['date_of_birth'])->format('Y-m-d H:i:s');
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
    public function updateMember(Request $request, UtilsCommonHelper $commonController, $memberId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'vjgr_code' => 'required|string|max:255',
            'handicap_vga' => 'nullable|string|max:255',
            'gender' => 'required|int',
            'date_of_birth' => 'required|date_format:d/m/Y H:i:s|max:255',
            'nationality' => 'required|string|max:255',
            'email' => 'nullable|email|unique:member,email,' . $memberId . '|max:255',
            'phone_number' => 'required|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'guardian_email' => 'required|email|unique:member,email,' . $memberId . '|max:255',
            'guardian_phone' => 'required|string|max:255',
        ]);

        $member = Member::find($memberId);

        if (!$member) {
            $response = $this->_formatBaseResponse(404, null, 'Không tìm thấy thành viên');
            return response()->json($response);
        }

        $member->name = $validatedData['name'];
        $member->vjgr_code = $validatedData['vjgr_code'];
        $member->handicap_vga = $validatedData['handicap_vga'];
        $member->gender = $validatedData['gender'];
        $member->date_of_birth = Carbon::createFromFormat('d/m/Y H:i:s', $validatedData['date_of_birth'])->format('Y-m-d H:i:s');
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

        $updatedMember = $this->_formatMember($member, $commonController);
        $response = $this->_formatBaseResponse(200, $updatedMember, 'Cập nhật thành viên thành công');
        return response()->json($response);
    }
    public function checkCodeVjgrExists(Request $request)
    {
        $codeVjgr = $request->input('vjgr_code');
        $member = Member::where('vjgr_code', $codeVjgr)->first();
        if ($member) {
            $response = $this->_formatBaseResponse(400, [], 'Mã VJGR không tồn tại');
            return response()->json($response);
        } else {
            $response = $this->_formatBaseResponse(200, [], 'Mã VJGR đã được đăng ký');
            return response()->json($response);
        }
    }
}
