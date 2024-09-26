<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserCreated;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    private $secretKey = "qQKPjndxljuYQi/POiXJa8O19nVO/vTf/DpXO541g=qQKPjndxljuYQi/POiXJa8O19nVO/vTf/DpXO541g=";

    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $fields = $request->all();
        $user = User::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'isValidEmail' => User::IS_INVALID_EMAIL,
            'remember_token' => generateRandomCode(),
        ]);
        NewUserCreated::dispatch($user);
        return response(['message' => 'User Created', 'user' => $user], 200);
    }

//    public function generateRandomCode()
//    {
//        $code = Str::random(10) . time();
//        return $code;
//    }

    public function validEmail($token)
    {
        User::where('remember_token', $token)->update(['isValidEmail' => User::IS_VALID_EMAIL]);
        return redirect('/login');
    }


    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $fields = $request->all();
        $user = User::where('email', $fields['email'])->first();
        if (!is_null($user)) {

            if (intval($user->isValidEmail) !== User::IS_VALID_EMAIL) {
                NewUserCreated::dispatch($user);
                return response([
                    'message' => 'We send you an email verification !',
                    'isLoggedIn' => false
                ], 422);
            }
        }
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Email and password invalid'], 422);
        }
        $token = $user->createToken($this->secretKey)->plainTextToken;
        return response(
            [
                'user' => $user,
                'message' => 'User login successfully',
                'token' => $token,
                'isLoggedIn' => true
            ],
            200
        );
    }
}
