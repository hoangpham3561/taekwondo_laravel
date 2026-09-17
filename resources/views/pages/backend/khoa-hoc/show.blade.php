@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$khoaHocPrefix = config('core.routes.khoa_hoc.prefix');
$levelLabels = [
    'beginner' => 'Cơ bản',
    'intermediate' => 'Trung cấp',
    'advanced' => 'Nâng cao',
];
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">Chi tiết khóa học</h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.index') }}">Khóa học</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">{{ $khoaHoc->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        @include('layout.backend.partials.message')

        <div class="block block-rounded p-3">
            <div class="block-header block-header-default d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h3 class="block-title mb-0">
                    <i class="ti ti-book me-2"></i>{{ $khoaHoc->title }}
                </h3>
                <div class="block-options d-flex flex-wrap gap-2">
                    <a href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.edit', $khoaHoc) }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-edit me-1"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.index') }}" class="btn btn-sm btn-alt-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Danh sách
                    </a>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-lg-8">
                        <table class="table table-borderless table-striped table-vcenter">
                            <tbody>
                                <tr>
                                    <th class="text-muted" style="width: 200px;">Cấp độ</th>
                                    <td>
                                        <span class="badge bg-info">{{ $levelLabels[$khoaHoc->level] ?? $khoaHoc->level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Huấn luyện viên</th>
                                    <td>{{ $khoaHoc->coach->ho_va_ten ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Chi nhánh</th>
                                    <td>{{ $khoaHoc->branch->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Câu lạc bộ</th>
                                    <td>{{ $khoaHoc->club->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Quý / Năm</th>
                                    <td>{{ $khoaHoc->quarter ?? '—' }} / {{ $khoaHoc->year ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Ngày bắt đầu</th>
                                    <td>{{ $khoaHoc->start_date ? $khoaHoc->start_date->format('d/m/Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Ngày kết thúc</th>
                                    <td>{{ $khoaHoc->end_date ? $khoaHoc->end_date->format('d/m/Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Số học viên</th>
                                    <td>{{ $khoaHoc->current_students ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Trạng thái</th>
                                    <td>
                                        @if($khoaHoc->is_active)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Ngừng hoạt động</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">URL hình ảnh</th>
                                    <td>
                                        @if($khoaHoc->image_url)
                                            <a href="{{ $khoaHoc->image_url }}" target="_blank" rel="noopener noreferrer">{{ $khoaHoc->image_url }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4">
                        <div class="block block-rounded block-bordered">
                            <div class="block-header block-header-default">
                                <h4 class="block-title">Mô tả</h4>
                            </div>
                            <div class="block-content">
                                @if($khoaHoc->description)
                                    <p class="mb-0">{{ $khoaHoc->description }}</p>
                                @else
                                    <p class="text-muted mb-0">Chưa có mô tả.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
