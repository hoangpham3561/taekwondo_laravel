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
                    Quản lý rút tiền
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Rút tiền
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
            <h3 class="block-title">Danh sách yêu cầu rút tiền</h3>
        </div>
        <div class="block-content">
            <!-- Search và Filter -->
            <form method="GET" action="{{ route($adminPrefix . '.withdraws.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm (UserName/Email/Tên)</label>
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Nhập UserName, Email hoặc Tên..."
                            value="{{ $search }}">
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
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="">Tất cả</option>
                            <option value="Y" {{ $status == 'Y' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="N" {{ $status == 'N' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="C" {{ $status == 'C' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <!-- <div class="col-md-2">
                        <label class="form-label">Loại ví</label>
                        <select class="form-select" name="currency">
                            <option value="">Tất cả</option>
                            <option value="THANKHOAN" {{ $currency == 'THANKHOAN' ? 'selected' : '' }}>Thanh Khoản</option>
                            <option value="THANHVIEN" {{ $currency == 'THANHVIEN' ? 'selected' : '' }}>Thành Viên</option>
                            <option value="TIEUDUNG" {{ $currency == 'TIEUDUNG' ? 'selected' : '' }}>Tiêu Dùng</option>
                        </select>
                    </div> -->
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
                    <div class="block block-rounded block-bordered p-3">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-primary">{{ number_format($totalWithdraw, 0, ',', '.') }} đ</h4>
                                <p class="text-muted mb-0">Tổng rút đã duyệt</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered p-3">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-warning">{{ $totalPending }}</h4>
                                <p class="text-muted mb-0">Yêu cầu chờ duyệt</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered p-3">
                        <div class="block-content">
                            <div class="text-center">
                                <h4 class="mb-0 text-info">{{ $totalCount }}</h4>
                                <p class="text-muted mb-0">Tổng số yêu cầu</p>
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
                            <th style="width: 150px;">UserName</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 150px;">Họ & Tên</th>
                            <th style="width: 120px;">Loại ví</th>
                            <th style="width: 120px;">Số tiền</th>
                            <th style="width: 120px;">Tỉ giá rút</th>
                            <th style="width: 120px;">Thuế TNCN</th>
                            <th style="width: 120px;">Thực nhận</th>
                            <th style="width: 150px;">Số TK ngân hàng</th>
                            <th style="width: 150px;">Tên ngân hàng</th>
                            <th style="width: 150px;">Chủ tài khoản</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th style="width: 150px;">Ngày yêu cầu</th>
                            <th style="width: 150px;">Công cụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $withdraw)
                        <tr>
                            <td class="text-center">{{ $withdraw->RequestID }}</td>
                            <td>
                                <code class="text-primary">{{ $withdraw->SerialCode ?? '-' }}</code>
                            </td>
                            <td>
                                @if($withdraw->user)
                                <strong>{{ $withdraw->user->UserName }}</strong>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($withdraw->user)
                                {{ $withdraw->user->Email }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($withdraw->user)
                                {{ $withdraw->user->FullName }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $withdraw->Currency ?? '-' }}</span>
                            </td>
                            <td class="text-end">
                                <strong class="text-danger">{{ number_format($withdraw->AmountTransfer ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                1PA = {{ number_format($withdraw->Rate ?? 0, 0, ',', '.') }} VND
                            </td>
                            <td>
                                {{ number_format($withdraw->detail->AmountFee ?? 0, 0, ',', '.') }} ({{ $withdraw->detail->Fee ?? 0 }}%)
                            </td>
                            <td>
                                {{ number_format($withdraw->detail->PV ?? 0, 0, ',', '.') }} VND
                            </td>
                            <td>
                                {{ $withdraw->detail->BTCAddress ?? '-' }}
                            </td>
                            <td>
                                {{ $withdraw->detail->BankName ?? '-' }}
                            </td>
                            <td>
                                {{ $withdraw->detail->FullName ?? '-' }}
                            </td>
                            <td>
                                @if($withdraw->RequestStatus == 'Y')
                                <span class="badge bg-success">Đã duyệt</span>
                                @elseif($withdraw->RequestStatus == 'N')
                                <span class="badge bg-warning">Chờ duyệt</span>
                                @else
                                <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $withdraw->RequestDate ? $withdraw->RequestDate->format('H:i d/m/Y') : '-' }}</small>
                            </td>
                            <td>
                                @if($withdraw->RequestStatus == 'N')
                                <div class="btn-group">
                                    <button type="button"
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approveModal{{ $withdraw->RequestID }}">
                                        <i class="fa fa-check"></i> Duyệt
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $withdraw->RequestID }}">
                                        <i class="fa fa-times"></i> Hủy
                                    </button>
                                </div>
                                @elseif($withdraw->RequestStatus == 'Y' && $withdraw->detail && $withdraw->detail->BTCImage)
                                <a href="{{ $withdraw->detail->BTCImage }}"
                                    target="_blank"
                                    class="btn btn-sm btn-info">
                                    <i class="fa fa-image"></i> Xem hóa đơn
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Duyệt -->
                        <div class="modal fade" id="approveModal{{ $withdraw->RequestID }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route($adminPrefix . '.withdraws.approve', $withdraw->RequestID) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Duyệt rút tiền</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <p><strong>Mã GD:</strong> {{ $withdraw->SerialCode }}</p>
                                                <p><strong>User:</strong> {{ $withdraw->user->UserName ?? '-' }} ({{ $withdraw->user->FullName ?? '-' }})</p>
                                                <p><strong>Số tiền:</strong> {{ number_format($withdraw->detail->PV, 0, ',', '.') }} VND</p>
                                                @if($withdraw->detail)
                                                <p><strong>Số TK:</strong> {{ $withdraw->detail->BTCAddress }}</p>
                                                <p><strong>Ngân hàng:</strong> {{ $withdraw->detail->BankName }}</p>
                                                <p><strong>Chủ TK:</strong> {{ $withdraw->detail->FullName }}</p>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Upload hóa đơn chuyển khoản <span class="text-danger">*</span></label>
                                                <input type="file"
                                                    class="form-control"
                                                    name="receipt_image"
                                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                                    required>
                                                <small class="text-muted">Định dạng: jpeg, png, jpg, gif. Tối đa 5MB</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-check me-1"></i> Xác nhận duyệt
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Hủy -->
                        <div class="modal fade" id="rejectModal{{ $withdraw->RequestID }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route($adminPrefix . '.withdraws.reject', $withdraw->RequestID) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hủy rút tiền</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <p><strong>Mã GD:</strong> {{ $withdraw->SerialCode }}</p>
                                                <p><strong>User:</strong> {{ $withdraw->user->UserName ?? '-' }} ({{ $withdraw->user->FullName ?? '-' }})</p>
                                                <p><strong>Số tiền:</strong> {{ number_format($withdraw->detail->PV, 0, ',', '.') }} VND</p>
                                                <p class="text-danger"><strong>Lưu ý:</strong> Khi hủy, tiền sẽ được trả lại vào ví của user.</p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Lý do hủy</label>
                                                <textarea class="form-control"
                                                    name="reject_reason"
                                                    rows="3"
                                                    placeholder="Nhập lý do hủy (tùy chọn)"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-times me-1"></i> Xác nhận hủy
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="20" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                    <span class="text-muted">Không có dữ liệu rút tiền</span>
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
                    {{ $withdraws->appends(request()->input())->links('layout.system.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection