<?php

namespace App\Http\Controllers\Api\Passport;

use App\DTO\UserDTO;
use App\DTO\UserUpdateDTO;
use App\Http\Requests\Passport\DestroyUserRequest;
use App\Http\Requests\Passport\IndexUserRequest;
use App\Http\Requests\Passport\ShowUserRequest;
use App\Http\Requests\Passport\StoreUserRequest;
use App\Http\Requests\Passport\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        IndexUserRequest $request,
        UserRepositoryInterface $userRepository,
    ): UserResourceCollection {
        return new UserResourceCollection($userRepository->getAllUsers());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreUserRequest $request,
        UserService $userService
    ): UserResource {
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
    public function show(ShowUserRequest $request, User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UserService $userService
    ): UserResource {
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
        DestroyUserRequest $request,
        User $user,
        UserService $userService
    ): JsonResponse{
        $userService->delete($user->id);

        return response()->json(null, 204);
    }
}
