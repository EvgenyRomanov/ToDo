<?php

namespace App\DTO;

use App\Models\User;

class UserUpdateDTO
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $password,
    ) {
    }
}
