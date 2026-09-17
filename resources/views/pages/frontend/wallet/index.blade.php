@extends('layout.frontend.office')

@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <h3 class="office-title px-2 d-flex justify-content-between align-items-center">
                    RÚT TIỀN
                    <div class="office-mobile-toggle d-lg-none">
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                            <img src="{{ asset('client/images/launchpad/arrow-down.svg') }}" alt="icon">
                        </button>
                    </div>
                </h3>
                <div class="row g-4 mx-0 mt-1">
                    <div class="col-lg-12 col-md-12">
                        <div class="office-card">
                            <div class="office-card-tiltle">
                                <span>Thông tin rút tiền</span>
                            </div>

                            @php
                            $hasBankInfo = !empty($user->NganHang) && !empty($user->STK) && !empty($user->Ten_TK);
                            @endphp

                            @if(!$hasBankInfo)
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Thông báo:</strong> Bạn chưa cập nhật thông tin tài khoản ngân hàng.
                                Vui lòng <a href="{{ route($userPrefix . '.dashboard') }}" class="alert-link">cập nhật thông tin ngân hàng</a> trước khi rút tiền.
                            </div>
                            @endif

                            <form method="POST" action="{{ route($userPrefix . '.doWithdraw') }}" id="withdrawForm" class="form-default">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="wallet_type">
                                        Chọn ví rút <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control form-office-card" id="wallet_type" name="wallet_type" required {{ !$hasBankInfo ? 'disabled' : '' }}>
                                        <option value="CRWallet" data-balance="{{ $user->CRWallet ?? 0 }}">
                                            Ví PA (Số dư: {{ $user->CRWallet ?? 0 }} PA)
                                        </option>
                                    </select>
                                </div>

                                <!-- Số tiền rút -->
                                <div class="mb-3">
                                    <label class="form-label" for="amount">
                                        Số PA muốn rút <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control form-office-card"
                                        id="amount"
                                        name="amount"
                                        type="number"
                                        step="5"
                                        min="{{ $withdrawLimit }}"
                                        placeholder="Nhập số PA (tối thiểu {{ $withdrawLimit }} PA)"
                                        value="{{ old('amount') }}"
                                        required
                                        {{ !$hasBankInfo ? 'disabled' : '' }}>
                                    <small class="text-muted d-block mt-1">
                                        Số dư PA hiện tại: <strong>{{ number_format($user->CRWallet ?? 0, 2, ',', '.') }} PA</strong>
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <div class="office-card" style="background: #f8f9fa; border: 1px solid #dee2e6;">
                                        <div class="p-3">
                                            <h6 class="mb-3 fw-bold">
                                                <i class="fas fa-calculator me-2 text-primary"></i>
                                                Thông tin quy đổi
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">Tỉ giá:</span>
                                                        <span class="fw-semibold">1 PA = {{ number_format($exchangeRate, 0, ',', '.') }} VND</span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">Số PA rút:</span>
                                                        <span class="fw-semibold" id="paAmountDisplay">0 PA</span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">Quy đổi sang VND:</span>
                                                        <span class="fw-semibold text-primary" id="vndAmountDisplay">0 VND</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 border-top pt-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">Thuế thu nhập cá nhân (10%):</span>
                                                        <span class="fw-semibold text-warning" id="taxAmountDisplay">0 VND</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 border-top pt-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold">Thực nhận:</span>
                                                        <span class="fw-bold fs-5 text-success" id="finalAmountDisplay">0 VND</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Thông tin tài khoản ngân hàng</label>
                                    @if($hasBankInfo)
                                    <div class="bg-light p-3 rounded">
                                        <div class="mb-2">
                                            <strong>Tên ngân hàng:</strong> {{ $user->NganHang }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Số tài khoản:</strong> {{ $user->STK }}
                                        </div>
                                        <div>
                                            <strong>Tên chủ tài khoản:</strong> {{ $user->Ten_TK }}
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Để thay đổi thông tin, vui lòng <a href="{{ route($userPrefix . '.dashboard') }}">cập nhật tại đây</a>
                                            </small>
                                        </div>
                                    </div>
                                    @else
                                    <div class="bg-light p-3 rounded text-center">
                                        <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                        <span class="text-muted">Chưa có thông tin ngân hàng</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="d-grid">
                                    <button class="btn office-submit" type="submit" {{ !$hasBankInfo ? 'disabled' : '' }}>
                                        {{ $hasBankInfo ? 'XÁC NHẬN RÚT TIỀN' : 'VUI LÒNG CẬP NHẬT THÔNG TIN NGÂN HÀNG' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lịch sử rút tiền -->
                <h3 class="office-title px-2 mt-4">LỊCH SỬ RÚT TIỀN</h3>
                <div class="office-card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>MÃ GD</th>
                                    <th>SỐ TIỀN</th>
                                    <th>Tỉ giá</th>
                                    <th>Thành tiền</th>
                                    <th>Thuế TNCN</th>
                                    <th>Thực nhận</th>
                                    <th>TRẠNG THÁI</th>
                                    <th>NGÀY YÊU CẦU</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawHistory as $index => $withdraw)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <code class="text-primary">{{ $withdraw->SerialCode ?? '-' }}</code>
                                    </td>
                                    <td class="text-danger fw-bold">
                                        {{ number_format($withdraw->AmountTransfer, 0, ',', '.') }} {{ $withdraw->Currency ?? '-' }}
                                    </td>
                                    <td>
                                        1PA = {{ $withdraw->Rate }} VND
                                    </td>
                                    <td>
                                        {{ number_format($withdraw->TaxAmount + $withdraw->FinalAmount, 0, ',', '.') }} VND
                                    </td>
                                    <td>
                                        {{ number_format($withdraw->TaxAmount, 0, ',', '.') }} ({{$withdraw->Fee}}%)
                                    </td>
                                    <td>
                                        {{ number_format($withdraw->FinalAmount, 0, ',', '.') }} VND
                                    </td>
                                    <td>
                                        @if($withdraw->RequestStatus == 'Y')
                                        <span class="badge bg-success">Đã duyệt</span>
                                        @elseif($withdraw->RequestStatus == 'N')
                                        <span class="badge bg-warning">Chờ duyệt</span>
                                        @else
                                        <span class="badge bg-danger">Từ chối</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $withdraw->RequestDate ? $withdraw->RequestDate->format('H:i d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="20" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                            <span class="text-muted">Chưa có lịch sử rút tiền</span>
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
</section>

@push('scripts')
<script>
    $(document).ready(function() {
        const exchangeRate = {{ $exchangeRate ?? 0 }};
        const taxRate = {{ $fee ?? 0 }};
        const maxPA = {{ $user->CRWallet ?? 0 }};

        // Tính toán khi nhập số PA
        $('#amount').on('input', function() {
            let paAmount = parseFloat($(this).val()) || 0;

            // Kiểm tra không vượt quá số dư
            if (paAmount > maxPA) {
                $(this).val(maxPA);
                paAmount = maxPA;
                alert('Số lượng PA không được vượt quá số dư hiện tại!');
            }

            // Hiển thị số PA
            $('#paAmountDisplay').text(paAmount.toLocaleString('vi-VN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' PA');

            // Tính VND
            let vndAmount = paAmount * exchangeRate;
            $('#vndAmountDisplay').text(vndAmount.toLocaleString('vi-VN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }) + ' VND');

            // Tính thuế (10%)
            let taxAmount = vndAmount * taxRate;
            $('#taxAmountDisplay').text(taxAmount.toLocaleString('vi-VN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }) + ' VND');

            // Tính thực nhận (VND - Thuế)
            let finalAmount = vndAmount - taxAmount;
            $('#finalAmountDisplay').text(finalAmount.toLocaleString('vi-VN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }) + ' VND');
        });
    });
</script>
@endpush
@endsection