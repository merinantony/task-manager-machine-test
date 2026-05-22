<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'priority', 'status', 
        'due_date', 'assigned_to', 'ai_summary', 'ai_priority'
    ];

    // A task belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
