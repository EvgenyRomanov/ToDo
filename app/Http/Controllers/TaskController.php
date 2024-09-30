<?php

namespace App\Http\Controllers;

use App\Actions\AuthAction;
use App\DTO\TaskDTO;
use App\Http\Requests\TaskRequest;
use App\Models\Interfaces\TaskRepositoryInterface;
use App\Models\Interfaces\UserRepositoryInterface;
use App\Models\Task;
use App\Repositories\UserRepository;
use App\Services\TaskService;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    private const TASK_COMPLETED = "on";

    public function index(TaskRepositoryInterface $taskRepository,  AuthManager $authManager): View|string
    {
        $userId = $authManager->id();

        return Cache::remember("tasks:index:{$userId}", 60, function () use ($taskRepository, $userId) {
            return view("tasks/index", [
                'tasks' => $taskRepository->getAllUsersTasks($userId)
            ])->render();
        });
    }

    public function create(): View
    {
        return view("tasks/create");
    }

    public function store(
        TaskRequest $request,
        TaskService $taskService,
        UserRepository $userRepository,
        AuthManager $authManager
    ): RedirectResponse {
        $user = $userRepository->findUser($authManager->id());
        $newTaskDTO = new TaskDTO(
            $request->get('title'),
            $request->get('description'),
            0,
            $user
        );
        $taskService->create($newTaskDTO);

        return redirect('/tasks')->with('message', trans('flush.create'));
    }

    public function show(Task $task, AuthAction $authAction, AuthManager $authManager): View|string
    {
        $authAction($task, $authManager);

        return Cache::remember("tasks:view:{$task->id}", 60, function () use ($task) {
            return view('tasks/view', [
                'task' => $task
            ])->render();
        });
    }

    public function edit(Task $task, AuthAction $authAction, AuthManager $authManager): View
    {
        $authAction($task, $authManager);

        return view("tasks/edit", [
            'task' => $task
        ]);
    }

    public function update(
        TaskRequest $request,
        Task $task,
        TaskService $taskService,
        UserRepositoryInterface $userRepository,
        AuthAction $authAction,
        AuthManager $authManager
    ): RedirectResponse {
        $authAction($task, $authManager);
        $user = $userRepository->findUser($task->user_id);
        $taskDTO = new TaskDTO(
            $request->get('title'),
            $request->get('description'),
            $request->completed == self::TASK_COMPLETED ? 1 : 0,
            $user
        );
        $taskService->update($task, $taskDTO);

        return redirect('/tasks')->with('message', trans('flush.update'));
    }

    public function destroy(Task $task, TaskService $taskService, AuthAction $authAction, AuthManager $authManager): RedirectResponse
    {
        $authAction($task, $authManager);
        $taskService->delete($task->id);

        return redirect('/tasks')->with('message', trans('flush.delete'));
    }
}
