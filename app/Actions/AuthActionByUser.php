<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Auth\AuthManager;

class AuthActionByUser
{
    public function __invoke(User $user, AuthManager $authManager): void
    {
        /** @var User|null $authUser */
        $authUser = $authManager->user();
        if ($authUser && $authUser->isAdmin()) return;

        if ($user->id !== $authManager->id()) abort(403, "Unauthorized Action");
    }
}
