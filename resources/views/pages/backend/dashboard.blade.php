@extends('layout.backend.backend')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card corona-gradient-card">
            <div class="card-body py-0 px-0 px-sm-3">
                <div class="row align-items-center">
                    <div class="col-12 p-3">
                        <h4 class="mb-1 mb-sm-0">Tổng quan hệ thống Taekwondo</h4>
                        <p class="mb-0 font-weight-normal d-none d-sm-block">Dashboard đồng bộ giao diện Taekwondo-Đồng_Phú và dữ liệu thực tế từ hệ thống.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0">{{ number_format($totalVoSinh, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-success"><span class="mdi mdi-account-group icon-item"></span></div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Võ sinh đang hoạt động</h6>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <h3 class="mb-0">{{ number_format($totalHuanLuyenVien, 0, ',', '.') }}</h3>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-info"><span class="mdi mdi-account-tie icon-item"></span></div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Huấn luyện viên</h6>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <h3 class="mb-0">{{ number_format($totalKhoaHoc, 0, ',', '.') }}</h3>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-warning"><span class="mdi mdi-school icon-item"></span></div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Khóa học</h6>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <h3 class="mb-0">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h3>
                    </div>
                    <div class="col-3">
                        <div class="icon icon-box-danger"><span class="mdi mdi-cash-multiple icon-item"></span></div>
                    </div>
                </div>
                <h6 class="text-muted font-weight-normal">Tổng doanh thu đã thanh toán</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Thống kê võ sinh theo cấp đai</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Cấp đai</th>
                            <th class="text-right">Số lượng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($thongKeTheoCapDai as $item)
                            <tr>
                                <td>{{ $item->cap_dai_name }}</td>
                                <td class="text-right">{{ number_format($item->so_luong, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Chưa có dữ liệu</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Võ sinh mới</h4>
                <div class="preview-list">
                    @forelse($latestVoSinh as $user)
                        <div class="preview-item border-bottom">
                            <div class="preview-item-content d-sm-flex flex-grow">
                                <div class="flex-grow">
                                    <h6 class="preview-subject">{{ $user->ho_va_ten }}</h6>
                                    <p class="text-muted mb-0">{{ $user->ma_hoi_vien }}</p>
                                </div>
                                <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                    <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Chưa có dữ liệu</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tin tức mới nhất</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Tiêu đề</th>
                            <th>Thời gian</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($latestNews as $news)
                            <tr>
                                <td>{{ $news->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($news->published_at ?? $news->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Chưa có dữ liệu</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

