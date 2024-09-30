<?php

namespace App\Http\Controllers\Api\Passport;

use App\Actions\AuthActionByUser;
use App\Actions\AuthActionByUserAdmin;
use App\DTO\UserDTO;
use App\DTO\UserUpdateDTO;
use App\Http\Requests\JWT\UserRequestJWT;
use App\Http\Requests\JWT\UserUpdateRequestJWT;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        UserRepositoryInterface $userRepository,
        AuthManager $authManager,
        AuthActionByUserAdmin $authActionByUserAdmin
    ): UserResourceCollection {
        $authActionByUserAdmin($authManager);
        return new UserResourceCollection($userRepository->getAllUsers());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        UserRequestJWT $request,
        UserService $userService,
        AuthManager $authManager,
        AuthActionByUserAdmin $authActionByUserAdmin
    ): UserResource {
        $authActionByUserAdmin($authManager);
        $userDTO = new UserDTO(
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password'),
        );
        $newUser = $userService->create($userDTO);

        return new UserResource($newUser);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, AuthManager $authManager, AuthActionByUser $authAction): UserResource
    {
        $authAction($user, $authManager);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UserUpdateRequestJWT $request,
        User $user,
        UserService $userService,
        AuthManager $authManager,
        AuthActionByUser $authAction
    ): UserResource {
        $authAction($user, $authManager);
        $userDTO = new UserUpdateDTO(
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password'),
        );
        $userService->update($user, $userDTO);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        User $user,
        UserService $userService,
        AuthManager $authManager,
        AuthActionByUser $authAction
    ): JsonResponse{
        $authAction($user, $authManager);
        $userService->delete($user->id);

        return response()->json(null, 204);
    }
}
