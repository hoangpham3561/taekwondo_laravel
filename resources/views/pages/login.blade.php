@extends('layout.backend.simple')

@section('content')

<div class="overflow-hidden p-3 acc-vh">
    <!-- start row -->
    <div class="row vh-100 w-100 g-0">
        <div class="col-lg-6 vh-100 overflow-y-auto overflow-x-hidden">
            <!-- start row -->
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <form action="{{ route('admin.doLogin') }}" method="POST" class="vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                        @csrf
                        
                        <div class="text-center mb-4 auth-logo">
                            <img src="{{ asset('client/img/logo.svg') }}" class="img-fluid" alt="Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <h2 class="mb-0" style="display:none;">TAEKWONDO ĐỒNG PHÚ</h2>
                        </div>
                        
                        <div>
                            <div class="mb-3">
                                <h3 class="mb-2">Sign In</h3>
                                <p class="mb-0">Đăng nhập bằng mã hội viên, email, họ tên hoặc số điện thoại và mật khẩu.</p>
                            </div>
                            
                            @include('layout.backend.partials.message')
                            
                            <div class="mb-3">
                                <label class="form-label">Mã hội viên / Email / Họ tên / SĐT</label>
                                <div class="input-group input-group-flat">
                                    <input type="text" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           id="login-username" 
                                           name="username" 
                                           placeholder="Nhập mã hội viên, email, họ tên hoặc số điện thoại" 
                                           value="{{ old('username') }}" 
                                           required>
                                    <span class="input-group-text">
                                        <i class="mdi mdi-account-outline"></i>
                                    </span>
                                </div>
                                @error('username')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group input-group-flat pass-group">
                                    <input type="password" 
                                           class="form-control pass-input @error('password') is-invalid @enderror" 
                                           id="login-password" 
                                           name="password" 
                                           placeholder="Password" 
                                           required>
                                    <button type="button" class="input-group-text toggle-password" style="cursor: pointer;" aria-label="Hiện mật khẩu">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">CAPTCHA</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" 
                                           class="form-control @error('captcha') is-invalid @enderror" 
                                           id="login-captcha" 
                                           name="captcha" 
                                           placeholder="Enter Captcha" 
                                           maxlength="5" 
                                           required>
                                    <div style="width: 120px; height: 40px; border: 1px solid #ddd; background: #f5f5f5; display: flex; align-items: center; justify-content: center; cursor: pointer;" 
                                         onclick="refreshCaptcha()" 
                                         title="Click to refresh">
                                        <img src="{{ route('admin.captcha') }}?t={{ time() }}" 
                                             alt="CAPTCHA" 
                                             id="captcha-image" 
                                             style="width: 100%; height: 100%; object-fit: contain;">
                                    </div>
                                </div>
                                @error('captcha')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="form-check form-check-md d-flex align-items-center">
                                    <input class="form-check-input mt-0" type="checkbox" name="remember" value="1" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark ms-1" for="remember-me">
                                        Remember Me
                                    </label>
                                </div>
                                <div class="text-end">
                                    <a href="#" class="link-danger fw-medium link-hover">Forgot Password?</a>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">Sign In</button>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-0">New on our platform?<a href="#" class="link-indigo fw-bold link-hover"> Create an account</a></p>
                            </div>
                            
                            <div class="or-login text-center position-relative mb-3">
                                <h6 class="fs-14 mb-0 position-relative text-body">OR</h6>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 mb-3">
                                <div class="text-center flex-fill">
                                    <a href="javascript:void(0);" class="p-2 btn btn-info d-flex align-items-center justify-content-center">
                                        <i class="mdi mdi-facebook fs-4"></i>
                                    </a>
                                </div>
                                <div class="text-center flex-fill">
                                    <a href="javascript:void(0);" class="p-2 btn btn-outline-secondary d-flex align-items-center justify-content-center">
                                        <i class="mdi mdi-google fs-4 text-danger"></i>
                                    </a>
                                </div>
                                <div class="text-center flex-fill">
                                    <a href="javascript:void(0);" class="p-2 btn btn-dark d-flex align-items-center justify-content-center">
                                        <i class="mdi mdi-apple fs-4"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center pb-4">
                            <p class="text-dark mb-0">Copyright &copy; {{ date('Y') }} - Taekwondo</p>
                        </div>
                    </form>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        
        <div class="col-lg-6 account-bg-01"></div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>

@endsection

@push('scripts')
<script>
    function refreshCaptcha() {
        const img = document.getElementById('captcha-image');
        img.src = '{{ route("admin.captcha") }}?t=' + new Date().getTime();
    }
    
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password').forEach(function(togglePassword) {
            const passwordInput = togglePassword.closest('.pass-group')?.querySelector('.pass-input');
            if (!passwordInput) return;

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('mdi-eye-off');
                    icon.classList.add('mdi-eye');
                    this.setAttribute('aria-label', 'Hiện mật khẩu');
                } else {
                    icon.classList.remove('mdi-eye');
                    icon.classList.add('mdi-eye-off');
                    this.setAttribute('aria-label', 'Ẩn mật khẩu');
                }
            });
        });
    });
</script>
@endpush