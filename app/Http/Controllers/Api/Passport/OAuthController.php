<?php

namespace App\Http\Controllers\Api\Passport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passport\AuthUserRequestOAuth;
use App\Http\Requests\Passport\RegisterUserRequestOAuth;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;

class OAuthController extends Controller
{
    public function register(RegisterUserRequestOAuth $request): JsonResponse
    {
        $data = $request->toArray();
        $data['password'] = bcrypt($data['password']);
        /** @var User $newUser */
        $newUser = User::query()->create($data);

        $success['token'] = $newUser->createToken('MyApp')->accessToken;
        $success['name'] = $newUser->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(AuthUserRequestOAuth $request, AuthManager $authManage): JsonResponse
    {
        if (
            $authManage
                ->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])
        ) {
            /** @var User $user */
            $user = $authManage->user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        }

        return response()->json([
            'data' => ['error' => 'Unauthenticated'],
            'message' => 'Unauthenticated.'
        ], 401);
    }

    protected function sendResponse(array $data, string $message): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ]);
    }
}
