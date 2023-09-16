<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
            // Kiểm tra xem email đã tồn tại trong hệ thống chưa
            $user = User::where('email', $facebookUser->getEmail())->first();
            // Nếu người dùng chưa tồn tại, tạo mới
            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    // Thêm các trường khác tùy ý
                ]);
            }
            // Đăng nhập người dùng
            Auth::login($user);
            return response()->json([
                'message' => 'Đăng nhập và đăng ký thành công.',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Đã xảy ra lỗi trong quá trình đăng nhập bằng Facebook.',
            ], 500);
        }
    }
}
