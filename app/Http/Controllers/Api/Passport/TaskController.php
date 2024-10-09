<?php

namespace App\Http\Controllers\Api\Passport;

use App\DTO\TaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyTaskRequest;
use App\Http\Requests\ShowTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskResourceCollection;
use App\Models\Interfaces\TaskRepositoryInterface;
use App\Models\Interfaces\UserRepositoryInterface;
use App\Models\Task;
use App\Repositories\UserRepository;
use App\Services\TaskService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private const TASK_COMPLETED = "on";

    /**
     * Display a listing of the resource.
     */
    public function index(TaskRepositoryInterface $taskRepository, AuthManager $authManager): TaskResourceCollection
    {
        $userId = $authManager->id();
        return new TaskResourceCollection($taskRepository->getAllUsersTasks($userId));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        TaskRequest $request,
        TaskService $taskService,
        UserRepository $userRepository,
        AuthManager $authManager
    ): TaskResource {
        $user = $userRepository->findUser($authManager->id());
        $newTaskDTO = new TaskDTO(
            $request->get('title'),
            $request->get('description'),
            0,
            $user
        );
        $task = $taskService->create($newTaskDTO);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowTaskRequest $request, Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        TaskRequest $request,
        Task $task,
        TaskService $taskService,
        UserRepositoryInterface $userRepository
    ): TaskResource {
        $user = $userRepository->findUser($task->user_id);
        $taskDTO = new TaskDTO(
            $request->get('title'),
            $request->get('description'),
            $request->completed == self::TASK_COMPLETED ? 1 : 0,
            $user
        );
        $taskService->update($task, $taskDTO);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DestroyTaskRequest $request,
        Task $task,
        TaskService $taskService
    ): JsonResponse {
        $taskService->delete($task->id);

        return response()->json(null, 204);
    }
}
