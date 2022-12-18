<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\ApiTrait;
use App\Traits\ValidationTrait;
use Hash;

class UsersController extends Controller
{
    use ApiTrait, ValidationTrait;
    protected $user;

    public function __construct()
    {
        $this->user = auth('sanctum')->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        return $this->responseSuccess([
                    'success' => true,
                    'data' => User::modifyValueDataUsers($users)
                ], 'Fetch all users');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->UserValidation());

        if ($validator->fails()) {
            return $this->responseError([
                'success' => false,
                'data' => []
            ], 400, $validator->errors()->first());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return  $this->responseSuccess([
                    'success' => true,
                    'data' => User::modifyValueDataUsers($user)
                ], 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($user)
    {
        $users = User::find($user);

        $msg = "Data user found";
        if(empty($users) || is_null($users)) {
            $msg = "Data user not found";
            $users = [];
        }

        return  $this->responseSuccess([
                    'success' => true,
                    'data' => User::modifyValueDataUsers($users)
                ], $msg);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $user)
    {
        $users = User::find($user);

        if(empty($users) || is_null($users)) {
            return $this->responseError([
                'success' => false,
                'data' => []
            ], 400, "Data user not found");
        } else {
            $validator = Validator::make($request->all(), $this->UserValidation());

            if ($validator->fails()) {
                return $this->responseError([
                    'success' => false,
                    'data' => []
                ], 400, $validator->errors()->first());
            }

            $users->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            return  $this->responseSuccess([
                        'success' => true,
                        'data' => User::modifyValueDataUsers($users)
                    ], 'User updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($user)
    {
        $users = User::find($user);

        if(empty($users) || is_null($users)) {
            return $this->responseError([
                'success' => false,
                'data' => []
            ], 400, "Data user not found");
        } else {
            $users->delete();

            return  $this->responseSuccess([
                        'success' => true,
                        'data' => []
                    ], 'User deleted successfully.');
        }
    }
}
