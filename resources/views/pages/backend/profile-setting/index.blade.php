@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
@endphp
@extends('layout.backend.backend')
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2">
                    Profile Settings
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Profile Settings
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @include('layout.backend.partials.message')
    
    <div class="row">
        <div class="col-xl-8">
            <!-- Update Profile Form -->
            <div class="block block-rounded mb-4 p-3">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        <i class="ti ti-user me-2"></i>Thông Tin Cá Nhân
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <form action="{{ route($adminPrefix . '.profile-setting.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="ho_va_ten">
                                    Họ và Tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                    class="form-control @error('ho_va_ten') is-invalid @enderror" 
                                    id="ho_va_ten" 
                                    name="ho_va_ten" 
                                    value="{{ old('ho_va_ten', $admin->ho_va_ten ?? '') }}" 
                                    placeholder="Nhập họ và tên" 
                                    required>
                                @error('ho_va_ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="email">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', $admin->email ?? '') }}" 
                                    placeholder="Nhập email" 
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="phone">
                                    Số Điện Thoại
                                </label>
                                <input type="text" 
                                    class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone', $admin->phone ?? '') }}" 
                                    placeholder="Nhập số điện thoại">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="ma_hoi_vien">
                                    Mã Hội Viên
                                </label>
                                <input type="text" 
                                    class="form-control" 
                                    id="ma_hoi_vien" 
                                    value="{{ $admin->ma_hoi_vien ?? '' }}" 
                                    readonly>
                                <small class="text-muted">Mã hội viên không thể thay đổi</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="photo_url">
                                    Ảnh Đại Diện
                                </label>
                                
                                <!-- Preview ảnh hiện tại hoặc ảnh mới được chọn -->
                                <div class="mb-3 text-center">
                                    <img id="avatarPreview" 
                                        src="{{ $admin->photo_url ?? asset('client/img/users/user-40.jpg') }}" 
                                        alt="Avatar" 
                                        class="img-thumbnail rounded-circle" 
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                
                                <input type="file" 
                                    class="form-control @error('photo_url') is-invalid @enderror" 
                                    id="photo_url" 
                                    name="photo_url" 
                                    accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('photo_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Định dạng: JPEG, PNG, JPG, GIF. Kích thước tối đa: 2MB</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Cập Nhật Thông Tin
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="block block-rounded p-3">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        <i class="ti ti-lock me-2"></i>Đổi Mật Khẩu
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <form action="{{ route($adminPrefix . '.profile-setting.change-password') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="old_password">
                                    Mật Khẩu Cũ <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                    class="form-control @error('old_password') is-invalid @enderror" 
                                    id="old_password" 
                                    name="old_password" 
                                    placeholder="Nhập mật khẩu cũ" 
                                    required>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="new_password">
                                    Mật Khẩu Mới <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                    class="form-control @error('new_password') is-invalid @enderror" 
                                    id="new_password" 
                                    name="new_password" 
                                    placeholder="Nhập mật khẩu mới" 
                                    required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="new_password_confirmation">
                                    Xác Nhận Mật Khẩu Mới <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                    id="new_password_confirmation" 
                                    name="new_password_confirmation" 
                                    placeholder="Xác nhận mật khẩu mới" 
                                    required>
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Đổi Mật Khẩu
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-xl-4">
            <div class="block block-rounded p-3">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        <i class="ti ti-info-circle me-2"></i>Thông Tin Tài Khoản
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="text-center mb-4">
                        @if($admin->photo_url)
                            <img src="{{ $admin->photo_url }}" alt="Avatar" class="img-thumbnail rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <img src="{{ asset('client/img/users/user-40.jpg') }}" alt="Avatar" class="img-thumbnail rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                    </div>
                    
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold">Họ và Tên:</td>
                            <td>{{ $admin->ho_va_ten ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td>{{ $admin->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">SĐT:</td>
                            <td>{{ $admin->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Mã Hội Viên:</td>
                            <td>{{ $admin->ma_hoi_vien ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Vai Trò:</td>
                            <td>
                                @if($admin->role === 'owner')
                                    <span class="badge bg-success">Super Admin</span>
                                @elseif($admin->role === 'admin')
                                    <span class="badge bg-info">Admin</span>
                                @else
                                    <span class="badge bg-secondary">{{ $admin->role ?? 'N/A' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Trạng Thái:</td>
                            <td>
                                @if($admin->is_active)
                                    <span class="badge bg-success">Hoạt Động</span>
                                @else
                                    <span class="badge bg-danger">Không Hoạt Động</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js_after')
<script>
    function readPath(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file size (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                alert('Dung lượng file vượt quá 2 MB. Vui lòng chọn file khác.');
                $(input).val('');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file JPEG, PNG, JPG, GIF. Vui lòng chọn file khác.');
                $(input).val('');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    }

    $(document).ready(function() {
        // Preview avatar when file is selected
        $('#photo_url').on('change', function() {
            readPath(this);
        });
    });
</script>
@endpush
