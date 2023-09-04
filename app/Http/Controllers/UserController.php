<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function updateAvatar(Request $request)
    {
        if ($files = $request->file('avatar')) {
            $file = Storage::disk('s3')->put('images/avatar', $request->avatar, 'public');
            return Response()->json([
                "success" => true,
                "file" => env('AWS_URL') . $file,
                "path" => $file
            ]);
        }

        return Response()->json([
            "success" => false,
            "file" => ''
        ]);
    }
}
