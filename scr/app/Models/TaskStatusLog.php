<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatusLog extends Model
{
    protected $fillable = [
        'task_id',
        'changed_by',
        'user_id',
        'old_status',
        'new_status',
        'note',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
