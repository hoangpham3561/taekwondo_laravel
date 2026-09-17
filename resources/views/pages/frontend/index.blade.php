@extends('layout.frontend.frontend')

@section('content')
<section class="hero-section">
    <div class="hs-slider owl-carousel">
        <div class="hs-item set-bg" data-setbg="{{ $heroImageUrl }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-6">
                        <div class="hi-text">
                            <span>Taekwondo Dong Phu Club</span>
                            <h1>{{ $club->name ?? 'Dao tao Taekwondo chuyen nghiep' }}</h1>
                            <p>{{ \Illuminate\Support\Str::limit($club->description ?? 'Xay dung the luc, ky luat va ban linh cho moi lua tuoi.', 150) }}</p>
                            <a href="{{ route($userPrefix . '.course') }}" class="primary-btn">Xem khoa hoc</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hs-item set-bg" data-setbg="{{ asset('client/gymlife/img/hero/hero-2.jpg') }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-6">
                        <div class="hi-text">
                            <span>Discipline - Respect - Confidence</span>
                            <h1>Ren luyen <strong>ban linh</strong> moi ngay</h1>
                            <a href="{{ route($userPrefix . '.about-us') }}" class="primary-btn">Giới thiệu CLB</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="choseus-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Why choose us?</span>
                    <h2>PUSH YOUR LIMITS FORWARD</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="cs-item">
                    <span class="flaticon-034-stationary-bike"></span>
                    <h4>Co so vat chat tot</h4>
                    <p>Khong gian tap luyen rong rai, trang thiet bi day du, an toan cho moi hoc vien.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="cs-item">
                    <span class="flaticon-033-juice"></span>
                    <h4>Lo trinh khoa hoc</h4>
                    <p>Chuong trinh duoc thiet ke theo do tuoi va cap do, de theo doi va nang cap.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="cs-item">
                    <span class="flaticon-002-dumbell"></span>
                    <h4>HLV kinh nghiem</h4>
                    <p>Doi ngu huan luyen vien dong hanh sat sao va toi uu ky thuat cho tung hoc vien.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="cs-item">
                    <span class="flaticon-014-heart-beat"></span>
                    <h4>Moi truong ky luat</h4>
                    <p>Phat trien the chat, tu ve, tinh than ton trong va ban linh ben vung.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="classes-section spad">
    <div class="container">
        <div class="section-title">
            <span>Our Classes</span>
            <h2>KHOA HOC NOI BAT</h2>
        </div>
        <div class="row">
            @forelse($featuredCourses ?? [] as $course)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route($userPrefix . '.course-detail', $course->id) }}" class="text-decoration-none text-reset d-block">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="{{ $course->resolved_image_url }}" alt="{{ $course->title }}">
                        </div>
                        <div class="ci-text">
                            <span>Taekwondo</span>
                            <h5>{{ $course->title }}</h5>
                            <p>{{ \Illuminate\Support\Str::limit($course->description ?? '', 90) }}</p>
                        </div>
                    </div>
                    </a>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info mb-0">Chua co du lieu khoa hoc.</div></div>
            @endforelse
        </div>
    </div>
</section>

<section class="banner-section set-bg" data-setbg="{{ asset('client/gymlife/img/banner-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="bs-text">
                    <h2>Đăng ký ngay de nhan lo trinh phu hop</h2>
                    <div class="bt-tips">Noi suc khoe, ky luat va ban linh hoi tu.</div>
                    <a href="{{ route($userPrefix . '.contact') }}" class="primary-btn btn-normal">Liên hệ tu van</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pricing-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Our Plan</span>
                    <h2>Goi tap goi y</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-8">
                <div class="ps-item">
                    <h3>Goi co ban</h3>
                    <div class="pi-price"><h2>799K</h2><span>1 THANG</span></div>
                    <ul>
                        <li>3 buoi / tuan</li>
                        <li>Lop co ban</li>
                        <li>Danh gia cap do</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-8">
                <div class="ps-item">
                    <h3>Goi nang cao</h3>
                    <div class="pi-price"><h2>1.299K</h2><span>1 THANG</span></div>
                    <ul>
                        <li>5 buoi / tuan</li>
                        <li>Coaching ky thuat</li>
                        <li>Luyen thi dau</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-8">
                <div class="ps-item">
                    <h3>Goi tre em</h3>
                    <div class="pi-price"><h2>699K</h2><span>1 THANG</span></div>
                    <ul>
                        <li>Lop 5-12 tuoi</li>
                        <li>Rieng theo nhom tuoi</li>
                        <li>Hoc thu 1 buoi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="gallery-section">
    <div class="gallery">
        <div class="grid-sizer"></div>
        <div class="gs-item grid-wide set-bg" data-setbg="{{ asset('client/gymlife/img/gallery/gallery-1.jpg') }}"></div>
        <div class="gs-item set-bg" data-setbg="{{ asset('client/gymlife/img/gallery/gallery-2.jpg') }}"></div>
        <div class="gs-item set-bg" data-setbg="{{ asset('client/gymlife/img/gallery/gallery-3.jpg') }}"></div>
        <div class="gs-item set-bg" data-setbg="{{ asset('client/gymlife/img/gallery/gallery-4.jpg') }}"></div>
        <div class="gs-item set-bg" data-setbg="{{ asset('client/gymlife/img/gallery/gallery-5.jpg') }}"></div>
        <div class="gs-item grid-wide set-bg" data-setbg="{{ asset('client/gymlife/img/gallery/gallery-6.jpg') }}"></div>
    </div>
</div>

<section class="team-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="team-title">
                    <div class="section-title">
                        <span>Our Team</span>
                        <h2>TRAIN WITH EXPERTS</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="ts-slider owl-carousel">
                <div class="col-lg-4"><div class="ts-item set-bg" data-setbg="{{ asset('client/gymlife/img/team/team-1.jpg') }}"><div class="ts_text"><h4>HLV Truong</h4><span>Head Coach</span></div></div></div>
                <div class="col-lg-4"><div class="ts-item set-bg" data-setbg="{{ asset('client/gymlife/img/team/team-2.jpg') }}"><div class="ts_text"><h4>HLV Minh</h4><span>Trainer</span></div></div></div>
                <div class="col-lg-4"><div class="ts-item set-bg" data-setbg="{{ asset('client/gymlife/img/team/team-3.jpg') }}"><div class="ts_text"><h4>HLV An</h4><span>Trainer</span></div></div></div>
            </div>
        </div>
    </div>
</section>

<section class="blog-section spad">
    <div class="container">
        <div class="section-title">
            <span>Our Blog</span>
            <h2>TIN TUC MOI</h2>
        </div>
        <div class="row">
            @forelse($latestNews ?? [] as $news)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-item">
                        <div class="bi-pic"><img src="{{ $news->resolved_image_url }}" alt="{{ $news->title }}"></div>
                        <div class="bi-text">
                            <ul><li>{{ optional($news->published_at)->format('d/m/Y') ?? optional($news->created_at)->format('d/m/Y') }}</li></ul>
                            <h5>{{ $news->title }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info mb-0">Chua co du lieu tin tuc.</div></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
