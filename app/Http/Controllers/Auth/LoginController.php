<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ResponseFormattingTrait;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $response = $this->_formatBaseResponse(200, $user, 'Tạo tài khoản thành công');
            return response()->json($response);
        } else {
            $response = $this->_formatBaseResponse(400, null, 'Đăng nhập không thành công');
            return response()->json($response, 400);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }
}
