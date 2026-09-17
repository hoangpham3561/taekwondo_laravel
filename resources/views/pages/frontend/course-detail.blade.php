@extends('layout.frontend.frontend')

@section('content')
@php
    $c = $course;
    $descRaw = (string) ($c->description ?? '');
    $descLines = $descRaw !== ''
        ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $descRaw)), fn ($line) => $line !== ''))
        : [];
@endphp
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>{{ $c->title }}</h2>
                    <div class="bt-option">
                        <a href="{{ route($userPrefix . '.index') }}">Trang chủ</a>
                        <a href="{{ route($userPrefix . '.course') }}">Khóa học</a>
                        <span>Chi tiết</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="class-details-section spad">
    <div class="container">
        <div class="class-details-text">
            <div class="cd-pic">
                <img src="{{ $c->resolved_image_url }}" alt="{{ $c->title }}">
            </div>
            <div class="cd-text">
                <div class="cd-single-item">
                    <h3>{{ $c->title }}</h3>
                </div>
                @if(count($descLines) >= 2)
                    <div class="cd-trainer">
                        <h5>Nội dung đào tạo</h5>
                        <ul>
                            @foreach($descLines as $line)
                                <li><i class="fa fa-check"></i> {{ $line }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif($c->description)
                    <div class="cd-single-item mb-3">
                        <div class="cd-desc">{!! nl2br(e($c->description)) !!}</div>
                    </div>
                @endif

                <div class="cd-trainer mt-4">
                    <h5>Thông tin khóa học</h5>
                    <ul class="list-unstyled">
                        @if($c->level)
                            <li><strong>Cấp độ:</strong> {{ ucfirst(str_replace('_', ' ', $c->level)) }}</li>
                        @endif
                        @if($c->quarter || $c->year)
                            <li><strong>Quý / năm:</strong> {{ trim(implode(' ', array_filter([$c->quarter, $c->year]))) }}</li>
                        @endif
                        @if($c->start_date || $c->end_date)
                            <li><strong>Thời gian:</strong>
                                @if($c->start_date){{ $c->start_date->format('d/m/Y') }}@endif
                                @if($c->start_date && $c->end_date) — @endif
                                @if($c->end_date){{ $c->end_date->format('d/m/Y') }}@endif
                            </li>
                        @endif
                        @if($c->coach)
                            <li><strong>Huấn luyện viên:</strong> {{ $c->coach->ho_va_ten ?? '—' }}</li>
                        @endif
                        @if($c->club)
                            <li><strong>Câu lạc bộ:</strong> {{ $c->club->name ?? '—' }}</li>
                        @endif
                        @if($c->branch)
                            <li><strong>Chi nhánh:</strong> {{ $c->branch->name ?? '—' }}</li>
                        @endif
                        <li><strong>Số học viên hiện tại:</strong> {{ (int) $c->current_students }}</li>
                    </ul>
                </div>

                <div class="mt-4 d-flex flex-wrap gap-2">
                    <a href="{{ route($userPrefix . '.course') }}" class="primary-btn">Danh sách khóa học</a>
                    <a href="{{ route($userPrefix . '.cart.add-course', ['courseId' => $c->id]) }}" class="primary-btn">Đăng ký / Mua</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
