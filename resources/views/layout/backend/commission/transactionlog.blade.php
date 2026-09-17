@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
@endphp
@extends('layout.system.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2">
                    Quản lý Transaction Logs
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Transaction Logs
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @include('layout.system.partials.message')

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Danh sách Transaction Logs</h3>
        </div>
        <div class="block-content">
            <!-- Search và Filter -->
            <form method="GET" action="{{ route($adminPrefix . '.transaction-logs.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Nhận Hoa hồng từ UserName</label>
                        <input
                            type="text"
                            class="form-control"
                            name="from_user_name"
                            placeholder="Nhập UserName, Email hoặc Tên..."
                            value="{{ $fromUserName }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">UserName/Email/Tên</label>
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Nhập UserName, Email hoặc Tên..."
                            value="{{ $search }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Loại hoa hồng</label>
                        <select class="form-select" name="type">
                            <option value="">Tất cả</option>
                            <option value="SL" {{ $type == 'SL' ? 'selected' : '' }}>Mua sỉ bán lẻ</option>
                            <option value="DL" {{ $type == 'DL' ? 'selected' : '' }}>Kết Nối đại lý</option>
                            <option value="VIP" {{ $type == 'VIP' ? 'selected' : '' }}>Đồng chia trọn đời</option>
                            <option value="CH" {{ $type == 'CH' ? 'selected' : '' }}>Cộng hưởng cộng sinh</option>
                            <option value="TL" {{ $type == 'TL' ? 'selected' : '' }}>Thành lập cửa hàng</option>
                            <option value="TT" {{ $type == 'TT' ? 'selected' : '' }}>Thưởng Tagger</option>
                            <option value="LD" {{ $type == 'LD' ? 'selected' : '' }}>Thưởng lãnh đạo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Currency</label>
                        <select class="form-select" name="currency">
                            <option value="">Tất cả</option>
                            <option value="VND" {{ $currency == 'VND' ? 'selected' : '' }}>VND</option>
                            <option value="PA" {{ $currency == 'PA' ? 'selected' : '' }}>PA</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">CNID</label>
                        <input
                            type="number"
                            class="form-control"
                            name="cnid"
                            placeholder="Nhập CNID..."
                            value="{{ $cnid }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input
                            type="date"
                            class="form-control"
                            name="from_date"
                            value="{{ $fromDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input
                            type="date"
                            class="form-control"
                            name="to_date"
                            value="{{ $toDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Thống kê -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-primary">{{ number_format($totalPoint, 0, ',', '.') }} đ</h4>
                                <p class="text-muted mb-0">Tổng Point VNĐ</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-info">{{ $transactionLogs->total() }}</h4>
                                <p class="text-muted mb-0">Tổng số Transaction Logs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-scrollable-wrapper">
                <table class="table table-bordered table-striped table-vcenter table-scrollable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">ID</th>

                            <th style="width: 150px;">UserName Nhận</th>
                            <th style="width: 150px;">Từ</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 150px;">Họ & Tên</th>
                            <th style="width: 120px;">Loại</th>
                            <th style="width: 100px;">Point</th>
                            <th style="width: 100px;">PV</th>
                            <th style="width: 100px;">Currency</th>
                            <th style="width: 80px;">Floor</th>
                            <th style="width: 100px;">Percent</th>
                            <th style="width: 150px;">Nội dung</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 120px;">CNID</th>
                            <th style="width: 150px;">Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactionLogs as $log)
                        <tr>
                            <td class="text-center">{{ $log->id }}</td>
                            <td>
                                @if($log->user)
                                <strong>{{ $log->user->UserName }}</strong>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($log->user2)
                                <strong>{{ $log->user2->UserName }}</strong>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @if($log->user)
                                {{ $log->user->Email }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($log->user)
                                {{ $log->user->FullName }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $typeNames[$log->type] ?? '-' }}</span>
                            </td>
                            <td class="text-end">
                                <strong class="text-primary">{{ number_format($log->point ?? 0, 0, ',', '.') }} đ</strong>
                            </td>
                            <td class="text-end">
                                <strong class="text-success">{{ number_format($log->pv ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $log->currency ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $log->floor ?? 0 }}</span>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($log->percent ?? 0, 2, ',', '.') }}%</strong>
                            </td>
                            <td>
                                <small>{{ $log->note ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $log->status == 'Y' ? 'success' : ($log->status == 'N' ? 'warning' : 'secondary') }}">
                                    {{ $log->status ?? 'pending' }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $log->cnid ?? '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : '-' }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                    <span class="text-muted">Không có dữ liệu transaction logs</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-12">
                    {{ $transactionLogs->appends(request()->input())->links('layout.system.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection