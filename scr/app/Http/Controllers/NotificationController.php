<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private array $typeLabels = [
        'task_assigned' => 'Giao việc',
        'task_submitted' => 'Chờ duyệt',
        'task_completed' => 'Hoàn thành',
        'task_revision' => 'Cần sửa lại',
        'task_commented' => 'Bình luận',
        'system' => 'Hệ thống',
    ];

    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
            'typeLabels' => $this->typeLabels,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403, 'Bạn không có quyền cập nhật thông báo này.');

        if (! $notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return back()->with('success', 'Đã đánh dấu thông báo là đã đọc.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }
}
