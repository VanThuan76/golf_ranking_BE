<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Http\Models\User;
use App\Traits\MemberFormattingTrait;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseFormattingTrait, MemberFormattingTrait;

    public function checkEmailExists(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            $response = $this->_formatBaseResponse(400, [], 'Địa chỉ email đã tồn tại');
            return response()->json($response);
        } else {
            $response = $this->_formatBaseResponse(200, [], 'Địa chỉ email chưa tồn tại');
            return response()->json($response);
        }
    }

    public function getById($id, UtilsCommonHelper $commonController)
    {
        $user = User::find($id);

        if (!$user) {
            $response = $this->_formatBaseResponse(404, null, 'Người dùng không được tìm thấy');
            return response()->json($response, 404);
        }

        if ($user->member_id > 0) {
            $member = Member::find($user->member_id);

            if ($member) {
                $transformedMember = $this->_formatMember($member, $commonController);
            }
        } else {
            $transformedMember = null;
        }

        $transformedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $response = $this->_formatBaseResponse(200, [
            'member' => $transformedMember,
            'user' => $transformedUser,
        ], 'Lấy dữ liệu thành công');

        return response()->json($response);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:22'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:22'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        $verify = 0;
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->avatar = $request->avatar;
        $user->birthdate = date("Y-m-d", strtotime($request->birthdate));
        if ($user->name && $user->phone && $user->birthdate) {
            if ($user->verify == 0) {
                if ($user->package_type == 1) {
                    $user->expire_time = Carbon::parse($user->expire_time)->addMonths(3);
                } else {
                    $user->package_type = 1;
                    $user->expire_time = Carbon::now()->addMonths(3);
                }
                $verify = 1;
                $user->verify = 1;
            }
        }
        if ($request->password != '******') {
            $user->password = Hash::make($request->password);
        }
        return response()->json([
            'user' => $user,
            'verify' => $verify
        ]);
    }
}
