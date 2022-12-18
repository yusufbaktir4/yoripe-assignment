<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Hash;
use Auth;

class AuthController extends Controller
{
    use ApiTrait, ValidationTrait;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->UserValidation());

        if ($validator->fails()) {
            return $this->responseError([], 400, $validator->errors()->first());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseSuccess([
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 'success');
    }

    public function getToken(Request $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->responseError([], 401, 'Unauthorized');
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return  $this->responseSuccess([
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 'success');
    }

    // public function deleteToken()
    // {
    //     Auth::user()->tokens()->delete();
    //     return response()->json([
    //         'message' => 'logout success'
    //     ]);
    //     return  $this->responseSuccess([], 'delete successfully');
    // }
}
