@php
$userPrefix = config('core.user_prefix');
@endphp
@extends('layout.frontend.office')
@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <h3 class="office-title px-2 d-flex justify-content-between align-items-center">
                    THAY ĐỔI MẬT KHẨU
                    <div class="office-mobile-toggle d-lg-none">
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                            <img src="{{ asset('client/images/launchpad/arrow-down.svg') }}" alt="icon">
                        </button>
                    </div>
                </h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="office-card change-pass">
                            <div class="max-w">
                                <div class="office-card-tiltle mb-4"><span>Cập nhật mật khẩu</span></div>
                                <form action="{{ route($userPrefix . '.changePassword') }}" method="POST" id="changePasswordForm">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label" for="old_password">Mật khẩu cũ</label>
                                        <div class="position-relative">
                                            <input
                                                class="form-control form-office-card @error('old_password') is-invalid @enderror"
                                                id="old_password"
                                                name="old_password"
                                                type="password"
                                                placeholder="Nhập mật khẩu cũ"
                                                required
                                                style="padding-right: 45px;">
                                            <i class="fa fa-eye position-absolute" id="toggleOldPassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 16px; z-index: 10;"></i>
                                        </div>
                                        @error('old_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="new_password">Mật khẩu mới</label>
                                        <div class="position-relative">
                                            <input
                                                class="form-control form-office-card @error('new_password') is-invalid @enderror"
                                                id="new_password"
                                                name="new_password"
                                                type="password"
                                                placeholder="Nhập mật khẩu mới"
                                                required
                                                style="padding-right: 45px;">
                                            <i class="fa fa-eye position-absolute" id="toggleNewPassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 16px; z-index: 10;"></i>
                                        </div>
                                        @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                                        <div class="position-relative">
                                            <input
                                                class="form-control form-office-card @error('new_password_confirmation') is-invalid @enderror"
                                                id="new_password_confirmation"
                                                name="new_password_confirmation"
                                                type="password"
                                                placeholder="Nhập lại mật khẩu mới"
                                                required
                                                style="padding-right: 45px;">
                                            <i class="fa fa-eye position-absolute" id="toggleConfirmPassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 16px; z-index: 10;"></i>
                                        </div>
                                        @error('new_password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="office-button-group d-flex mt-4">
                                        <button class="btn office-cancel-btn" type="button" onclick="window.location.href='{{ route($userPrefix . '.dashboard') }}'">HUỶ BỎ</button>
                                        <button class="btn office-submit" type="submit">CẬP NHẬT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#toggleOldPassword').on('click', function() {
            const passwordInput = $('#old_password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        $('#toggleNewPassword').on('click', function() {
            const passwordInput = $('#new_password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        $('#toggleConfirmPassword').on('click', function() {
            const passwordInput = $('#new_password_confirmation');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
@endpush
