@extends('layout.frontend.frontend')
@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Chuoi coffee</h2>
                    <div class="bt-option">
                        <a href="{{ route('.index') }}">Trang chủ</a>
                        <span>Chuoi coffee</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="blog-section spad">
    <div class="container">
        <div class="section-title">
            <span>Coffee</span>
            <h2>CHUOI CUA HANG</h2>
        </div>
        <div class="row mt-5">
            @for($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="blog-item">
                        <div class="bi-pic"><img src="{{ asset('client/images/list-cf.png') }}" alt="Coffee Shop"></div>
                        <div class="bi-text">
                            <h5>S'mores Saigon Caffe</h5>
                            <p>Địa chỉ: 2/12 Cao Thang, Quan 3, TP.HCM</p>
                            <p>Gio mo cua: 10:00 - 22:00</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
@endsection
