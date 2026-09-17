@extends('layout.frontend.frontend')

@section('content')
@php
    $defaultBreadcrumbBg = asset('client/gymlife/img/breadcrumb-bg.jpg');
@endphp
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Giới thiệu</h2>
                    <div class="bt-option">
                        <a href="{{ route($userPrefix . '.index') }}">Trang chủ</a>
                        <span>Giới thiệu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .breadcrumb-section {
        background-image: url('{{ $defaultBreadcrumbBg }}');
    }

    .aboutus-section .cs-item h4,
    .aboutus-section .cs-item p {
        color: #000;
    }
</style>

<section class="aboutus-section spad">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 p-0">
                <div class="about-video set-bg" data-setbg="{{ $clubImageUrl }}"></div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="about-text">
                    <div class="section-title">
                        <span>About Us</span>
                        <h2>{{ $club->name ?? 'Clb taekwondo dong phu' }}</h2>
                    </div>
                    <div class="at-desc">
                        <p>
                        {{ $club->description ?? 'Chúng tôi xây dựng môi trường tập luyện ky luật, tích cực và an toàn. Học viên được phát triển đồng bộ về thế chất, kỹ năng tự vệ và bản lĩnh.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-lg-4"><div class="cs-item"><h4>Địa chỉ</h4><p>{{ $club->address ?? 'Đang cập nhật' }}</p></div></div>
            <div class="col-lg-4"><div class="cs-item"><h4>Điện thoại</h4><p>{{ $club->phone ?? 'Đang cập nhật' }}</p></div></div>
            <div class="col-lg-4"><div class="cs-item"><h4>Email</h4><p>{{ $club->email ?? 'Đang cập nhật' }}</p></div></div>
        </div>
    </div>
</section>
@endsection

