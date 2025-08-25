@props(['notification'])

<a href="{{ $notification->link }}" class="text-reset notification-item">
    <div class="d-flex">
        <div class="flex-shrink-0 me-3">
            <div class="avatar-xs">
                <span class="avatar-title bg-primary rounded-circle font-size-16">
                    <i class="bx bx-bell"></i>
                </span>
            </div>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-1">{{ $notification->title }}</h6>
            <div class="font-size-12 text-muted">
                <p class="mb-1">{{ $notification->message }}</p>
                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</a>
