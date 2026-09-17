@php
$userPrefix = config('core.user_prefix');
@endphp
@extends('layout.frontend.auth')
@section('title', 'Đăng Nhập - Taekwondo Đồng Phú')
@section('description', 'Đăng nhập vào hệ thống Taekwondo Đồng Phú')
@section('content')
<div class="account-page bg-white">
    <div class="main-wrapper">
        <div class="overflow-hidden p-3 acc-vh">
            <div class="row vh-100 w-100 g-0">
                <!-- Left Column - Form -->
                <div class="col-lg-6 vh-100 overflow-y-auto overflow-x-hidden">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <form id="login-form" class="vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                                <div class="text-center mb-4 auth-logo">
                                    <img src="{{ asset('client/images/logo.png') }}" class="img-fluid" alt="Logo" width="50" height="50">
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <h3 class="mb-2">Đăng Nhập</h3>
                                        <p class="mb-0">
                                            Truy cập hệ thống bằng email, tên đăng nhập, mã hội viên và mật khẩu của bạn.
                                        </p>
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
                                        <label class="form-label">Email / Tên đăng nhập / Mã hội viên</label>
                                        <div class="input-group input-group-flat">
                                            <input type="text" class="form-control" placeholder="Nhập email, tên đăng nhập hoặc mã hội viên" id="email" name="email" required>
                                            <span class="input-group-text">
                                                <i class="ti ti-user"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            Bạn có thể đăng nhập bằng email, tên đăng nhập hoặc mã hội viên
                                            <br>
                                            <span style="font-size: 11px;">
                                                Định dạng mã hội viên: HV_tên+họ+tên lót viết tắt_ngày tháng năm sinh
                                                <br>
                                                Ví dụ: HV_anhhpba_280216
                                            </span>
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
                                        <div id="password-error" class="text-danger mt-1" style="display: none;"></div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input mt-0" type="checkbox" id="rememberMe" name="remember" value="1">
                                            <label class="form-check-label text-dark ms-1" for="rememberMe">
                                                Ghi nhớ đăng nhập
                                            </label>
                                        </div>
                                        <div class="text-end">
                                            <a href="{{ route($userPrefix . '.forgotPassword') }}" class="link-danger fw-medium link-hover">Quên mật khẩu?</a>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100" id="submit-btn">
                                            <span id="submit-text">Đăng Nhập</span>
                                            <span id="submit-loading" style="display: none;">Đang xử lý...</span>
                                        </button>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="mb-0">
                                            Chưa có tài khoản? <a href="{{ route($userPrefix . '.sign-up') }}" class="link-indigo fw-bold link-hover">Đăng ký ngay</a>
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

    document.getElementById('login-form').addEventListener('submit', async function(e) {
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
        submitText.style.display = 'inline';
        submitLoading.style.display = 'none';

        // Get form data
        const formData = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        try {
            const response = await fetch('{{ url("/api/v1/login") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Lưu token vào localStorage
                if (data.data && data.data.token) {
                    localStorage.setItem('api_token', data.data.token);
                }

                // Hiển thị thông báo thành công
                document.getElementById('alert-container').innerHTML =
                    '<div class="alert alert-success mb-3">' + data.message + '</div>';

                // Tạo session ngầm, không điều hướng qua URL trung gian
                const remember = document.getElementById('rememberMe').checked;
                const sessionPayload = new URLSearchParams();
                sessionPayload.append('token', data.data.token);
                sessionPayload.append('remember', remember ? '1' : '0');
                sessionPayload.append('_token', '{{ csrf_token() }}');

                const sessionResponse = await fetch('{{ route($userPrefix . ".createSessionFromToken", [], false) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html,application/xhtml+xml'
                    },
                    body: sessionPayload.toString(),
                    credentials: 'same-origin'
                });

                if (!sessionResponse.ok) {
                    throw new Error('Không thể tạo session đăng nhập');
                }

                window.location.href = '{{ route($userPrefix . ".index", [], false) }}';
            } else {
                // Hiển thị lỗi validation
                if (data.errors && typeof data.errors === 'object') {
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(field + '-error');
                        if (errorEl) {
                            const errorMessage = Array.isArray(data.errors[field]) ?
                                data.errors[field][0] :
                                data.errors[field];
                            errorEl.textContent = errorMessage;
                            errorEl.style.display = 'block';
                        }
                    });

                    if (Object.keys(data.errors).length === 0 && data.message) {
                        document.getElementById('alert-container').innerHTML =
                            '<div class="alert alert-danger mb-3">' + data.message + '</div>';
                    }
                } else if (data.message) {
                    document.getElementById('alert-container').innerHTML =
                        '<div class="alert alert-danger mb-3">' + data.message + '</div>';
                } else {
                    document.getElementById('alert-container').innerHTML =
                        '<div class="alert alert-danger mb-3">Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.</div>';
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
