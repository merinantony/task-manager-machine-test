<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TaskService
{
    protected $taskRepo;
    public $aiService;

    public function __construct(TaskRepositoryInterface $taskRepo, AIService $aiService)
    {
        $this->taskRepo = $taskRepo;
        $this->aiService = $aiService;
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the task via Repository
            $task = $this->taskRepo->create($data);
            
            // 2. Trigger AI processing (can be moved to a Job queue later for the bonus)
            $aiData = $this->aiService->generateSummary($task);
            
            // 3. Update the task with AI data via Repository
            $this->taskRepo->update($task->id, $aiData);
            
            return $this->taskRepo->find($task->id); // Return fresh instance
        });
    }
}