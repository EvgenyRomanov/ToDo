<?php

namespace App\Http\Controllers\Api\JWT;

use App\Actions\AuthActionByUser;
use App\DTO\UserDTO;
use App\DTO\UserUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\JWT\UserRequestJWT;
use App\Http\Requests\JWT\UserUpdateRequestJWT;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserRepositoryInterface $userRepository): UserResourceCollection
    {
        return new UserResourceCollection($userRepository->getAllUsers());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequestJWT $request, UserService $userService): UserResource
    {
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
    public function show(User $user): UserResource
    {
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
