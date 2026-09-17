@extends('layout.frontend.frontend')

@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Trang hoc vien</h2>
                    <div class="bt-option">
                        <a href="{{ route('.index') }}">Trang chủ</a>
                        <span>Hoc vien</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pricing-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="ps-item">
                    <div class="pi-price">
                        <h2>{{ $voSinh->ho_va_ten ?? 'Võ sinh' }}</h2>
                        <span>Cap dai: {{ $voSinh?->capDai?->name ?? 'Chua cap nhat' }}</span>
                    </div>
                    <div class="mb-4">
                        <img src="{{ $voSinh->profile_image_url ?? asset('assets/images/faces/face15.jpg') }}" alt="Hoc vien">
                    </div>
                    <a href="{{ route($userPrefix . '.settings') }}" class="primary-btn pricing-btn">Cập nhật ho so</a>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="ps-item mb-4">
                    <h3>Lich hoc trong tuan</h3>
                    <ul>
                        @forelse($lichHoc as $khoaHoc)
                            <li>{{ optional($khoaHoc->start_date)->format('d/m/Y') ?? 'Đang cập nhật lich' }} - {{ $khoaHoc->title }}</li>
                        @empty
                            <li>Chua co lich hoc. Vui long quay lai sau.</li>
                        @endforelse
                    </ul> 
                </div>
                <div class="ps-item">
                    <h3>Thông báo</h3>
                    <ul>
                        @forelse($thongBao as $item)
                            <li>{{ \Illuminate\Support\Str::limit($item->title ?? $item->excerpt ?? '', 120) }}</li>
                        @empty
                            <li>Chua co thong bao moi.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
