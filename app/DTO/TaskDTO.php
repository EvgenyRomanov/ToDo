<?php

namespace App\DTO;

use App\Models\User;

class TaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public int $completed,
        public User $user
    ) {
    }
}
