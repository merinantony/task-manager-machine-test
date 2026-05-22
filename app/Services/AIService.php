<?php

// app/Services/AIService.php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    public function generateSummary(Task $task): array
    {
        $apiKey = env('OPENAI_API_KEY');

        // Mock fallback if no API key is set in .env
        if (!$apiKey) {
            return $this->mockResponse($task);
        }

        try {
            $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'You are an AI assistant for a task management system. Summarize the task description and assign an AI priority (low, medium, high). Return ONLY a valid JSON object exactly like this: {"ai_summary": "...", "ai_priority": "..."}'
                    ],
                    [
                        'role' => 'user', 
                        'content' => "Task Title: {$task->title}\nTask Description: {$task->description}"
                    ]
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                return json_decode($content, true) ?? $this->mockResponse($task);
            }
            
            return $this->mockResponse($task);

        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            return $this->mockResponse($task);
        }
    }

    private function mockResponse(Task $task): array
    {
        return [
            'ai_summary' => "Automated mock summary for: {$task->title}. This bypasses the real API call.",
            'ai_priority' => 'high'
        ];
    }
}