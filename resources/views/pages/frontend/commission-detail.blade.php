@extends('layout.frontend.office')

@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <div class="report-header d-flex justify-content-between align-items-center px-2 mb-4">
                    <h3 class="office-title d-flex justify-content-between align-items-center">
                        Chi tiết hoa hồng: {{ $typeName ?? 'N/A' }}
                        <div class="office-mobile-toggle d-lg-none">
                            <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar"><img src="{{ asset('client/images/launchpad/arrow-down.svg') }}" alt="icon"></button>
                        </div>
                    </h3>
                    <div class="report-date-range">
                        <div class="report-date-input-wrapper">
                            <form method="GET" action="{{ route($userPrefix . '.commission.detail') }}" id="dateFilterForm">
                                <input type="hidden" name="type" value="{{ request('type') }}">
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

                <!-- Bảng chi tiết -->
                <div class="row g-4 mx-0 mt-1">
                    <div class="col-12">
                        <div class="office-card report-card">
                            <div class="report-table-wrapper">
                                <table class="table report-table">
                                    <thead>
                                        <tr>
                                            <th class="report-table-header">SỐ ĐIỂM</th>
                                            <th class="report-table-header">NỘI DUNG</th>
                                            <th class="report-table-header">NGÀY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($commissions as $index => $commission)
                                        <tr class="report-table-row">
                                            <td class="report-table-cell text-success fw-bold">
                                                {{ number_format($commission['total_point'] ?? 0, 2, ',', '.') }} PA
                                            </td>
                                            <td class="report-table-cell">{{ $commission['note'] ?? 'N/A' }}</td>
                                            <td class="report-table-cell">
                                                {{ $commission['created_at'] ? \Carbon\Carbon::parse($commission['created_at'])->format('H:i d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="report-table-row">
                                            <td colspan="3" class="report-table-cell text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                                    <span class="text-muted">Chưa có lịch sử hoa hồng cho loại này</span>
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

                <!-- Tổng kết -->
                @if($commissions->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="office-card">
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <div class="fw-semibold text-dark">Tổng số điểm:</div>
                                <div class="fs-5 text-success fw-bold">
                                    {{ number_format($commissions->sum('total_point'), 2, ',', '.') }} PA
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#fromDate, #toDate').on('change', function() {
            $('#dateFilterForm').submit();
        });
    });
</script>
@endpush
@endsection
