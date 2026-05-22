<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Protect all routes with Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // The exact endpoints requested by the machine test
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus']); // We will add this to the controller later
    Route::get('/tasks/{id}/ai-summary', [TaskController::class, 'getAiSummary']); // We will add this to the controller later
});