@extends('layout.frontend.frontend')
@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Giỏ hàng</h2>
                    <div class="bt-option">
                        <a href="{{ route($userPrefix . '.index') }}">Trang chủ</a>
                        <span>Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="pricing-section spad cart-modern-section">
    <div class="container custom-w">
        <div class="row">
            <div class="col-md-12">
                <nav class="custom-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">GIỎ HÀNG</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row box-marg-50">
            <div class="col-md-8 padd">
                <div class="list-cart mb-3">
                    @forelse($cartItems ?? [] as $item)
                    <div class="item-prod-cart position-relative cart-modern-item" data-cart-id="{{ $item['cart_id'] }}">
                        <div class="thumb">
                            <a href="javascript:void(0)">
                                <img src="{{ $item['product']->product_image_url }}" alt="{{ $item['product']->ProductName }}">
                            </a>
                        </div>
                        <div class="content d-flex justify-content-between">
                            <div class="info">
                                <div class="title">
                                    <a href="javascript:void(0)">
                                        {{ $item['product']->ProductName }}
                                    </a>
                                </div>
                                <div class="price">{{ number_format($item['product']->Price, 0, ',', '.') }} đ</div>
                            </div>
                            <div class="c-right text-end">
                                <a class="d-block link-icon-remove" href="#" onclick="removeFromCart({{ $item['cart_id'] }}); return false;">
                                    <img src="{{ asset('client/images/shop/icon-remove.svg') }}" alt="Xóa">
                                </a>
                                <div class="number control-number">
                                    <span class="minus" onclick="decreaseQuantity({{ $item['cart_id'] }})">-</span>
                                    <input type="text"
                                        id="quantity-{{ $item['cart_id'] }}"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        max="{{ $item['product']->Amount ?? 999 }}"
                                        onchange="updateQuantity({{ $item['cart_id'] }})">
                                    <span class="plus" onclick="increaseQuantity({{ $item['cart_id'] }})">+</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-cart-container text-center py-5 px-3">
                            <div class="empty-cart-icon mb-4">
                            </div>
                            <h3 class="empty-cart-title mb-3">
                                Giỏ hàng của bạn đang trống
                            </h3>
                            <p class="empty-cart-description mb-4">
                                Hãy khám phá các sản phẩm tuyệt vời và thêm chúng vào giỏ hàng nhé!
                            </p>
                            <a href="{{ route($userPrefix . '.course') }}" class="btn btn-shopping">
                                <i class="fas fa-shopping-bag me-2"></i>
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-4 padd">
                <div class="block-summary mb-3 cart-modern-summary">
                    <div class="title text-uppercase mb-4">Tổng tiền hàng</div>
                    <div class="content">
                        <div class="align-items-center d-flex item justify-content-between">
                            <div class="text">Tạm tính</div>
                            <div class="value" id="subtotal">{{ number_format($subtotal ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="align-items-center d-flex item justify-content-between">
                            <div class="text">Ưu đãi (0%)</div>
                            <div class="value custom-price">00</div>
                        </div>
                        <div class="align-items-center d-flex item justify-content-between">
                            <div class="text">Phí vận chuyển</div>
                            <div class="value" id="shipping-fee">{{ number_format($shippingFee ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="align-items-center d-flex item justify-content-between custom-total">
                            <div class="text">Thành tiền</div>
                            <div class="value" id="total">{{ number_format($total ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="item-gift position-relative mb-4">
                            <div class="icon">
                                <img src="{{ asset('client/images/shop/icon-gift.svg') }}" alt="Gift">
                            </div>
                            <input class="form-control custom" type="text" placeholder="Mã giảm giá">
                            <button class="btn btn-apply">Áp dụng</button>
                        </div>
                        <a class="link-chect-out w-100" href="{{ route($userPrefix . '.checkout') }}">THANH TOÁN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const cartUpdateUrl = "{{ route($userPrefix . '.cart.update', ':id') }}";
    const cartRemoveUrl = "{{ route($userPrefix . '.cart.remove', ':id') }}";

    let isUpdating = false;

    function decreaseQuantity(cartId) {
        if (isUpdating) return;

        const input = document.getElementById('quantity-' + cartId);
        if (!input) return;

        const currentValue = parseInt(input.value) || 1;
        if (currentValue > 1) {
            isUpdating = true;
            input.value = currentValue;
            updateQuantity(cartId);
            setTimeout(() => { isUpdating = false; }, 100);
        }
    }

    function increaseQuantity(cartId) {
        if (isUpdating) return;

        const input = document.getElementById('quantity-' + cartId);
        if (!input) return;

        const max = parseInt(input.getAttribute('max')) || 999;
        const currentValue = parseInt(input.value) || 1;
        if (currentValue < max) {
            isUpdating = true;
            input.value = currentValue;
            updateQuantity(cartId);
            setTimeout(() => { isUpdating = false; }, 100);
        }
    }

    function updateQuantity(cartId) {
        const input = document.getElementById('quantity-' + cartId);
        if (!input) return;

        const quantity = parseInt(input.value) || 1;
        if (quantity < 1) {
            input.value = 1;
            return;
        }

        const url = cartUpdateUrl.replace(':id', cartId);

        fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const subtotalEl = document.getElementById('subtotal');
                    const shippingEl = document.getElementById('shipping-fee');
                    const totalEl = document.getElementById('total');

                    if (subtotalEl) subtotalEl.textContent = formatPrice(data.subtotal) + ' đ';
                    if (shippingEl) shippingEl.textContent = formatPrice(data.shipping_fee) + ' đ';
                    if (totalEl) totalEl.textContent = formatPrice(data.total) + ' đ';
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            })
            .finally(() => {
                isUpdating = false; // Reset flag sau khi hoàn thành
            });
    }

    function removeFromCart(cartId) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }

        const url = cartRemoveUrl.replace(':id', cartId);

        fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const itemElement = document.querySelector(`[data-cart-id="${cartId}"]`);
                    if (itemElement) {
                        itemElement.remove();
                    }

                    const subtotalEl = document.getElementById('subtotal');
                    const shippingEl = document.getElementById('shipping-fee');
                    const totalEl = document.getElementById('total');

                    if (subtotalEl) subtotalEl.textContent = formatPrice(data.subtotal) + ' đ';
                    if (shippingEl) shippingEl.textContent = formatPrice(data.shipping_fee) + ' đ';
                    if (totalEl) totalEl.textContent = formatPrice(data.total) + ' đ';

                    if (data.subtotal === 0) {
                        location.reload();
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            });
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
</script>
@endsection
