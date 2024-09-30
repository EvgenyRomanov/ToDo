<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Auth\AuthManager;

class AuthActionByUserAdmin
{
    public function __invoke(AuthManager $authManager): void
    {
        /** @var User|null $user */
        $user = $authManager->user();
        if ($user && $user->isAdmin()) return;

        abort(403, "Forbidden action");
    }
}
