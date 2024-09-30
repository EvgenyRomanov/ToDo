<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\DTO\UserUpdateDTO;
use App\Models\User;

class UserService
{
    public function create(UserDTO $userDTO): User
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => bcrypt($userDTO->password),
        ]);

        return $user;
    }

    public function delete(int $userId): int
    {
        return User::destroy($userId);
    }

    public function update(User $user, UserUpdateDTO $userUpdateDTO): bool
    {
        return $user->update([
            'name' => $userUpdateDTO->name ?? $user->name,
            'email' => $userUpdateDTO->email ?? $user->email,
            'password' => $userUpdateDTO->password ? bcrypt($userUpdateDTO->password) : $user->password,
        ]);
    }
}
