<?php

namespace App\Http\Requests\JWT;

use Illuminate\Foundation\Http\FormRequest;

class UserRequestJWT extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string"],
            "email" => ["required", "email", "unique:users"],
            "password" => ["required", "min:6"],
        ];
    }
}
