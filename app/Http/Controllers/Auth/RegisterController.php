<?php

namespace App\Http\Controllers\Auth;

use App\Http\Models\User;
use App\Traits\ResponseFormattingTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use ResponseFormattingTrait;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        if ($data['password'] != $data['password_confirmation']) {
            $validator->after(function ($validator) {
                $validator->errors()->add('password_confirmation', 'Mật khẩu xác nhận không giống với mật khẩu.');
            });
        }
        return $validator;
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(60),
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $response = $this->_formatBaseResponse(400, null, $validator->errors());
            return response()->json($response, 400);
        }

        $user = $this->create($request->all());

        $response = $this->_formatBaseResponse(200, $user, 'Tạo tài khoản thành công');
        return response()->json($response);
    }
}