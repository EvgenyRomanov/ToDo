<?php

namespace App\Models\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

interface UserRepositoryInterface
{
    public function findUser(int $userId): ?User;

    public function getAllUsersLazy(): LazyCollection;

    public function getAllUsers(): Collection;
}
