<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Api\user\CartController as ApiCartController;
use App\Models\KhoaHoc;
use App\Models\ProductCart;
use App\Traits\ImageHelper;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    use ImageHelper;

    protected $apiCartController;

    public function __construct(ApiCartController $apiCartController)
    {
        parent::__construct();
        $this->apiCartController = $apiCartController;
    }

    public function index()
    {
        // Gọi API để lấy dữ liệu giỏ hàng
        $cartResponse = $this->apiCartController->index();
        $cartData = json_decode($cartResponse->getContent(), true);

        // Xử lý data từ API response
        $cartItems = $cartData['data']['items'] ?? [];
        $totals = $cartData['data']['totals'] ?? [
            'subtotal' => 0,
            'shipping_fee' => 0,
            'total' => 0,
        ];

        // Chuyển đổi product từ array thành object và xử lý ảnh
        $processedItems = [];
        $defaultImage = asset('client/images/no-image.jpg');

        foreach ($cartItems as $item) {
            // Chuyển product array thành object
            if (isset($item['product']) && is_array($item['product'])) {
                $product = (object) $item['product'];

                // Backward-compatible fields for legacy views
                if (!isset($product->ProductName) && isset($product->name)) {
                    $product->ProductName = $product->name;
                }
                if (!isset($product->Price) && isset($product->price)) {
                    $product->Price = (float) $product->price;
                }
                if (!isset($product->ProductID) && isset($product->id)) {
                    $product->ProductID = (int) $product->id;
                }

                // Xử lý ảnh sản phẩm - lấy từ ProductImage trực tiếp
                $productImageUrl = null;

                // Ưu tiên lấy từ product_image_url nếu có (từ ProductResource)
                if (isset($product->product_image_url) && !empty($product->product_image_url)) {
                    $productImageUrl = $product->product_image_url;
                }
                // Nếu không có, thử lấy từ image_url
                elseif (isset($product->image_url) && !empty($product->image_url)) {
                    $productImageUrl = $product->image_url;
                }
                // Nếu không có, lấy từ ProductImage (có thể là JSON array hoặc string)
                elseif (isset($product->ProductImage) && !empty($product->ProductImage)) {
                    $decoded = json_decode($product->ProductImage, true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $productImageUrl = $decoded[0]; // Lấy ảnh đầu tiên
                    } elseif (is_string($product->ProductImage)) {
                        $productImageUrl = $product->ProductImage;
                    }
                }
                // Nếu không có, thử lấy từ images array (từ ProductResource)
                elseif (isset($product->images) && is_array($product->images) && !empty($product->images)) {
                    $productImageUrl = $product->images[0];
                }

                // Xử lý URL - nếu là URL đầy đủ thì dùng trực tiếp, nếu không thì dùng asset()
                if ($productImageUrl) {
                    if (
                        filter_var($productImageUrl, FILTER_VALIDATE_URL) ||
                        strpos($productImageUrl, 'http://') === 0 ||
                        strpos($productImageUrl, 'https://') === 0
                    ) {
                        $product->product_image_url = $productImageUrl;
                    } else {
                        $product->product_image_url = asset($productImageUrl);
                    }
                } else {
                    // Nếu không có ảnh, dùng ảnh mặc định
                    $product->product_image_url = $defaultImage;
                }

                $item['product'] = $product;
            }
            $processedItems[] = $item;
        }

        return view('pages.frontend.cart', [
            'cartItems' => $processedItems,
            'subtotal' => $totals['subtotal'],
            'shippingFee' => $totals['shipping_fee'],
            'total' => $totals['total'],
            'userPrefix' => $this->userPrefix,
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng - gọi API
     */
    public function add(Request $request)
    {
        $apiResponse = $this->apiCartController->add($request);
        $result = json_decode($apiResponse->getContent(), true);

        $statusCode = $result['success'] ? 200 : ($result['message'] === 'Sản phẩm không tồn tại hoặc đã bị xóa.' ? 404 : 400);

        return response()->json($result, $statusCode);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng - gọi API
     */
    public function update(Request $request, $id)
    {
        $apiResponse = $this->apiCartController->update($request, $id);
        $result = json_decode($apiResponse->getContent(), true);

        $statusCode = $result['success'] ? 200 : 404;

        return response()->json($result, $statusCode);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng - gọi API
     */
    public function remove($id)
    {
        $apiResponse = $this->apiCartController->remove($id);
        $result = json_decode($apiResponse->getContent(), true);

        $statusCode = $result['success'] ? 200 : 404;

        return response()->json($result, $statusCode);
    }

    /**
     * Xóa toàn bộ giỏ hàng - gọi API
     */
    public function clear()
    {
        $apiResponse = $this->apiCartController->clear();
        $result = json_decode($apiResponse->getContent(), true);

        return response()->json($result);
    }

    public function addCourse(int $courseId)
    {
        $course = KhoaHoc::query()
            ->where('is_active', true)
            ->find($courseId);

        if (!$course) {
            return redirect()->back()->with('error', 'Khóa học không tồn tại hoặc đã ngừng hoạt động.');
        }

        $product = ProductCart::query()
            ->where('is_active', true)
            ->when($course->club_id, function ($query) use ($course) {
                $query->where('club_id', $course->club_id);
            })
            ->orderBy('price')
            ->first();

        if (!$product) {
            $product = ProductCart::query()
                ->where('is_active', true)
                ->orderBy('price')
                ->first();
        }

        if (!$product) {
            return redirect()->back()->with('error', 'Hiện chưa có gói học phí khả dụng để đăng ký khóa học này.');
        }

        $request = new Request([
            'product_id' => (int) $product->id,
            'quantity' => 1,
        ]);

        $apiResponse = $this->apiCartController->add($request);
        $result = json_decode($apiResponse->getContent(), true);

        if (!($result['success'] ?? false)) {
            return redirect()->back()->with('error', $result['message'] ?? 'Không thể thêm khóa học vào giỏ hàng.');
        }

        return redirect()->route($this->userPrefix . '.cart')->with('success', 'Đã thêm khóa học vào giỏ hàng.');
    }
}
