<?php

namespace App\Repositories;

use App\Models\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

class UserRepository implements UserRepositoryInterface
{
    public function findUser(int $userId): ?User
    {
        return User::find($userId);
    }

    public function getAllUsersLazy(): LazyCollection
    {
        return User::all()->lazy();
    }

    public function getAllUsers(): Collection
    {
        return User::all();
    }
}
