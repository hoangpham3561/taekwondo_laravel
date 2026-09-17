@php
$userPrefix = config('core.user_prefix');
@endphp
@extends('layout.frontend.auth')
@section('title', 'Đăng Ký - Taekwondo Đồng Phú')
@section('description', 'Đăng ký tài khoản mới tại Taekwondo Đồng Phú')
@push('css')
<style>
    .role-radio-input:checked {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
    .role-radio-input:focus {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
    .role-radio-input {
        border-color: #dee2e6;
        cursor: pointer;
        width: 1.25em;
        height: 1.25em;
    }
    .role-radio-label {
        cursor: pointer;
        user-select: none;
    }
</style>
@endpush
@section('content')
<div class="account-page">
    <div class="main-wrapper">
        <div class="overflow-hidden p-3 acc-vh">
            <div class="row vh-100 w-100 g-0">
                <!-- Left Column - Form -->
                <div class="col-lg-6 vh-100 overflow-y-auto overflow-x-hidden">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <form id="signup-form" class="vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                                <div class="text-center mb-3 auth-logo">
                                    <img src="{{ asset('client/images/logo.png') }}" class="img-fluid" alt="Logo" width="50" height="50">
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <h3 class="mb-2">Đăng Ký</h3>
                                        <p class="mb-0">Tạo tài khoản mới</p>
                                    </div>

                                    @if(session('error'))
                                    <div class="alert alert-danger mb-3">
                                        {{ session('error') }}
                                    </div>
                                    @endif
                                    @if(session('success'))
                                    <div class="alert alert-success mb-3">
                                        {{ session('success') }}
                                    </div>
                                    @endif

                                    <div id="alert-container"></div>

                                    <div class="mb-3">
                                        <label class="form-label">Tên đăng nhập</label>
                                        <div style="position: relative;">
                                            <input type="text" class="form-control" placeholder="Nhập tên đăng nhập" id="username" name="username" value="{{ old('username') }}" required style="padding-right: 40px;">
                                            <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #999; font-size: 16px;">
                                                <i class="ti ti-user"></i>
                                            </span>
                                        </div>
                                        <div id="username-error" class="text-danger mt-1" style="display: none;"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Họ và Tên</label>
                                        <div style="position: relative;">
                                            <input type="text" class="form-control" placeholder="Nhập họ và tên" id="full_name" name="full_name" value="{{ old('full_name') }}" required style="padding-right: 40px;">
                                            <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #999; font-size: 16px;">
                                                <i class="ti ti-user"></i>
                                            </span>
                                        </div>
                                        <div id="full_name-error" class="text-danger mt-1" style="display: none;"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group input-group-flat">
                                            <input type="email" class="form-control" placeholder="Nhập email Gmail của bạn" id="email" name="email" value="{{ old('email') }}" required>
                                            <span class="input-group-text">
                                                <i class="ti ti-mail"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            Vui lòng sử dụng địa chỉ email Gmail (@gmail.com)
                                        </small>
                                        <div id="email-error" class="text-danger mt-1" style="display: none;"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Mật khẩu</label>
                                        <div class="input-group input-group-flat pass-group">
                                            <input type="password" class="form-control pass-input" placeholder="Nhập mật khẩu" id="password" name="password" required>
                                            <button type="button" class="input-group-text toggle-password" style="cursor: pointer;" aria-label="Hiện mật khẩu">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted d-block mb-1">Yêu cầu mật khẩu:</small>
                                            <ul class="list-unstyled mb-0" style="font-size: 0.75rem;">
                                                <li class="text-muted">
                                                    <i class="ti ti-circle me-1"></i>
                                                    Tối thiểu 8 ký tự
                                                </li>
                                                <li class="text-muted">
                                                    <i class="ti ti-circle me-1"></i>
                                                    Có ký tự viết hoa
                                                </li>
                                                <li class="text-muted">
                                                    <i class="ti ti-circle me-1"></i>
                                                    Có số
                                                </li>
                                                <li class="text-muted">
                                                    <i class="ti ti-circle me-1"></i>
                                                    Có ký tự đặc biệt
                                                </li>
                                            </ul>
                                        </div>
                                        <div id="password-error" class="text-danger mt-1" style="display: none;"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Xác nhận mật khẩu</label>
                                        <div class="input-group input-group-flat pass-group">
                                            <input type="password" class="form-control pass-input" placeholder="Nhập lại mật khẩu" id="password_confirmation" name="password_confirmation" required>
                                            <button type="button" class="input-group-text toggle-password" style="cursor: pointer;" aria-label="Hiện mật khẩu">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </div>
                                        <div id="password_confirmation-error" class="text-danger mt-1" style="display: none;"></div>
                                    </div>

                                    @if(isset($refId) && $refId)
                                    <div class="mb-3">
                                        <label class="form-label">Mã giới thiệu</label>
                                        <input type="text" class="form-control" id="ref_id" name="ref_id" value="{{ $refTokenID ?? $refId }}" readonly>
                                        <small class="form-text text-muted">Mã giới thiệu: {{ $refUserName ?? ($refTokenID ?? $refId) }}</small>
                                    </div>
                                    @endif
                                    
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input mt-0" type="checkbox" id="agree-terms" name="agreeTerms" required>
                                            <label class="form-check-label ms-1" for="agree-terms">
                                                Tôi đồng ý với <a href="javascript:void(0);" class="text-primary link-hover">Điều khoản & Quyền riêng tư</a>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100" id="submit-btn">
                                            <span id="submit-text">Đăng Ký</span>
                                            <span id="submit-loading" style="display: none;">Đang xử lý...</span>
                                        </button>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="mb-0">
                                            Đã có tài khoản? <a href="{{ route($userPrefix . '.login') }}" class="link-indigo fw-bold link-hover">Đăng nhập ngay</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-center pb-4">
                                    <p class="text-dark mb-0">
                                        Copyright &copy; <span id="current-year"></span> - Taekwondo Đồng Phú
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Right Column - Logo -->
                <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light position-relative overflow-hidden">
                    <div class="text-center p-5">
                        <img src="{{ asset('client/images/logo.png') }}" alt="Taekwondo Đồng Phú Logo" width="500" height="500" class="img-fluid" style="max-width: 100%; height: auto; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    // Set current year
    document.getElementById('current-year').textContent = new Date().getFullYear();
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
                this.setAttribute('aria-label', 'Ẩn mật khẩu');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
                this.setAttribute('aria-label', 'Hiện mật khẩu');
            }
        });
    });

    document.getElementById('signup-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Reset errors
        document.querySelectorAll('[id$="-error"]').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        document.getElementById('alert-container').innerHTML = '';

        // Disable submit button
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitLoading = document.getElementById('submit-loading');
        submitBtn.disabled = true;
        submitText.style.display = 'none';
        submitLoading.style.display = 'inline';

        // Get form data
        const formData = {
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            full_name: document.getElementById('full_name').value,
            password_confirmation: document.getElementById('password_confirmation').value
        };

        // Add ref_id if exists
        const refIdInput = document.getElementById('ref_id');
        if (refIdInput && refIdInput.value) {
            formData.ref_id = refIdInput.value;
        }

        try {
            const response = await fetch('{{ url("/api/v1/register") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Lưu token vào localStorage (nếu cần dùng cho API sau này)
                if (data.data && data.data.token) {
                    localStorage.setItem('api_token', data.data.token);
                }

                document.getElementById('alert-container').innerHTML =
                    '<div class="alert alert-success mb-3">' + data.message + '. Đăng ký thành công. Vui lòng xác thực Email để tiếp tục.</div>';

                setTimeout(() => {
                    window.location.href = '{{ route($userPrefix . ".login") }}';
                }, 2000);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(field + '-error');
                        if (errorEl) {
                            errorEl.textContent = Array.isArray(data.errors[field]) ?
                                data.errors[field][0] :
                                data.errors[field];
                            errorEl.style.display = 'block';
                        }
                    });
                } else {
                    document.getElementById('alert-container').innerHTML =
                        '<div class="alert alert-danger mb-3">' + (data.message || 'Đăng ký thất bại') + '</div>';
                }

                // Enable submit button
                submitBtn.disabled = false;
                submitText.style.display = 'inline';
                submitLoading.style.display = 'none';
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('alert-container').innerHTML =
                '<div class="alert alert-danger mb-3">Có lỗi xảy ra. Vui lòng thử lại sau.</div>';

            // Enable submit button
            submitBtn.disabled = false;
            submitText.style.display = 'inline';
            submitLoading.style.display = 'none';
        }
    });
</script>
@endpush
@endsection
