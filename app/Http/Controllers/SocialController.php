<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function loginCallback()
    {
        try {
            // Gọi driver của Socialite cho Facebook
            $facebookUser = Socialite::driver('facebook')->user();
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
            // Trả về thông tin người dùng đã đăng nhập thành công
            return Response::json([
                'user' => new User($user),
                'facebook_user' => $facebookUser,
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return Response::json([
                'error' => 'Đã xảy ra lỗi trong quá trình đăng nhập bằng Facebook.',
            ], 500);
        }
    }
}
