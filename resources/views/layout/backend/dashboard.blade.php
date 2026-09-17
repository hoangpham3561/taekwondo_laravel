@extends('layout.backend.backend')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2">Dashboard Thống Kê</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    Tổng quan hệ thống CRM
                </h2>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('admin.dashboard') }}">Admin</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @include('layout.backend.partials.message')

    <h2 class="content-heading">
        <i class="fa fa-chart-pie me-2 text-primary"></i>Tổng Quan
    </h2>
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="block block-rounded stats-card">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                    <div class="me-3">
                        <p class="fs-sm fw-medium text-muted mb-1">Tổng Võ Sinh</p>
                        <p class="fs-3 fw-bold text-dark mb-0">
                            {{ number_format($totalVoSinh ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="stats-icon bg-primary-light">
                        <i class="fa fa-2x fa-users text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="block block-rounded stats-card">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                    <div class="me-3">
                        <p class="fs-sm fw-medium text-muted mb-1">Tổng Doanh Thu</p>
                        <p class="fs-3 fw-bold text-success mb-0">
                            {{ number_format($totalRevenue ?? 0, 0, ',', '.') }} đ
                        </p>
                    </div>
                    <div class="stats-icon bg-success-light">
                        <i class="fa fa-2x fa-money-bill-wave text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="content-heading mt-4">
        <i class="fa fa-layer-group me-2 text-info"></i>Tổng Võ Sinh Theo Cấp Đai
    </h2>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Danh Sách Cấp Đai</h3>
        </div>
        <div class="block-content p-0">
            <div class="table-responsive">
                <table class="table table-striped table-vcenter mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">STT</th>
                            <th>Tên Cấp Đai</th>
                            <th class="text-end">Tổng Võ Sinh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($thongKeTheoCapDai as $index => $capDai)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $capDai->cap_dai_name }}</td>
                            <td class="text-end">{{ number_format($capDai->so_luong, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu cấp đai</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection