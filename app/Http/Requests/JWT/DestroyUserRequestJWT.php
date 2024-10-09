<?php

namespace App\Http\Requests\JWT;

use Illuminate\Foundation\Http\FormRequest;

class DestroyUserRequestJWT extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->route()->parameter('user')->id === $this->user()->id;;
    }
}
