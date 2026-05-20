<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'task_category_id',
        'creator_id',
        'assignee_id',
        'title',
        'description',
        'status',
        'priority',
        'start_date',
        'due_date',
        'completed_at',
        'result_note',
        'result_link',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(TaskStatusLog::class)->latest();
    }
}
