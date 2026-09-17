# Hướng Dẫn Chuyển Đổi Sang API

## Tổng quan

Dự án đã được cấu hình để hỗ trợ API RESTful sử dụng Laravel Token Guard. Token được lưu trực tiếp trong cột `api_token` của bảng `user` (không cần bảng riêng). Bạn có thể sử dụng API để:
- Xây dựng mobile app (iOS, Android)
- Xây dựng SPA (Single Page Application) với React, Vue, Angular
- Tích hợp với các hệ thống khác
- Mở rộng dễ dàng hơn

## Cấu trúc đã tạo

### 1. Authentication (Laravel Token Guard)
- ✅ Đã cấu hình guard `api` với driver `token` trong `config/auth.php`
- ✅ Đã thêm cột `api_token` vào bảng `user` (migration đã chạy)
- ✅ Token được lưu trực tiếp trong bảng user, không cần bảng riêng

### 2. API Controllers
- `app/Http/Controllers/Api/AuthController.php` - Xử lý đăng ký, đăng nhập, đăng xuất
- `app/Http/Controllers/Api/ProductController.php` - Quản lý sản phẩm
- `app/Http/Controllers/Api/CartController.php` - Quản lý giỏ hàng

### 3. API Resources
- `app/Http/Resources/ProductResource.php` - Transform dữ liệu sản phẩm
- `app/Http/Resources/ProductCategoryResource.php` - Transform dữ liệu category

### 4. API Requests (Validation)
- `app/Http/Requests/Api/Auth/LoginRequest.php`
- `app/Http/Requests/Api/Auth/RegisterRequest.php`

### 5. API Routes
- `routes/api.php` - Tất cả API routes với prefix `/api/v1`

## Cách sử dụng API

### 1. Đăng ký user mới
```bash
POST /api/v1/register
Content-Type: application/json

{
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### 2. Đăng nhập
```bash
POST /api/v1/login
Content-Type: application/json

{
    "email": "testuser",  // username hoặc email
    "password": "password123"
}

Response:
{
    "success": true,
    "data": {
        "user": {...},
        "token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
    }
}
```

### 3. Sử dụng token
```bash
GET /api/v1/products
Authorization: Bearer {token}
Accept: application/json
```

## Frontend Integration

### JavaScript/Fetch
```javascript
// Đăng nhập
const response = await fetch('http://your-domain.com/api/v1/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'testuser',
        password: 'password123'
    })
});

const data = await response.json();
const token = data.data.token;

// Lưu token
localStorage.setItem('token', token);

// Sử dụng token cho các request tiếp theo
const productsResponse = await fetch('http://your-domain.com/api/v1/products', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});
```

### Axios
```javascript
import axios from 'axios';

// Tạo axios instance
const api = axios.create({
    baseURL: 'http://your-domain.com/api/v1',
    headers: {
        'Accept': 'application/json'
    }
});

// Thêm token vào mọi request
api.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Đăng nhập
const login = async (email, password) => {
    const response = await api.post('/login', { email, password });
    localStorage.setItem('token', response.data.data.token);
    return response.data;
};

// Lấy sản phẩm
const getProducts = async () => {
    const response = await api.get('/products');
    return response.data;
};
```

## Mở rộng API

### Thêm endpoint mới

1. **Tạo Controller:**
```php
// app/Http/Controllers/Api/OrderController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Logic lấy danh sách đơn hàng
    }
}
```

2. **Tạo Resource (nếu cần):**
```php
// app/Http/Resources/OrderResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->OrderID,
            'code' => $this->OrderCode,
            // ...
        ];
    }
}
```

3. **Thêm route:**
```php
// routes/api.php
Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
});
```

## Best Practices

### 1. Response Format
Luôn trả về format nhất quán:
```json
{
    "success": true/false,
    "message": "Message",
    "data": {...}
}
```

### 2. Error Handling
```php
try {
    // Logic
    return response()->json([
        'success' => true,
        'data' => $data
    ]);
} catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}
```

### 3. Validation
Sử dụng Form Requests cho validation:
```php
// app/Http/Requests/Api/Order/CreateOrderRequest.php
public function rules(): array
{
    return [
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|min:1',
    ];
}
```

### 4. Resources
Luôn sử dụng Resources để transform data:
```php
return new ProductResource($product);
// hoặc
return ProductResource::collection($products);
```

## Testing API

### Sử dụng Postman
1. Import collection từ `API_DOCUMENTATION.md`
2. Set environment variables
3. Test từng endpoint

### Sử dụng cURL
```bash
# Đăng nhập
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"testuser","password":"password123"}'

# Lấy sản phẩm
curl -X GET http://localhost:8000/api/v1/products \
  -H "Accept: application/json"
```

### Sử dụng PHPUnit
```php
// tests/Feature/Api/AuthTest.php
public function test_user_can_login()
{
    $response = $this->postJson('/api/v1/login', [
        'email' => 'testuser',
        'password' => 'password123'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => ['user', 'token']
        ]);
}
```

## Security

### 1. Rate Limiting
Đã được cấu hình trong `bootstrap/app.php`:
```php
$middleware->api(prepend: [
    \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
]);
```

### 2. CORS
Đã cấu hình trong `config/cors.php`. Có thể điều chỉnh `allowed_origins` cho production.

### 3. Token Expiration
Token hiện tại không có thời hạn mặc định. Nếu cần thêm expiration, có thể:
- Thêm cột `api_token_expires_at` vào bảng `user`
- Tạo middleware để kiểm tra token expiration
- Hoặc tự động refresh token khi gần hết hạn

## Migration từ Web Routes sang API

### Bước 1: Xác định endpoints cần chuyển
- Authentication: ✅ Đã có
- Products: ✅ Đã có
- Cart: ✅ Đã có
- Orders: ⏳ Cần thêm
- User Profile: ⏳ Cần thêm
- ...

### Bước 2: Tạo API Controller
Copy logic từ Frontend Controller, nhưng:
- Trả về JSON thay vì view
- Sử dụng Resources để transform data
- Xử lý errors trả về JSON

### Bước 3: Test API
- Test với Postman/cURL
- Test với frontend mới
- Đảm bảo backward compatibility

### Bước 4: Update Frontend
- Thay đổi từ form submit sang API calls
- Sử dụng token authentication
- Xử lý responses và errors

## Checklist

- [x] Cấu hình Laravel Token Guard
- [x] Thêm cột `api_token` vào bảng `user`
- [x] Cập nhật User model
- [x] Tạo API Controllers (Auth, Product, Cart)
- [x] Tạo API Resources
- [x] Tạo API Routes
- [x] Cấu hình CORS
- [x] Tạo API Documentation
- [ ] Test tất cả endpoints
- [ ] Thêm các endpoints còn thiếu (Orders, Profile, etc.)
- [ ] Tạo Postman collection
- [ ] Setup API versioning strategy
- [ ] Cấu hình rate limiting chi tiết hơn

## Tài liệu tham khảo

- [Laravel Authentication - Token Guard](https://laravel.com/docs/authentication#token-authentication)
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [RESTful API Best Practices](https://restfulapi.net/)

---

**Lưu ý:** API hiện tại đang ở version 1 (`/api/v1/`). Khi cần thay đổi breaking changes, tạo version 2 (`/api/v2/`) để giữ backward compatibility.

