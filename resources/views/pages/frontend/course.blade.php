@extends('layout.frontend.frontend')

@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Khóa học</h2>
                    <div class="bt-option">
                        <a href="{{ route($userPrefix . '.index') }}">Trang chủ</a>
                        <span>Khóa học</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="classes-section spad">
    <div class="container">
        <div class="section-title">
            <span>Our Classes</span>
            <h2>CHUONG TRINH DAO TAO</h2>
        </div>
        <div class="row g-4">
            @forelse($courses ?? [] as $course)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="class-item course-card">
                        <div class="ci-pic">
                            <img src="{{ $course->resolved_image_url }}" alt="{{ $course->title }}">
                        </div>
                        <div class="ci-text">
                            <span>Taekwondo</span>
                            <h5>{{ $course->title }}</h5>
                            <p>{{ \Illuminate\Support\Str::limit($course->description ?? '', 110) }}</p>
                            <a href="{{ route($userPrefix . '.course-detail', $course->id) }}" class="course-cta me-2">Chi tiết</a>
                            <a href="{{ route($userPrefix . '.cart.add-course', ['courseId' => $course->id]) }}" class="course-cta">Mua ngay</a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">Chưa có dữ liệu khoá học .</div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
