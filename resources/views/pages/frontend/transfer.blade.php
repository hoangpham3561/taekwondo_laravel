@extends('layout.frontend.office')

@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <h3 class="office-title px-2">CHUYỂN KHOẢN NỘI BỘ</h3>
                <div class="row g-4 mx-0 mt-1">
                    <div class="col-lg-12 col-md-12">
                        <div class="office-card">
                            <div class="office-card-tiltle">
                                <span>Thông tin chuyển khoản</span>
                            </div>
                            <form method="POST" action="{{ route($userPrefix . '.doTransfer') }}" id="transferForm" class="form-default">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="wallet_type">
                                        Chọn ví chuyển <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control form-office-card" id="wallet_type" name="wallet_type" required>
                                        <option value="">-- Chọn ví muốn chuyển --</option>
                                        <option value="THANKHOAN" data-balance="{{ $walletBalances['thankhoan'] ?? 0 }}">
                                            Ví Thanh Khoản (Số dư: {{ number_format($walletBalances['thankhoan'] ?? 0, 0, ',', '.') }} đ)
                                        </option>
                                        <option value="THANHVIEN" data-balance="{{ $walletBalances['thanhvien'] ?? 0 }}">
                                            Ví Thành Viên (Số dư: {{ number_format($walletBalances['thanhvien'] ?? 0, 0, ',', '.') }} đ)
                                        </option>
                                        <option value="TIEUDUNG" data-balance="{{ $walletBalances['tieudung'] ?? 0 }}">
                                            Ví Tiêu Dùng (Số dư: {{ number_format($walletBalances['tieudung'] ?? 0, 0, ',', '.') }} đ)
                                        </option>
                                    </select>
                                </div>

                                <!-- Số tiền chuyển -->
                                <div class="mb-3">
                                    <label class="form-label" for="amount">
                                        Số tiền chuyển <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control form-office-card"
                                        id="amount"
                                        name="amount"
                                        type="number"
                                        placeholder="Nhập số tiền (tối thiểu 1,000 đ)"
                                        min="1000"
                                        step="1000"
                                        value="{{ old('amount') }}"
                                        required>
                                    <small class="text-muted d-block mt-1">
                                        Số tiền tối thiểu: <strong>1,000 đ</strong>
                                    </small>
                                </div>

                                <!-- Người nhận -->
                                <div class="mb-3">
                                    <label class="form-label" for="receiver">
                                        Người nhận <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control form-office-card"
                                        id="receiver"
                                        name="receiver"
                                        type="text"
                                        placeholder="Nhập Username, Email của người nhận"
                                        value="{{ old('receiver') }}"
                                        required>
                                    <small class="text-muted d-block mt-1">
                                        Có thể nhập Username, Email của người nhận (đại lý)
                                    </small>
                                    <div id="receiverError" class="text-danger small mt-1" style="display: none;"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="receiverInfo">
                                        Thông tin người nhận
                                    </label>
                                    <input class="form-control form-office-card" readonly
                                        id="receiverInfo"
                                        type="text"
                                        placeholder="Thông tin sẽ hiển thị sau khi tìm thấy người nhận"
                                        style="background-color: #f5f5f5;">
                                </div>

                                <div class="d-grid">
                                    <button class="btn office-submit" type="submit">XÁC NHẬN CHUYỂN KHOẢN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lịch sử chuyển -->
                <h3 class="office-title px-2 mt-4">LỊCH SỬ CHUYỂN</h3>
                <div class="office-card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>MÃ GD</th>
                                    <th>SỐ TIỀN</th>
                                    <th>VÍ</th>
                                    <th>NGƯỜI NHẬN</th>
                                    <th>NGÀY CHUYỂN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sentTransfers as $index => $transfer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <code class="text-primary">{{ $transfer->SerialCode ?? '-' }}</code>
                                    </td>
                                    <td class="text-danger fw-bold">
                                        {{ number_format($transfer->AmountTransfer, 0, ',', '.') }} đ
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $transfer->Currency ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($transfer->fUser)
                                        {{ $transfer->fUser->UserName }}
                                        @if($transfer->fUser->FullName)
                                        <br><small class="text-muted">({{ $transfer->fUser->FullName }})</small>
                                        @endif
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $transfer->RequestDate ? $transfer->RequestDate->format('H:i d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                            <span class="text-muted">Chưa có lịch sử chuyển khoản</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lịch sử nhận -->
                <h3 class="office-title px-2 mt-4">LỊCH SỬ NHẬN</h3>
                <div class="office-card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>MÃ GD</th>
                                    <th>SỐ TIỀN</th>
                                    <th>VÍ</th>
                                    <th>NGƯỜI GỬI</th>
                                    <th>NGÀY NHẬN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receivedTransfers as $index => $transfer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <code class="text-success">{{ $transfer->SerialCode ?? '-' }}</code>
                                    </td>
                                    <td class="text-success fw-bold">
                                        {{ number_format($transfer->AmountTransfer, 0, ',', '.') }} đ
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $transfer->Currency ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($transfer->fUser)
                                        {{ $transfer->fUser->UserName }}
                                        @if($transfer->fUser->FullName)
                                        <br><small class="text-muted">({{ $transfer->fUser->FullName }})</small>
                                        @endif
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $transfer->RequestDate ? $transfer->RequestDate->format('H:i d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                            <span class="text-muted">Chưa có lịch sử nhận khoản</span>
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
    document.addEventListener('DOMContentLoaded', function() {
        const receiverInput = document.getElementById('receiver');
        const receiverInfoInput = document.getElementById('receiverInfo');
        const receiverError = document.getElementById('receiverError');
        let searchTimeout = null;

        function debounce(func, wait) {
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(searchTimeout);
                    func(...args);
                };
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(later, wait);
            };
        }

        function searchUser(query) {
            if (!query || query.trim().length < 2) {
                receiverInfoInput.value = '';
                receiverInfoInput.style.backgroundColor = '#f5f5f5';
                receiverError.style.display = 'none';
                return;
            }

            receiverInfoInput.value = 'Đang tìm kiếm...';
            receiverInfoInput.style.backgroundColor = '#fff3cd';
            receiverError.style.display = 'none';

            fetch('{{ route($userPrefix . ".transfer.searchUser") }}?q=' + encodeURIComponent(query.trim()), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.user) {
                        receiverInfoInput.value = data.user.userName +
                            (data.user.fullName ? ' (' + data.user.fullName + ')' : '');
                        receiverInfoInput.style.backgroundColor = '#d1e7dd';
                        receiverError.style.display = 'none';

                        receiverInput.setAttribute('data-user-id', data.user.userID);
                    } else {
                        receiverInfoInput.value = '';
                        receiverInfoInput.style.backgroundColor = '#f5f5f5';
                        receiverError.textContent = data.message || 'Không tìm thấy người dùng';
                        receiverError.style.display = 'block';
                        receiverInput.removeAttribute('data-user-id');
                    }
                })
                .catch(error => {
                    console.error('Error searching user:', error);
                    receiverInfoInput.value = '';
                    receiverInfoInput.style.backgroundColor = '#f5f5f5';
                    receiverError.textContent = 'Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại.';
                    receiverError.style.display = 'block';
                });
        }

        const debouncedSearch = debounce(function(query) {
            searchUser(query);
        }, 500);

        receiverInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            debouncedSearch(query);
        });

        receiverInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                receiverInfoInput.value = '';
                receiverInfoInput.style.backgroundColor = '#f5f5f5';
                receiverError.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
