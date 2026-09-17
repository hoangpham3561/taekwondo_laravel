<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Traits\CartManagement;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use CartManagement;

    /**
     * Lấy danh sách sản phẩm trong giỏ hàng
     */
    public function index()
    {
        $products = $this->getCartItemsWithProducts();
        $totals = $this->calculateCartTotals();

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $products,
                'totals' => $totals,
            ],
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:goi_hoc_phi,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $result = $this->addToCart(
            (int) $request->product_id,
            (int) $request->quantity
        );

        $statusCode = $result['success'] ? 200 : ($result['message'] === 'Sản phẩm không tồn tại hoặc đã bị xóa.' ? 404 : 400);

        return response()->json($result, $statusCode);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $result = $this->updateCartItem(
            (int) $id,
            (int) $request->quantity
        );

        $statusCode = $result['success'] ? 200 : 404;

        return response()->json($result, $statusCode);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove($id)
    {
        $result = $this->removeFromCart((int) $id);

        $statusCode = $result['success'] ? 200 : 404;

        return response()->json($result, $statusCode);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        $this->clearCart();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng.',
        ]);
    }
}