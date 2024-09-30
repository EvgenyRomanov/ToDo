<?php

namespace App\Http\Requests\JWT;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequestJWT extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["string"],
            "email" => ["email", "unique:users"],
            "password" => ["min:6"],
        ];
    }
}
