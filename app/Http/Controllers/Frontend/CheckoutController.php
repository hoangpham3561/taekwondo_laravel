<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Checkout\ProcessCheckoutRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductQuantityDiscount;
use App\Models\ProductCart;
use App\Models\TransGdRequirement;
use App\Traits\CartManagement;
use App\Traits\Telegram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends BaseController
{
    use CartManagement, Telegram;
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        if (session('order_type') == 'landing') {
            $title = 'THANH TOÁN MUA HÀNG VÃNG LAI';
        } elseif (session('order_type') == 'sys') {
            $title = 'THANH TOÁN ĐẶT HÀNG BÁN ĐẠI LÝ';
        } elseif (session('order_type') == 'wholesale') {
            $title = 'THANH TOÁN ĐẶT HÀNG BÁN SỈ';
        } else {
            $title = 'THANH TOÁN MUA HÀNG VÃNG LAI';
        }

        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect()->route($this->userPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $products = $this->getCartItemsWithProducts();
        if (empty($products)) {
            return redirect()->route($this->userPrefix . '.cart')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }


        // Kiểm tra ProductType trước khi hiển thị trang checkout
        $validation = $this->validateCartProductTypes();

        if (!$validation['valid']) {
            return redirect()->route($this->userPrefix . '.cart')
                ->with('error', $validation['message']);
        }

        $totals = $this->calculateCartTotals();

        return view('pages.frontend.checkout', [
            'cartItems' => $products,
            'subtotal' => $totals['subtotal'],
            'shippingFee' => $totals['shipping_fee'],
            'total' => $totals['total'],
            'user' => $user,
            'userPrefix' => $this->userPrefix,
            'title' => $title,
        ]);
    }

    /**
     * Xử lý thanh toán đơn hàng
     */
    public function submitCheckout(ProcessCheckoutRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::guard('web')->user();
            if (!$user) {
                return redirect()->route($this->userPrefix . '.login')
                    ->with('error', 'Vui lòng đăng nhập để thanh toán.');
            }

            $cartItems = $this->getCartItemsWithProducts();
            if (empty($cartItems)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            $validation = $this->validateCartProductTypes();
            if (!$validation['valid']) {
                DB::rollBack();
                return redirect()->route($this->userPrefix . '.cart')
                    ->with('error', $validation['message']);
            }

            if (session('order_type') == 'wholesale') {
                $userNode = \App\Models\TblNode::where('UserID', $user->UserID)->first();
                if (!$userNode || $userNode->MucID < 1) {
                    DB::rollBack();
                    return redirect()->route($this->userPrefix . '.cart')
                        ->with('error', 'Bạn không có quyền mua sản phẩm bán sỉ. Vui lòng liên hệ admin để được nâng cấp.');
                }
            }

            $totals = $this->calculateCartTotals();
            $totalAmount = $totals['total'];

            // Tính tổng Point từ các sản phẩm trong giỏ hàng
            $totalPoint = 0;
            foreach ($cartItems as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                // Tính Point = Point của sản phẩm * số lượng
                $totalPoint += $product->Point * $quantity;
            }

            foreach ($cartItems as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];

                if ($product->Amount !== null && $quantity > $product->Amount) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Sản phẩm '{$product->ProductName}' không đủ số lượng. Còn lại: {$product->Amount} sản phẩm.");
                }

                if ($product->Active !== 'Y') {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Sản phẩm '{$product->ProductName}' đã bị vô hiệu hóa.");
                }
            }

            $orderCode = 'ORD' . strtoupper(Str::random(8)) . time();
            while (Order::where('OrderCode', $orderCode)->exists()) {
                $orderCode = 'ORD' . strtoupper(Str::random(8)) . time();
            }

            if (session('order_type') == 'landing') { // Mua hàng vãng lai
                $txId = 2;
                $telegramMessage = "<b>✅ USER MUA HÀNG VÃNG LAI !</b>\n";
            } elseif (session('order_type') == 'sys') { // Bán đại lý
                $txId = 1;
                $telegramMessage = "<b>✅ USER ĐẶT HÀNG BÁN ĐẠI LÝ !</b>\n";
            } elseif (session('order_type') == 'wholesale') { // Bán sỉ
                $txId = 0;
                $telegramMessage = "<b>✅ USER ĐẶT HÀNG BÁN SỈ !</b>\n";
            } else {
                $txId = 2; // Default = vãng lai
                $telegramMessage = "<b>✅ USER MUA HÀNG VÃNG LAI !</b>\n";
            }

            $order = Order::create([
                'TxID' => $txId,
                'OrderCode' => $orderCode,
                'OrderType' => 'Order',
                'OrderDate' => now(),
                'DatePay' => now(),
                'OrderStatus' => 1,
                'Parent' => 0,
                'UserID' => $user->UserID,
                'MasterID' => 0,
                'ProductID' => 0,
                'ProductName' => '',
                'Website' => 0,
                'Country' => 'VN',
                'FullName' => $request->input('full_name'),
                'Phone' => $request->input('phone'),
                'Address' => $request->input('address'),
                'Email' => $user->Email ?? null,
                'Total' => $totalAmount,
                'TotalBonus' => 0,
                'PaymentMethod' => $request->input('payment_method'),
                'ShippingMethod' => $request->input('shipping_method'),
                'ShippingFee' => $request->input('shipping_fee'),
                'Currency' => 'VND',
                'secure_code' => Str::random(32),
                'SaleTotal' => 0,
                'TotalPoint' => $totalPoint,
                'TotalAmount' => $totalAmount,
                'Status' => 'N',
                'Note' => $request->input('note'),
                'IPAddress' => $request->ip(),
                'Image' => null,
            ]);
            $telegramMessagedetail = "";
            $totalSale = 0;
            foreach ($cartItems as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];

                //tính giảm giá theo số lượng
                $discount = $product->getDiscountByQuantity($quantity);
                $sale = $quantity * ($product->Point * $discount / 100);
                $totalSale += $sale;
                OrderDetail::create([
                    'OrderID' => $order->OrderID,
                    'userIdCreate' => $user->UserID,
                    'ProductID' => $product->ProductID,
                    'ProductName' => $product->ProductName,
                    'Amount' => $quantity,
                    'Price' => $product->Price,
                    'Sale' => $sale,
                    'Point' => $product->Point,
                    'ID_Addin' => 0,
                    'StatusOfMember' => 0,
                ]);

                $telegramMessagedetail .= " - {$product->ProductName} x{$quantity} - DiscountPercent: {$discount}% - Sale: {$sale}\n";
            }
            $order->update([
                'TotalBonus' => $totalSale,
            ]);
            $this->clearCart();

            $telegramMessage .= "Mã đơn: {$orderCode}\n";
            $telegramMessage .= "User: {$user->UserName} (ID: {$user->UserID})\n";
            $telegramMessage .= "Tổng tiền: " . number_format($totalAmount, 0, ',', '.') . " VNĐ\n";
            $telegramMessage .= "Họ và tên: {$request->input('full_name')}\n";
            $telegramMessage .= "Địa chỉ: {$request->input('address')}\n";
            $telegramMessage .= "SĐT: {$request->input('phone')}\n";
            $telegramMessage .= "Ghi chú: {$request->input('note')}\n";
            $telegramMessage .= "Phương thức thanh toán: " . ($request->input('payment_method') == '0' ? 'Thanh toán COD' : 'Thanh toán online') . " \n";
            $telegramMessage .= "Phương thức vận chuyển: " . ($request->input('shipping_method') == '0' ? 'Vận chuyển nhanh' : 'Vận chuyển tiết kiệm') . "\n";
            $telegramMessage .= "Phí vận chuyển: {$request->input('shipping_fee')} VNĐ\n";
            $telegramMessage .= "Thời gian: " . now()->format('d/m/Y H:i:s') . "\n\n";

            $telegramMessage .= "<b>✅ THÔNG TIN SẢN PHẨM</b>\n";
            $telegramMessage .= $telegramMessagedetail;

            $this->sendMesssageTelegram($telegramMessage, 'muahang');

            DB::commit();

            return redirect()->route($this->userPrefix . '.order')
                ->with('success', 'Thanh toán thành công! Mã đơn hàng: ' . $orderCode);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::guard('web')->id(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thanh toán. Vui lòng thử lại sau.');
        }
    }

    /**
     * Kiểm tra ProductType phù hợp với order_type
     */
    private function validateCartProductTypes()
    {
        $orderType = session('order_type');
        $cartItems = $this->getCartItemsWithProducts();

        foreach ($cartItems as $item) {
            $product = $item['product'];
            $productTypes = [];

            if (!empty($product->ProductType)) {
                $decoded = json_decode($product->ProductType, true);
                $productTypes = is_array($decoded) ? $decoded : [$product->ProductType];
            }

            // Kiểm tra loại sản phẩm phù hợp
            $isValidProduct = false;
            if ($orderType == 'landing' && in_array('2', $productTypes)) {
                $isValidProduct = true; // Sản phẩm vãng lai
            } elseif ($orderType == 'sys' && in_array('1', $productTypes)) {
                $isValidProduct = true; // Sản phẩm đại lý
            } elseif ($orderType == 'wholesale' && in_array('0', $productTypes)) {
                $isValidProduct = true; // Sản phẩm bán sỉ
            }

            if (!$isValidProduct) {
                $orderTypeName = [
                    'landing' => 'mua hàng vãng lai',
                    'sys' => 'bán đại lý',
                    'wholesale' => 'bán sỉ'
                ][$orderType] ?? 'mua hàng vãng lai';

                return [
                    'valid' => false,
                    'message' => "Sản phẩm '{$product->ProductName}' không phù hợp với loại đơn hàng {$orderTypeName}."
                ];
            }
        }

        return ['valid' => true];
    }
}
