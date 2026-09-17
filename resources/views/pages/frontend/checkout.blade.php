@extends('layout.frontend.frontend')
@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Thanh toán</h2>
                    <div class="bt-option">
                        <a href="{{ route($userPrefix . '.index') }}">Trang chủ</a>
                        <a href="{{ route($userPrefix . '.cart') }}">Giỏ hàng</a>
                        <span>Thanh toán</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="pricing-section spad">
    <div class="container custom-w">
        <div class="row">
            <div class="col-md-12">
                <nav class="custom-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route($userPrefix . '.cart') }}">GIỎ HÀNG</a></li>
                        <li class="breadcrumb-item active" aria-current="page">THANH TOÁN</li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title ?? '' }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row box-marg-50">
            <div class="col-md-8 padd">
                <form id="checkout-form" method="POST" action="{{ route($userPrefix . '.submitcheckout') }}">
                    @csrf
                    <input type="hidden" name="shipping_method" id="shipping_method" value="fast">
                    <input type="hidden" name="shipping_fee" id="shipping_fee_input" value="0">

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="custom-label d-block mb-1">Họ & tên <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control custom-default-control"
                                id="full_name"
                                name="full_name"
                                value="{{ $user->FullName ?? '' }}"
                                required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="custom-label d-block mb-1">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control custom-default-control"
                                id="phone"
                                name="phone"
                                value="{{ $user->Phone ?? '' }}"
                                required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="custom-label d-block mb-1">Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control custom-default-control"
                                id="address"
                                name="address"
                                value="{{ $user->Address_ship ?? $user->Address ?? '' }}"
                                required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="custom-label d-block mb-1">Lưu ý</label>
                            <textarea class="form-control custom-default-control"
                                id="note"
                                name="note"
                                rows="3"
                                placeholder="Ghi chú cho người giao hàng..."></textarea>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="custom-label d-block mb-3 custom-main">Phương thức vận chuyển</label>
                            <div class="row list-check">
                                <div class="col-md-6 mb-4">
                                    <div class="box-check-sm position-relative">
                                        <div class="form-check">
                                            <input class="form-check-input shipping-method-radio"
                                                type="radio"
                                                name="shipping_method"
                                                id="shipping_fast"
                                                value="0"
                                                data-fee="0"
                                                data-name="Vận chuyển nhanh"
                                                checked>
                                            <label class="form-check-label" for="shipping_fast">
                                                Vận chuyển nhanh
                                            </label>
                                        </div>
                                        <div class="text">1-2 ngày</div>
                                        <div class="price" id="shipping_price_fast">0 đ</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="box-check-sm position-relative">
                                        <div class="form-check">
                                            <input class="form-check-input shipping-method-radio"
                                                type="radio"
                                                name="shipping_method"
                                                id="shipping_economy"
                                                value="1"
                                                data-fee="0"
                                                data-name="Vận chuyển tiết kiệm">
                                            <label class="form-check-label" for="shipping_economy">
                                                Vận chuyển tiết kiệm
                                            </label>
                                        </div>
                                        <div class="text">5-6 ngày</div>
                                        <div class="price" id="shipping_price_economy">0 đ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="custom-label d-block mb-3 custom-main">Chọn phương pháp thanh toán</label>
                            <div class="list-check">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="0" checked>
                                    <label class="form-check-label" for="payment_cod">Thanh toán COD</label>
                                </div>
                                <!-- Có thể thêm các phương thức thanh toán khác sau -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 padd">
                <div class="block-summary mb-3">
                    <div class="title text-uppercase mb-4">Giỏ hàng</div>
                    <div class="list-cart-sm mb-3">
                        @foreach($cartItems as $item)
                        <div class="item-prod-cart position-relative">
                            <div class="thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{ $item['product']->product_image_url ?? asset('client/images/sp3.png') }}"
                                        alt="{{ $item['product']->ProductName }}"
                                        onerror="this.src='{{ asset('client/images/sp3.png') }}'">
                                </a>
                            </div>
                            <div class="align-items-center content d-flex justify-content-between">
                                <div class="info">
                                    <div class="title-sm">
                                        <a href="javascript:void(0)">
                                            {{ $item['product']->ProductName }}
                                        </a>
                                    </div>
                                    <div class="quality">x{{ $item['quantity'] }}</div>
                                </div>
                                <div class="price">{{ number_format($item['product']->Price * $item['quantity'], 0, ',', '.') }} đ</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="content">
                        <div class="item-gift position-relative mb-4">
                            <div class="icon">
                                <img src="{{ asset('client/images/shop/icon-gift.svg') }}" alt="Gift">
                            </div>
                            <input class="form-control custom" type="text" placeholder="Mã giảm giá">
                            <button class="btn btn-apply">Áp dụng</button>
                        </div>
                        <div class="align-items-center d-flex item justify-content-between">
                            <div class="text">Tạm tính</div>
                            <div class="value" id="checkout-subtotal">{{ number_format($subtotal ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="align-items-center d-flex item justify-content-between">
                            <div class="text">Ưu đãi (0%)</div>
                            <div class="value">00</div>
                        </div>
                        <div class="align-items-center d-flex item justify-content-between">
                            <div class="text">Phí vận chuyển</div>
                            <div class="value" id="checkout-shipping-fee">0 đ</div>
                        </div>
                        <div class="align-items-center d-flex item justify-content-between custom-total">
                            <div class="text">Thành tiền</div>
                            <div class="value" id="checkout-total">{{ number_format($subtotal ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                        <button type="submit" form="checkout-form" class="btn btn-chect-out w-100" id="btn-checkout-submit">
                            THANH TOÁN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Lấy giá trị từ server
    const subtotal = parseFloat('{{ $subtotal ?? 0 }}');
    let currentShippingFee = 0; // Mặc định là vận chuyển nhanh
    let currentTotal = subtotal + currentShippingFee;

    // Format số tiền
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(Math.round(price)) + ' đ';
    }

    // Cập nhật tổng tiền khi thay đổi phương thức vận chuyển
    function updateShippingAndTotal() {
        const selectedShipping = document.querySelector('.shipping-method-radio:checked');

        if (selectedShipping) {
            // Lấy phí vận chuyển từ data attribute
            currentShippingFee = parseFloat(selectedShipping.getAttribute('data-fee')) || 0;
            const shippingMethod = selectedShipping.value;
            const shippingName = selectedShipping.getAttribute('data-name');

            // Cập nhật hidden inputs
            document.getElementById('shipping_method').value = shippingMethod;
            document.getElementById('shipping_fee_input').value = currentShippingFee;

            // Tính lại tổng tiền
            currentTotal = subtotal + currentShippingFee;

            // Cập nhật UI
            document.getElementById('checkout-shipping-fee').textContent = formatPrice(currentShippingFee);
            document.getElementById('checkout-total').textContent = formatPrice(currentTotal);
        }
    }

    document.querySelectorAll('.shipping-method-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            updateShippingAndTotal();
        });
    });

    updateShippingAndTotal();

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const fullName = document.getElementById('full_name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const address = document.getElementById('address').value.trim();

        if (!fullName || !phone || !address) {
            alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
            return false;
        }

        const selectedShipping = document.querySelector('.shipping-method-radio:checked');
        if (!selectedShipping) {
            alert('Vui lòng chọn phương thức vận chuyển.');
            return false;
        }

        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPayment) {
            alert('Vui lòng chọn phương thức thanh toán.');
            return false;
        }

        const btn = document.getElementById('btn-checkout-submit');
        btn.disabled = true;
        btn.innerHTML = 'Đang xử lý...';

        this.submit();
    });
</script>
@endsection
