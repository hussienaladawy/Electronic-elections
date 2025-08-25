@extends('layouts.app')

@section('title', 'إدارة الإشعارات')
@section('page-title', 'إدارة الإشعارات')
@section('page-description', 'عرض وإدارة جميع الإشعارات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">قائمة الإشعارات</h5>
                <div class="btn-group">
                    <a href="{{ route('assistant.notifications.markAllAsRead') }}" class="btn btn-sm btn-primary">تمييز الكل كمقروء</a>
                    <form action="{{ route('assistant.notifications.deleteAllRead') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف جميع الإشعارات المقروءة؟');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger ms-2">حذف جميع المقروءة</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @forelse($notifications as $notification)
                    <div class="alert alert-light border-start border-4 {{ $notification->read_at ? 'border-secondary' : 'border-primary' }} p-3 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0">
                                <strong>{{ $notification->data['message'] ?? 'New Notification' }}</strong>
                                <br>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                        <div>
                            @if(isset($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-info">عرض</a>
                            @endif
                            @unless($notification->read_at)
                                <a href="{{ route('assistant.notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-success ms-2">تمييز كمقروء</a>
                            @endunless
                            <form action="{{ route('assistant.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger ms-2">حذف</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">
                        لا توجد إشعارات لعرضها.
                    </div>
                @endforelse

                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection