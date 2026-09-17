@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
@endphp

@extends('layout.system.backend')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title mb-1">Profile Settings</h3>
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile Settings</li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Cập nhật thông tin</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route($adminPrefix . '.profile-setting.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @php
                        $avatarUrl = !empty($admin?->photo_url)
                            ? $admin->photo_url
                            : asset('client/img/users/user-40.jpg');
                    @endphp

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img
                            id="profileAvatarPreview"
                            src="{{ $avatarUrl }}"
                            alt="Avatar"
                            class="profile-avatar rounded-circle"
                        >
                        <div class="flex-grow-1">
                            <div class="fw-medium">Ảnh đại diện</div>
                            <div class="text-muted small">Chọn ảnh để xem trước trước khi lưu.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input
                            type="text"
                            name="ho_va_ten"
                            value="{{ old('ho_va_ten', $admin->ho_va_ten ?? '') }}"
                            class="form-control @error('ho_va_ten') is-invalid @enderror"
                            required
                        >
                        @error('ho_va_ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $admin->email ?? '') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $admin->phone ?? '') }}"
                            class="form-control @error('phone') is-invalid @enderror"
                        >
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chọn ảnh mới</label>
                        <input
                            type="file"
                            name="photo_url"
                            id="profileAvatarInput"
                            class="form-control @error('photo_url') is-invalid @enderror"
                            accept="image/*"
                        >
                        @error('photo_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Đổi mật khẩu</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route($adminPrefix . '.profile-setting.change-password') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu cũ</label>
                        <input
                            type="password"
                            name="old_password"
                            class="form-control @error('old_password') is-invalid @enderror"
                            required
                        >
                        @error('old_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input
                            type="password"
                            name="new_password"
                            class="form-control @error('new_password') is-invalid @enderror"
                            required
                        >
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input
                            type="password"
                            name="new_password_confirmation"
                            class="form-control @error('new_password_confirmation') is-invalid @enderror"
                            required
                        >
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-outline-primary">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js_after')
<script>
    (function () {
        var input = document.getElementById('profileAvatarInput');
        var preview = document.getElementById('profileAvatarPreview');

        if (!input || !preview) return;

        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) return;

            if (!file.type || !file.type.startsWith('image/')) return;

            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target && e.target.result ? e.target.result : preview.src;
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
@endpush

