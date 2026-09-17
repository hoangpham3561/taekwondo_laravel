@extends('layout.frontend.office')

@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <div class="report-header d-flex justify-content-between align-items-center px-2 mb-4">
                    <h3 class="office-title d-flex justify-content-between align-items-center">
                        BÁO CÁO
                        <div class="office-mobile-toggle d-lg-none">
                            <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                                <img src="{{ asset('client/images/launchpad/arrow-down.svg') }}" alt="icon">
                            </button>
                        </div>
                    </h3>
                    <div class="report-date-range">
                        <div class="report-date-input-wrapper">
                            <form method="GET" action="{{ route($userPrefix . '.commission') }}" id="dateFilterForm">
                                <div class="date-range">
                                    <input class="form-control" id="fromDate" name="from_date" type="date"
                                        value="{{ request('from_date', date('Y-m-01')) }}">
                                    <span class="mx-2">~</span>
                                    <input class="form-control" id="toDate" name="to_date" type="date"
                                        value="{{ request('to_date', date('Y-m-d')) }}">
                                    <i class="fas fa-calendar-alt report-date-icon"></i>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-12">
                        <div class="office-card h-100 d-flex align-items-center justify-content-between p-3 shadow-sm" style="background: #e9f6ff;">
                            <div>
                                <div class="fw-semibold text-dark">Số dư ví PA</div>
                                <div class="fs-5 text-success">
                                    {{ $user->CRWallet ?? 0 }} <span class="fw-normal" style="font-size: 16px;">PA</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route($userPrefix . '.wallet') }}" class="btn btn-outline-success ms-3">Rút tiền</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mx-0 mt-1">
                    <div class="col-12">
                        <div class="office-card report-card">
                            <div class="report-table-wrapper">
                                <table class="table report-table">
                                    <thead>
                                        <tr>
                                            <th class="report-table-header">LOẠI</th>
                                            <th class="report-table-header">SỐ ĐIỂM</th>
                                            <th class="report-table-header">TOOL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($commissions as $commission)
                                        <tr class="report-table-row">
                                            <td class="report-table-cell">{{ $commission['type_name'] ?? 'N/A' }}</td>
                                            <td class="report-table-cell">{{ $commission['total_point'] ?? 0 }} PA</td>
                                            <td class="report-table-cell">
                                                <a href="{{ route($userPrefix . '.commission.detail', ['type' => $commission['type'] ?? '', 'from_date' => request('from_date', date('Y-m-01')), 'to_date' => request('to_date', date('Y-m-d'))]) }}"
                                                    class="btn report-detail-btn">
                                                    CHI TIẾT
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="report-table-row">
                                            <td colspan="3" class="report-table-cell text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                                    <span class="text-muted">Chưa có lịch sử hoa hồng</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto submit form khi thay đổi date
        $('#fromDate, #toDate').on('change', function() {
            $('#dateFilterForm').submit();
        });
    });
</script>
@endpush
@endsection
