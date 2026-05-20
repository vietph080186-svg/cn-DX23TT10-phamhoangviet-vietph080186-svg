@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
    <main class="container">
        <div class="page-header">
            <h1 class="page-title">Thông báo</h1>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button class="button" type="submit">Đánh dấu tất cả đã đọc</button>
            </form>
        </div>

        @include('admin.shared.messages')

        <table>
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Loại</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr style="{{ $notification->is_read ? '' : 'background:#f8fafc;font-weight:600;' }}">
                        <td>{{ $notification->title }}</td>
                        <td>{{ $notification->message }}</td>
                        <td>{{ $typeLabels[$notification->type] ?? 'Hệ thống' }}</td>
                        <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $notification->is_read ? 'Đã đọc' : 'Chưa đọc' }}</td>
                        <td class="actions">
                            @if ($notification->link)
                                <a class="button light" href="{{ url($notification->link) }}">Xem</a>
                            @endif

                            @if (! $notification->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="button secondary" type="submit">Đánh dấu đã đọc</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Chưa có thông báo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">{{ $notifications->links() }}</div>
    </main>
@endsection
