@extends('layout.backend.backend')

@section('content')
<div class="content">
    @include('layout.backend.partials.message')

    <div class="block block-rounded p-3">
        <div class="block-header block-header-default d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h3 class="block-title mb-0">
                <i class="mdi mdi-bell-outline me-2"></i>Thông báo
            </h3>
            @if(($unreadCount ?? 0) > 0)
                <form action="{{ route($adminPrefix . '.notifications.read_all') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-check-all me-1"></i>Đánh dấu đã đọc tất cả
                    </button>
                </form>
            @endif
        </div>
        <div class="block-content block-content-full">
            <div class="list-group list-group-flush">
            @forelse($notifications as $n)
                @php
                    $payload = is_array($n->data) ? $n->data : [];
                    $nTitle = $payload['title'] ?? 'Thông báo';
                    $nBody = $payload['body'] ?? '';
                    $isUnread = $n->read_at === null;
                @endphp
                <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 py-3 {{ $isUnread ? 'border-start border-3 border-primary' : '' }}">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h6 class="mb-0">{{ $nTitle }}</h6>
                            @if($isUnread)
                                <span class="badge bg-danger">Chưa đọc</span>
                            @else
                                <span class="badge bg-secondary">Đã đọc</span>
                            @endif
                        </div>
                        <p class="text-muted mb-1">{{ $nBody }}</p>
                        <small class="text-muted">{{ $n->created_at?->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="d-flex flex-shrink-0 gap-2 align-items-center">
                        @if($isUnread)
                            <form action="{{ route($adminPrefix . '.notifications.read', $n->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Đã đọc</button>
                            </form>
                        @endif
                        <a href="{{ route($adminPrefix . '.notifications.open', $n->id) }}" class="btn btn-sm btn-primary">
                            Mở
                        </a>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center text-muted py-5 border-0">
                    <i class="mdi mdi-bell-off-outline" style="font-size: 2.5rem;"></i>
                    <p class="mt-3 mb-0">Chưa có thông báo nào.</p>
                </div>
            @endforelse
            </div>

            @if(isset($notifications) && $notifications->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
