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
                    Quản lý nạp tiêu dùng
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Nạp tiêu dùng
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
            <h3 class="block-title">Danh sách nạp tiêu dùng</h3>
        </div>
        <div class="block-content">
            <!-- Search và Filter -->
            <form method="GET" action="{{ route($adminPrefix . '.deposits.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm theo UserName/Email/Tên</label>
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Nhập UserName, Email hoặc Tên..."
                            value="{{ $search }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Mã đơn hàng</label>
                        <input
                            type="text"
                            class="form-control"
                            name="order_code"
                            placeholder="Nhập mã đơn..."
                            value="{{ $orderCode }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="">Tất cả</option>
                            <option value="Y" {{ $status == 'Y' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="N" {{ $status == 'N' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="C" {{ $status == 'C' ? 'selected' : '' }}>Đã hủy</option>
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
                    <div class="col-md-1">
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
                    <div class="block block-rounded block-bordered">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-primary">{{ number_format($totalDeposit, 0, ',', '.') }} đ</h4>
                                <p class="text-muted mb-0">Tổng nạp đã duyệt</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-warning">{{ $totalPending }}</h4>
                                <p class="text-muted mb-0">Đơn chờ duyệt</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-info">{{ $orders->total() }}</h4>
                                <p class="text-muted mb-0">Tổng số đơn</p>
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
                            <th style="width: 150px;">Mã đơn</th>
                            <th style="width: 150px;">UserName</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 150px;">Họ & Tên</th>
                            <th style="width: 150px;">Gói nạp</th>
                            <th style="width: 120px;">Số tiền</th>
                            <th style="width: 150px;">Nội dung chuyển khoản</th>
                            <th style="width: 120px;">Ảnh chứng từ</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th style="width: 150px;">Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="text-center">{{ $order->OrderID }}</td>
                            <td>
                                <code class="text-primary">{{ $order->OrderCode ?? '-' }}</code>
                            </td>
                            <td>
                                @if($order->user)
                                <strong>{{ $order->user->UserName }}</strong>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($order->user)
                                {{ $order->user->Email }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($order->user)
                                {{ $order->user->FullName }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($order->product)
                                <span class="badge bg-info">{{ $order->product->Name }}</span>
                                @else
                                <small>{{ $order->ProductName ?? '-' }}</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong class="text-danger">{{ number_format($order->TotalAmount ?? 0, 0, ',', '.') }} đ</strong>
                            </td>
                            <td>
                                <small>{{ $order->Note ?? '-' }}</small>
                            </td>
                            <td>
                                @if($order->Image)
                                <a href="{{ $order->Image }}" target="_blank" class="btn btn-sm btn-alt-secondary" title="Xem ảnh chứng từ">
                                    <i class="fa fa-image"></i> Xem
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($order->Status === 'Y')
                                <span class="badge bg-success">Đã duyệt</span>
                                @elseif($order->Status === 'C')
                                <span class="badge bg-danger">Đã hủy</span>
                                @else
                                <span class="badge bg-warning">Chờ duyệt</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $order->OrderDate }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                    <span class="text-muted">Không có dữ liệu nạp tiêu dùng</span>
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
                    {{ $orders->appends(request()->input())->links('layout.system.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection