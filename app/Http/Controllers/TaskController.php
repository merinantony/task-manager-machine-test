<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskStatusRequest;
use App\Http\Resources\TaskResource;
use Inertia\Inertia;
use App\Models\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    protected $taskService;
    protected $taskRepo;

    public function __construct(TaskService $taskService, TaskRepositoryInterface $taskRepo)
    {
        $this->taskService = $taskService;
        $this->taskRepo = $taskRepo;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'priority']);
        
        // ROLE ENFORCEMENT: If regular user, force filter by their ID
        if ($request->user()->role !== 'admin') {
            $filters['assigned_to'] = $request->user()->id;
        }

        $tasks = $this->taskRepo->all($filters);

        // Calculate monthly completion statistics for the last 4 months
        $monthlyStats = [];
        $months = [];
        for ($i = 3; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M');
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $totalTasks = Task::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $completedTasks = Task::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('status', 'completed')
                ->count();
            
            $completionPercent = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
            
            $months[] = $month;
            $monthlyStats[] = round($completionPercent);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($tasks);
        }

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'monthlyStats' => $monthlyStats,
            'monthLabels' => $months
        ]);
    }

    public function create(Request $request)
    {
        // POLICY CHECK: Prevent users from directly accessing /tasks/create URL
        $this->authorize('create', \App\Models\Task::class);

        $users = \App\Models\User::select('id', 'name')->get();
        return Inertia::render('Tasks/Create', ['users' => $users]);
    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        // Default status for new tasks
        $validated['status'] = 'pending';

        // Save via the Service layer
        $task = $this->taskService->store($validated);

        // If it's an API request, return JSON with an API resource
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Task created successfully',
                'task' => new TaskResource($task),
            ], 201);
        }

        // If it's from the Vue UI, redirect back to the task list
        return redirect()->route('tasks.index');
    }

    // Display the Task Detail + AI Summary Page
    public function show($id)
    {
        $task = $this->taskRepo->find($id);
        $task->load('user'); // Load the assigned user relationship
        
        return Inertia::render('Tasks/Show', [
            'task' => $task
        ]);
    }

    // Display the Edit Form
    public function edit(Request $request, $id)
    {
        $task = $this->taskRepo->find($id);
        
        // POLICY CHECK: Can this user edit this specific task?
        $this->authorize('update', $task);

        $users = \App\Models\User::select('id', 'name')->get();

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'users' => $users
        ]);
    }

    // Process the Update
    public function update(TaskRequest $request, $id)
    {
        $task = $this->taskRepo->find($id);
        $this->authorize('update', $task);

        $validated = $request->validated();

        $task = $this->taskRepo->update($id, $validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Task updated successfully',
                'task' => new TaskResource($task),
            ], 200);
        }

        return redirect()->route('tasks.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = $this->taskRepo->find($id);
        
        // Ensure only authorized users can change the status
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $this->taskRepo->update($id, ['status' => $validated['status']]);

        // If it's an API request, return JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Status updated successfully', 'task' => $this->taskRepo->find($id)]);
        }

        // Otherwise, stay on the current page
        return redirect()->back();
    }

    // AI Summary Endpoint Requirement
    public function getAiSummary(Request $request, $id)
    {
        $task = $this->taskRepo->find($id);
        
        // Trigger the AI Service again
        $aiData = $this->taskService->aiService->generateSummary($task);
        $task = $this->taskRepo->update($task->id, $aiData);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'AI summary refreshed',
                'task' => new TaskResource($task),
            ], 200);
        }

        return redirect()->back();
    }
}
