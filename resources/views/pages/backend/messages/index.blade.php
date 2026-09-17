@extends('layout.backend.backend')

@section('content')
<div class="content">
    <div class="block block-rounded p-3">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <i class="mdi mdi-email-outline me-2"></i>Tin nhắn
            </h3>
        </div>
        <div class="block-content block-content-full">
            <p class="mb-3">Đây là trang Tin nhắn (tạm thời).</p>
            <div class="list-group">
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">Hệ thống</h6>
                        <small class="text-muted">Bạn có thể tích hợp danh sách inbox tại đây.</small>
                    </div>
                    <span class="badge bg-success">Mới</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

