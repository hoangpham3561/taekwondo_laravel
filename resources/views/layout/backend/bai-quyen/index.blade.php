@extends('layout.system.backend')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title mb-1">Bài Quyền</h3>
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Bài Quyền</li>
            </ul>
        </div>
    </div>
</div>

@if(!empty($error))
    <div class="alert alert-danger">
        {{ $error }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Tên (VN)</th>
                        <th>Tên (EN)</th>
                        <th>Tên (KR)</th>
                        <th>Cấp độ</th>
                        <th>Mô tả</th>
                        <th class="text-end">Số động tác</th>
                        <th class="text-end">Thời gian (giây)</th>
                        <th>Khối lượng lý thuyết</th>
                        <th class="text-end">Tạo lúc</th>
                        <th class="text-end">Cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($baiQuyen ?? [] as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->ten_bai_quyen_vietnamese }}</td>
                            <td>{{ $item->ten_bai_quyen_english }}</td>
                            <td>{{ $item->ten_bai_quyen_korean }}</td>
                            <td>{{ $item->cap_do }}</td>
                            <td>{{ $item->mo_ta }}</td>
                            <td class="text-end">{{ $item->so_dong_tac }}</td>
                            <td class="text-end">{{ $item->thoi_gian_thuc_hien }}</td>
                            <td>{{ $item->khoi_luong_ly_thuyet }}</td>
                            <td class="text-end">{{ optional($item->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="text-end">{{ optional($item->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
