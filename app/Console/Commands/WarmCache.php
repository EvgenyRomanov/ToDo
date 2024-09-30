<?php

namespace App\Console\Commands;

use App\Models\Interfaces\TaskRepositoryInterface;
use App\Models\Interfaces\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmCache extends Command
{
    protected TaskRepositoryInterface $taskRepository;
    protected UserRepositoryInterface $userRepository;

    public function __construct(TaskRepositoryInterface $taskRepository, UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:warm-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Прогрев КЭШа';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('The command is running');

        $users = $this->userRepository->getAllUsersLazy();
        $bar = $this->output->createProgressBar(count($users));

        $bar->start();

        foreach ($users as $user) {
            Cache::set("tasks:index:{$user->id}", view("tasks/index", [
                'tasks' => $this->taskRepository->getAllUsersTasks($user->id)
            ])->render(), 60);

            $bar->advance();
        }

        $bar->finish();

        $this->line("");
        $this->info('The command was successful!');
    }
}
