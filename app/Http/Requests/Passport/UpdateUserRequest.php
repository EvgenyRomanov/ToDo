<?php

namespace App\Http\Requests\Passport;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->route()->parameter('user')->id === $this->user()->id;
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
