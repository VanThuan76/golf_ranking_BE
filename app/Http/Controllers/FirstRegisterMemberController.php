<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Register;
use App\Traits\MemberFormattingTrait;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Carbon;

class FirstRegisterMemberController extends Controller
{
    use ResponseFormattingTrait, MemberFormattingTrait;

    public function getById($id, UtilsCommonHelper $commonController)
    {
        $member = Register::find($id);
        if (!$member) {
            $response = $this->_formatBaseResponse(404, null, 'Thành viên không được tìm thấy');
            return response()->json($response, 404);
        }

        $transformedMember = $this->_formatMember($member, $commonController);

        $response = $this->_formatBaseResponse(200, $transformedMember, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public function firstRegisterMember(Request $request, UtilsCommonHelper $commonController)
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

        $member = new Register();
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
        $member = $this->_formatMember($member, $commonController);
        $response = $this->_formatBaseResponse(200, $member, 'Tạo thành viên thành công');
        return response()->json($response);
    }
    public function firstUpdateRegisterMember(Request $request, UtilsCommonHelper $commonController, $memberId)
    {
        $member = Register::find($memberId);

        if (!$member) {
            $response = $this->_formatBaseResponse(404, null, 'Không tìm thấy thành viên');
            return response()->json($response);
        }

        $member->name = $request->input('name');
        $member->vjgr_code =$request->input('vjgr_code');
        $member->handicap_vga =$request->input('handicap_vga');
        $member->gender =$request->input('gender');
        $member->date_of_birth = Carbon::createFromFormat('d/m/Y H:i:s',$request->input('date_of_birth'))->format('Y-m-d H:i:s');
        $member->nationality =$request->input('nationality');
        $member->email =$request->input('email');
        $member->phone_number =$request->input('phone_number');
        $member->guardian_name =$request->input('guardian_name');
        $member->relationship =$request->input('relationship');
        $member->guardian_email =$request->input('guardian_email');
        $member->guardian_phone =$request->input('guardian_phone');

        if (isset($validatedData['handicap_vga'])) {
            $member->handicap_vga =$request->input('handicap_vga');
        }
        if (isset($validatedData['guardian_email'])) {
            $member->email =$request->input('guardian_email');
        }

        $member->save();

        $updatedMember = $this->_formatMember($member, $commonController);
        $response = $this->_formatBaseResponse(200, $updatedMember, 'Cập nhật thành viên thành công');
        return response()->json($response);
    }
    public function getByRegisterMember(Request $request, UtilsCommonHelper $commonController)
    {
        $user = User::where('email', $request->input("email"))->first();
        $registerMember = Register::where('email', $request->input("email"))->get();
        if (!$registerMember) {
            $response = $this->_formatBaseResponse(404, null, 'Đăng ký không được tìm thấy');
            return response()->json($response, 404);
        }

        $transformedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
        $transformedMember = $this->_formatMember($registerMember, $commonController);

        $response = $this->_formatBaseResponse(200, [
            'member' => $transformedMember,
            'user' => $transformedUser,
        ], 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
