<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use App\Models\User;

trait ApiTrait
{
    protected function responseSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function responseError($data, int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function isNormalUser($user): bool
    {
        if (!empty($user)) {
            return $user->role == User::NORMAL_USER;
        }

        return false;
    }

    protected function isManager($user): bool
    {

        if (!empty($user)) {
            return $user->role == User::MANAGER;
        }

        return false;
    }

    protected function isAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->role == User::ADMIN;
        }

        return false;
    }
}