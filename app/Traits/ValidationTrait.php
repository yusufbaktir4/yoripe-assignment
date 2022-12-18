<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ValidationTrait
{
    protected function PostValidation() {
        return [
            'title' => 'required|string|max:155',
            'content' => 'required',
            'status' => 'required'
        ];
    }

    protected function UserValidation() {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|integer'
        ];
    }
}