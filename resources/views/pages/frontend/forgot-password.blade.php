@php
$userPrefix = config('core.user_prefix');
@endphp
@extends('layout.frontend.auth')
@section('title', 'Quên Mật Khẩu - Taekwondo Đồng Phú')
@section('description', 'Đặt lại mật khẩu cho tài khoản Taekwondo Đồng Phú')
@section('content')
<section class="forgot-password d-flex align-items-center justify-content-center min-vh-100 w-100">
    <div class="container">
        <div class="row justify-content-center align-items-center d-flex">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center">
                <div class="login-form w-100 mt-4 mt-lg-0">
                    <div class="text-center mb-4">
                        <img src="{{ asset('client/images/logo.png') }}" alt="Logo" width="50" height="50">
                    </div>
                    <h2 class="mb-4 text-center forgot-password-title">
                        Đặt lại mật khẩu
                    </h2>

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

                    <form method="POST" action="{{ route($userPrefix . '.doForgotPassword') }}" class="form">
                        @csrf
                        <label class="form-label forgot-password-label" for="email">
                            Email
                        </label>
                        <input
                            class="form-control forgot-password-input @error('email') is-invalid @enderror"
                            id="email"
                            type="email"
                            name="email"
                            placeholder="Nhập email của bạn"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        />
                        @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <div class="mb-3">
                            <small class="text-muted">
                                Chúng tôi sẽ gửi link đặt lại mật khẩu đến email của bạn
                            </small>
                        </div>
                        <button
                            class="btn btn-primary forgot-password-button w-100"
                            type="submit"
                        >
                            Gửi yêu cầu
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <a class="text-primary" href="{{ route($userPrefix . '.login') }}">
                            <i class="ti ti-arrow-left"></i> Quay lại đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
