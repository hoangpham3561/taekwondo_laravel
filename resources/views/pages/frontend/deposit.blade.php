@extends('layout.frontend.office')

@section('content')
<section class="office-content-wrapper">
    <div class="deposit">
        <div class="deposit-main-wrapper container mt-5 mt-lg-0">
            <div class="deposit-main">
                <h3 class="deposit-title px-2">NẠP TIÊU DÙNG</h3>
                <!-- Wallet summary cards-->
                <div class="row g-3 mb-4">
                    <div class="col-lg-auto col-md-4 col-sm-6 col-12">
                        <div class="office-card text-center">
                            <h6 class="mb-2 office-card-title">VÍ THANH KHOẢN</h6>
                            <h5 class="text-danger mb-3">{{ number_format($walletBalances['thankhoan'] ?? 0, 0, ',', '.') }} đ</h5>
                        </div>
                    </div>
                    <div class="col-lg-auto col-md-4 col-sm-6 col-12">
                        <div class="office-card text-center">
                            <h6 class="mb-2 office-card-title">VÍ THÀNH VIÊN</h6>
                            <h5 class="text-danger mb-3">{{ number_format($walletBalances['thanhvien'] ?? 0, 0, ',', '.') }} đ</h5>
                        </div>
                    </div>
                    <div class="col-lg-auto col-md-4 col-sm-6 col-12">
                        <div class="office-card text-center">
                            <h6 class="mb-2 office-card-title">VÍ TIÊU DÙNG</h6>
                            <h5 class="text-danger mb-3">{{ number_format($walletBalances['tieudung'] ?? 0, 0, ',', '.') }} đ</h5>
                        </div>
                    </div>
                    <div class="col-lg-auto col-md-4 col-sm-6 col-12">
                        <div class="office-card text-center">
                            <h6 class="mb-2 office-card-title">TAEKWONDO ĐỒNG PHÚ</h6>
                            <h5 class="text-danger mb-3">{{ number_format($walletBalances['odicaffee'] ?? 0, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-auto col-md-4 col-sm-6 col-12">
                        <div class="office-card text-center">
                            <h6 class="mb-2 office-card-title">VÍ KHUYẾN MÃI</h6>
                            <h5 class="text-danger mb-3">{{ number_format($walletBalances['khuyenmai'] ?? 0, 0, ',', '.') }} đ</h5>
                        </div>
                    </div>
                </div>
                <!-- Package cards-->
                <p class="mb-2 history-title">Các gói nạp tiêu dùng:</p>
                <div class="row g-3 mb-4 justify-content-around">
                    @foreach($products as $product)
                    <div class="col-lg-auto col-md-4 col-sm-6 col-12">
                        <div class="office-card text-center">
                            <h6 class="mb-2 office-card-title">{{ $product->Name }}</h6>
                            <h6 class="text-danger mb-3">{{ number_format($product->Amount, 0, ',', '.') }} đ</h6>
                            <button
                                class="btn btn-odiva-office btn-deposit"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#depositModal"
                                data-product-id="{{ $product->ProductID }}"
                                data-product-name="{{ $product->Name }}"
                                data-product-amount="{{ $product->Amount }}">
                                Nạp
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- History table-->
                <h6 class="mb-3 history-title">Lịch sử nạp tiêu dùng</h6>
                <div class="office-card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>SỐ NẠP</th>
                                    <th>ẢNH CHỨNG TỪ</th>
                                    <th>TRẠNG THÁI</th>
                                    <th>NGÀY NẠP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ number_format($order->amount, 0, ',', '.') }} đ</td>
                                    <td>
                                        @if($order->proof_image)
                                        <a href="{{ $order->proof_image }}" target="_blank" title="Xem ảnh chứng từ">
                                            <img src="{{ $order->proof_image }}" alt="Ảnh chứng từ" width="100" style="border-radius: 4px; cursor: pointer;">
                                        </a>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->status === 'Y')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Đã duyệt
                                        </span>
                                        @elseif($order->status === 'C')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Đã hủy
                                        </span>
                                        @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>Chờ xác nhận
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->created_at ? $order->created_at->format('H:i d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                            <span class="text-muted">Chưa có lịch sử nạp tiền</span>
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

<!-- Deposit Modal-->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background: #fff;">
            <div class="modal-header border-0" style="background: #211706;">
                <h5 class="modal-title text-white" id="depositModalLabel">
                    <i class="fas fa-wallet me-2"></i>
                    <strong>Nạp tiêu dùng</strong>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="form-default" method="POST" action="{{ route($userPrefix . '.doDeposit') }}" enctype="multipart/form-data">
                <div class="modal-body pt-4">
                    @csrf
                    <input type="hidden" id="modalProductId" name="product_id" value="">

                    <div class="office-card mb-4">
                        <div class="text-center mb-4 pb-4 border-bottom">
                            <h6 class="mb-2 text-muted small">
                                Gói nạp
                            </h6>
                            <h5 class="mb-3 fw-bold text-primary" id="modalProductName">-</h5>
                            <h6 class="mb-0 text-muted small">
                                Số tiền
                            </h6>
                            <h4 class="mb-0 fw-bold text-danger" id="modalProductAmount">0 đ</h4>
                        </div>

                        <div class="text-center">
                            <h6 class="mb-3 text-muted small">
                                Thông tin tài khoản nhận tiền
                            </h6>

                            <div class="row g-3 text-start">
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-landmark text-primary" style="font-size: 20px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block mb-1">Ngân hàng</small>
                                        <strong class="text-dark" id="bankName">{{ $bankTransferConfig['bank_name'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-credit-card text-success" style="font-size: 20px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block mb-1">Số tài khoản</small>
                                            <strong class="text-dark" id="accountNumber">{{ $bankTransferConfig['account_number'] }}</strong>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-link p-0 ms-2"
                                                onclick="copyToClipboard('accountNumber')"
                                                title="Sao chép số tài khoản">
                                                <i class="fas fa-copy text-primary"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-user text-info" style="font-size: 20px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block mb-1">Tên tài khoản</small>
                                            <strong class="text-dark" id="accountName">{{ $bankTransferConfig['account_name'] }}</strong>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-link p-0 ms-2"
                                                onclick="copyToClipboard('accountName')"
                                                title="Sao chép tên tài khoản">
                                                <i class="fas fa-copy text-primary"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- QR Code Section -->
                                <div class="col-12">
                                    <div class="text-center p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fas fa-qrcode me-1"></i>
                                            Quét mã QR để chuyển khoản nhanh
                                        </small>
                                        <div class="d-flex justify-content-center">
                                            <img
                                                src=""
                                                alt="QR Code"
                                                id="qrImage"
                                                style="width: 200px; height: 200px; border: 2px solid #ddd; border-radius: 8px; padding: 10px; background: white;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="depositImage" class="form-label fw-semibold">
                            <i class="fas fa-image me-2 text-info"></i>
                            Upload ảnh chứng từ (nếu có)
                        </label>
                        <input
                            type="file"
                            class="form-control"
                            id="depositImage"
                            name="image"
                            accept="image/*"
                            >
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Vui lòng upload ảnh chứng từ thanh toán (JPG, PNG)
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="transferNote" class="form-label fw-semibold">
                            <i class="fas fa-comment-alt me-2 text-primary"></i>
                            Nội dung chuyển khoản
                        </label>
                        <div class="d-flex align-items-center p-2 bg-light rounded border">
                            <div class="flex-grow-1 me-2">
                                <input
                                    type="text"
                                    class="form-control border-0 bg-transparent shadow-none p-0"
                                    id="inputRef"
                                    name="note"
                                    value="{{ $orderCode ?? '' }}"
                                    placeholder="Nhập nội dung chuyển khoản"
                                    maxlength="255"
                                    readonly
                                    style="font-weight: 500; font-size: 14px;">
                            </div>
                            <button
                                type="button"
                                class="btn btn-sm btn-link p-1 flex-shrink-0"
                                onclick="copyRef()"
                                title="Sao chép nội dung chuyển khoản">
                                <i class="fas fa-copy text-primary" style="font-size: 16px;"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Lưu ý: <strong>Hãy nhập đầy đủ nội dung chuyển khoản vào trong phần chuyển khoản ngân hàng</strong>
                        </small>

                    </div>

                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-success fw-bold px-4 py-2 shadow" style="background: linear-gradient(90deg, #22c55e 0%, #0ea5e9 100%); border: none; color: #fff; border-radius: 999px; font-size: 1.1rem;">
                        <i class="fas fa-check me-2"></i>
                        Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const depositModal = document.getElementById('depositModal');
        const noteInput = document.getElementById('inputRef');
        const qrImage = document.getElementById('qrImage');
        const accountNumber = document.getElementById('accountNumber').textContent.trim();
        const accountName = document.getElementById('accountName').textContent.trim();
        const bankCode = @json($bankTransferConfig['bank_code']);

        depositModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productAmount = button.getAttribute('data-product-amount');
            const transferNote = 'NAP' + Date.now() + Math.random().toString(36).slice(2, 6).toUpperCase();

            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductAmount').textContent = new Intl.NumberFormat('vi-VN').format(productAmount) + ' đ';
            noteInput.value = transferNote;
            qrImage.src = `https://img.vietqr.io/image/${encodeURIComponent(bankCode)}-${encodeURIComponent(accountNumber)}-compact2.png?amount=${parseInt(productAmount, 10)}&addInfo=${encodeURIComponent(transferNote)}&accountName=${encodeURIComponent(accountName)}`;
        });

        const pendingDepositId = @json(session('pending_deposit_id'));
        if (pendingDepositId) {
            const statusUrlTemplate = @json(route($userPrefix . '.deposit.status', ['deposit' => '__ID__']));
            const statusUrl = statusUrlTemplate.replace('__ID__', pendingDepositId);
            const interval = setInterval(async () => {
                try {
                    const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) return;
                    const data = await response.json();
                    if (data.status === 'Y') {
                        clearInterval(interval);
                        window.location.reload();
                    }
                } catch (e) {
                    console.error('Không thể kiểm tra trạng thái nạp tiền', e);
                }
            }, 5000);
        }
    });

    // Function to copy to clipboard
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent.trim();

        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const btn = event.target.closest('button');
            const icon = btn.querySelector('i');
            icon.classList.remove('fa-copy');
            icon.classList.add('fa-check', 'text-success');

            setTimeout(function() {
                icon.classList.remove('fa-check', 'text-success');
                icon.classList.add('fa-copy', 'text-primary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }

    // Function to copy input value to clipboard
    function copyInputToClipboard(inputId) {
        const inputElement = document.getElementById(inputId);
        const text = inputElement.value.trim();

        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const btn = event.target.closest('button');
            const icon = btn.querySelector('i');
            icon.classList.remove('fa-copy');
            icon.classList.add('fa-check', 'text-success');

            setTimeout(function() {
                icon.classList.remove('fa-check', 'text-success');
                icon.classList.add('fa-copy', 'text-primary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }

    function copyRef() {
        var copyText = document.getElementById("inputRef");
        copyText.select();
        document.execCommand("copy");
    }
</script>
@endsection
