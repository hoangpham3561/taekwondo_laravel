<?php

namespace App\Traits;

use App\Models\ProductCart;
use Illuminate\Support\Facades\Session;

trait CartManagement
{
    /**
     * Session key để lưu giỏ hàng
     */
    protected function getCartSessionKey(): string
    {
        return 'bags_cart_items';
    }

    /**
     * Lấy giỏ hàng từ session
     * 
     * @return array
     */
    protected function getCart(): array
    {
        return Session::get($this->getCartSessionKey(), []);
    }

    /**
     * Lưu giỏ hàng vào session
     * 
     * @param array $cart
     * @return void
     */
    protected function saveCart(array $cart): void
    {
        Session::put($this->getCartSessionKey(), $cart);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * 
     * @param int $productId
     * @param int $quantity
     * @return array ['success' => bool, 'message' => string, 'cart_count' => int|null]
     */
    protected function addToCart(int $productId, int $quantity): array
    {
        // Kiểm tra sản phẩm tồn tại và đang active
        $product = $this->findActiveCartProduct($productId);

        if (!$product) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã bị xóa.',
                'cart_count' => null,
            ];
        }

        // Kiểm tra số lượng còn lại
        if ($product->Amount !== null && $quantity > $product->Amount) {
            return [
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ. Còn lại: ' . $product->Amount . ' sản phẩm.',
                'cart_count' => null,
            ];
        }

        // Lấy giỏ hàng hiện tại
        $cart = $this->getCart();

        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        if (isset($cart[$productId])) {
            // Cộng thêm số lượng
            $newQuantity = $cart[$productId]['quantity'] + $quantity;

            // Kiểm tra lại số lượng tổng
            if ($product->Amount !== null && $newQuantity > $product->Amount) {
                $newQuantity = $product->Amount;
            }

            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            // Thêm mới sản phẩm
            $cart[$productId] = [
                'quantity' => $quantity,
                'added_at' => now()->toDateTimeString(),
            ];
        }

        // Lưu vào session
        $this->saveCart($cart);

        // Tính tổng số lượng sản phẩm trong giỏ hàng
        $totalQuantity = $this->getCartTotalQuantity($cart);

        return [
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
            'cart_count' => $totalQuantity,
        ];
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     * 
     * @param int $productId
     * @param int $quantity
     * @return array ['success' => bool, 'message' => string, 'subtotal' => float, 'shipping_fee' => float, 'total' => float]
     */
    protected function updateCartItem(int $productId, int $quantity): array
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.',
                'subtotal' => 0,
                'shipping_fee' => 0,
                'total' => 0,
            ];
        }

        $product = $this->findActiveCartProduct($productId);

        if (!$product) {
            // Xóa sản phẩm không tồn tại
            unset($cart[$productId]);
            $this->saveCart($cart);

            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã bị xóa.',
                'subtotal' => 0,
                'shipping_fee' => 0,
                'total' => 0,
            ];
        }

        // Kiểm tra số lượng còn lại
        if ($product->Amount !== null && $quantity > $product->Amount) {
            return [
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ. Còn lại: ' . $product->Amount . ' sản phẩm.',
                'subtotal' => 0,
                'shipping_fee' => 0,
                'total' => 0,
            ];
        }

        // Cập nhật số lượng
        $cart[$productId]['quantity'] = $quantity;
        $this->saveCart($cart);

        // Tính lại tổng tiền
        $totals = $this->calculateCartTotals($cart);

        return [
            'success' => true,
            'message' => 'Đã cập nhật số lượng.',
            'subtotal' => $totals['subtotal'],
            'shipping_fee' => $totals['shipping_fee'],
            'total' => $totals['total'],
        ];
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * 
     * @param int $productId
     * @return array ['success' => bool, 'message' => string, 'subtotal' => float, 'shipping_fee' => float, 'total' => float]
     */
    protected function removeFromCart(int $productId): array
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.',
                'subtotal' => 0,
                'shipping_fee' => 0,
                'total' => 0,
            ];
        }

        // Xóa sản phẩm khỏi giỏ hàng
        unset($cart[$productId]);
        $this->saveCart($cart);

        // Tính lại tổng tiền
        $totals = $this->calculateCartTotals($cart);

        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
            'subtotal' => $totals['subtotal'],
            'shipping_fee' => $totals['shipping_fee'],
            'total' => $totals['total'],
        ];
    }

    /**
     * Xóa toàn bộ giỏ hàng
     * 
     * @return void
     */
    protected function clearCart(): void
    {
        Session::forget($this->getCartSessionKey());
    }

    /**
     * Tính tổng số lượng sản phẩm trong giỏ hàng
     * 
     * @param array|null $cart
     * @return int
     */
    protected function getCartTotalQuantity(?array $cart = null): int
    {
        $cart = $cart ?? $this->getCart();
        $totalQuantity = 0;

        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }

        return $totalQuantity;
    }

    /**
     * Tính tổng tiền giỏ hàng
     * 
     * @param array|null $cart
     * @param float $shippingFee
     * @return array ['subtotal' => float, 'shipping_fee' => float, 'total' => float]
     */
    protected function calculateCartTotals(?array $cart = null, float $shippingFee = 0): array
    {
        $cart = $cart ?? $this->getCart();
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = $this->findActiveCartProduct((int) $productId);

            if ($product) {
                $subtotal += $product->Price * $item['quantity'];
            }
        }

        $total = $subtotal + $shippingFee;

        return [
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
        ];
    }

    /**
     * Lấy danh sách sản phẩm trong giỏ hàng với thông tin đầy đủ
     * 
     * @return array
     */
    protected function getCartItemsWithProducts(): array
    {
        $cartItems = $this->getCart();
        $products = [];
        $subtotal = 0;

        // Lấy thông tin sản phẩm từ database
        foreach ($cartItems as $productId => $item) {
            $product = $this->findActiveCartProduct((int) $productId);

            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'cart_id' => $productId, // Dùng ProductID làm cart_id
                ];
                $subtotal += $product->Price * $item['quantity'];
            } else {
                // Xóa sản phẩm không tồn tại khỏi giỏ hàng
                unset($cartItems[$productId]);
                $this->saveCart($cartItems);
            }
        }

        return $products;
    }

    protected function findActiveCartProduct(int $productId): ?ProductCart
    {
        return ProductCart::query()
            ->where('id', $productId)
            ->where('is_active', true)
            ->first();
    }
}
