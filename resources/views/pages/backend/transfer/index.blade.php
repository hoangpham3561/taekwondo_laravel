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
                    Quản lý chuyển khoản nội bộ
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Chuyển khoản nội bộ
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @include('layout.system.partials.message')

    <div class="block block-rounded p-3">
        <div class="block-header block-header-default">
            <h3 class="block-title">Danh sách chuyển khoản nội bộ</h3>
        </div>
        <div class="block-content">
            <!-- Search và Filter -->
            <form method="GET" action="{{ route($adminPrefix . '.transfers.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm người nhận (UserName/Email/Tên)</label>
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Người nhận..."
                            value="{{ $search }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tìm người gửi (UserName/Email/Tên)</label>
                        <input
                            type="text"
                            class="form-control"
                            name="search_sender"
                            placeholder="Người gửi..."
                            value="{{ $searchSender }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Mã giao dịch</label>
                        <input
                            type="text"
                            class="form-control"
                            name="serial_code"
                            placeholder="Mã GD..."
                            value="{{ $serialCode }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Loại ví</label>
                        <select class="form-select" name="currency">
                            <option value="">Tất cả</option>
                            <option value="THANKHOAN" {{ $currency == 'THANKHOAN' ? 'selected' : '' }}>Thanh Khoản</option>
                            <option value="THANHVIEN" {{ $currency == 'THANHVIEN' ? 'selected' : '' }}>Thành Viên</option>
                            <option value="TIEUDUNG" {{ $currency == 'TIEUDUNG' ? 'selected' : '' }}>Tiêu Dùng</option>
                        </select>
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
                                <i class="fa fa-search me-1"></i> Tìm
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Thống kê -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered p-3">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-primary">{{ number_format($totalTransfer, 0, ',', '.') }} đ</h4>
                                <p class="text-muted mb-0">Tổng chuyển khoản</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered p-3">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-info">{{ $totalCount }}</h4>
                                <p class="text-muted mb-0">Tổng số giao dịch</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered p-3">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-success">{{ $transfers->total() }}</h4>
                                <p class="text-muted mb-0">Số giao dịch hiện tại</p>
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
                            <th style="width: 120px;">Mã GD</th>
                            <th style="width: 150px;">Người nhận</th>
                            <th style="width: 200px;">Email người nhận</th>
                            <th style="width: 150px;">Người gửi</th>
                            <th style="width: 200px;">Email người gửi</th>
                            <th style="width: 120px;">Loại ví</th>
                            <th style="width: 120px;">Số tiền</th>
                            <th style="width: 200px;">Ghi chú</th>
                            <th style="width: 150px;">Ngày chuyển</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        <tr>
                            <td class="text-center">{{ $transfer->RequestID }}</td>
                            <td>
                                <code class="text-primary">{{ $transfer->SerialCode ?? '-' }}</code>
                            </td>
                            <td>
                                @if($transfer->user)
                                <strong>{{ $transfer->user->UserName }}</strong>
                                @if($transfer->user->FullName)
                                <br><small class="text-muted">({{ $transfer->user->FullName }})</small>
                                @endif
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($transfer->user)
                                {{ $transfer->user->Email }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($transfer->fUser)
                                <strong>{{ $transfer->fUser->UserName }}</strong>
                                @if($transfer->fUser->FullName)
                                <br><small class="text-muted">({{ $transfer->fUser->FullName }})</small>
                                @endif
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($transfer->fUser)
                                {{ $transfer->fUser->Email }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $transfer->Currency ?? '-' }}</span>
                            </td>
                            <td class="text-end">
                                <strong class="text-success">{{ number_format($transfer->AmountTransfer ?? 0, 0, ',', '.') }} đ</strong>
                            </td>
                            <td>
                                <small>{{ $transfer->Note ?? '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $transfer->RequestDate }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                    <span class="text-muted">Không có dữ liệu chuyển khoản</span>
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
                    {{ $transfers->appends(request()->input())->links('layout.system.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scrollable Table - Dùng chung cho tất cả table */
    .table-scrollable-wrapper {
        position: relative;
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .table-scrollable-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .table-scrollable-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-scrollable-wrapper::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-scrollable-wrapper::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .table-scrollable {
        width: 100%;
        min-width: 100%;
        margin-bottom: 0;
    }

    .table-scrollable thead th {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 10;
        white-space: nowrap;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .table-scrollable-wrapper {
            overflow-x: scroll;
            -webkit-overflow-scrolling: touch;
        }

        .table-scrollable {
            min-width: 1400px;
        }
    }
</style>
@endsection