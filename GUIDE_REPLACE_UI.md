# Hướng Dẫn Thay Đổi Giao Diện HTML Sang Laravel Blade Template

## Mục đích
Tài liệu này hướng dẫn cách thay đổi giao diện HTML tĩnh sang Laravel Blade template một cách có hệ thống, đảm bảo giữ nguyên logic backend và data flow.

## Quy trình từng bước

### Bước 1: Chuẩn bị giao diện mới
- Đảm bảo có folder chứa giao diện HTML mới (ví dụ: `html/`)
- Kiểm tra các file HTML cần thay đổi
- Xác định các file CSS/JS/assets cần thiết

### Bước 2: Copy assets (CSS, JS, Images)
```bash
# Copy toàn bộ assets từ folder HTML sang public
cp -r html/client/* public/client/
```

**Lưu ý:**
- Đảm bảo các file CSS, JS, images đã được copy đầy đủ
- Kiểm tra đường dẫn trong HTML gốc để biết cấu trúc folder

### Bước 3: Phân tích cấu trúc HTML
Xác định các phần cần chuyển đổi:
- **Header**: Phần navigation, logo, menu
- **Footer**: Thông tin công ty, links, social media
- **Content**: Nội dung chính của trang
- **Layout**: Cấu trúc chung (head, body, scripts)

### Bước 4: Cập nhật Layout chính (`resources/views/layouts/frontend/frontend.blade.php`)

**Các thay đổi cần làm:**
1. Cập nhật `<title>` theo giao diện mới
2. Cập nhật favicon: `href="{{ asset('client/images/logo-moi.png') }}"`
3. Thêm/tuỳ chỉnh CSS theo thứ tự trong HTML gốc:
   ```blade
   <link rel="stylesheet" href="{{ asset('client/css/bootstrap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('client/css/animate.css') }}">
   <link rel="stylesheet" href="{{ asset('client/css/style.css') }}">
   <!-- ... các CSS khác -->
   ```
4. Thêm/tuỳ chỉnh JS theo thứ tự trong HTML gốc:
   ```blade
   <script src="{{ asset('client/js/jquery.min.js') }}"></script>
   <script src="{{ asset('client/js/bootstrap.bundle.min.js') }}"></script>
   <!-- ... các JS khác -->
   ```
5. Giữ nguyên cấu trúc `@include` và `@yield`:
   ```blade
   @include('layouts.frontend.partials.header')
   @include('layouts.frontend.partials.message')
   @yield('content')
   @include('layouts.frontend.partials.footer')
   ```

### Bước 5: Cập nhật Header (`resources/views/layouts/frontend/partials/header.blade.php`)

**Quy tắc chuyển đổi:**
1. **Giữ nguyên cấu trúc HTML** từ file HTML gốc
2. **Chuyển đường dẫn tĩnh** sang `asset()`:
   - `src="client/images/logo.png"` → `src="{{ asset('client/images/logo.png') }}"`
   - `href="index.html"` → `href="{{ route('.index') }}"`
3. **Chuyển links HTML** sang Laravel routes:
   - `href="shop.html"` → `href="{{ route('.product') }}"`
   - `href="login.html"` → `href="{{ route('.login') }}"`
   - `href="contact.html"` → `href="{{ route('.contact') }}"`
   - `href="cart.html"` → `href="{{ route('.cart') }}"`
4. **Giữ nguyên classes CSS** và cấu trúc HTML
5. **Giữ nguyên các attributes** như `data-bs-toggle`, `data-bs-dismiss`, etc.

**Ví dụ:**
```blade
<!-- HTML gốc -->
<a href="index.html"><img src="client/images/logo.png"></a>

<!-- Blade template -->
<a href="{{ route('.index') }}"><img src="{{ asset('client/images/logo.png') }}"></a>
```

### Bước 6: Cập nhật Footer (`resources/views/layouts/frontend/partials/footer.blade.php`)

**Quy tắc tương tự Header:**
1. Giữ nguyên cấu trúc HTML
2. Chuyển đường dẫn sang `asset()`
3. Chuyển links sang routes Laravel
4. Giữ nguyên nội dung tĩnh (nếu chưa cần render động)

### Bước 7: Cập nhật Content Page (ví dụ: `resources/views/pages/frontend/index.blade.php`)

**Quy tắc:**
1. **Giữ nguyên cấu trúc HTML** từ file HTML gốc
2. **Chuyển đường dẫn ảnh** sang `asset()`:
   ```blade
   <!-- HTML gốc -->
   <img src="client/images/sp3.png">
   
   <!-- Blade -->
   <img src="{{ asset('client/images/sp3.png') }}">
   ```
3. **Nếu chưa cần render data động**, giữ nguyên nội dung tĩnh
4. **Nếu cần render data động**, sử dụng Blade directives:
   ```blade
   @forelse($products as $product)
       <div>{{ $product->name }}</div>
   @empty
       <div>Không có sản phẩm</div>
   @endforelse
   ```

### Bước 8: Kiểm tra Routes

**Đảm bảo các routes đã được định nghĩa:**
- Kiểm tra file `routes/frontend/web.php` hoặc `routes/frontend/user.php`
- Xác định route names (ví dụ: `.index`, `.product`, `.login`)
- Sử dụng đúng route name trong Blade templates

**Ví dụ kiểm tra route:**
```php
// routes/frontend/web.php
Route::get('/', [HomeController::class, 'index'])->name($userPrefix . '.index');
Route::get('/product/{slug?}', [ProductController::class, 'index'])->name($userPrefix . '.product');
```

### Bước 9: Kiểm tra và Test

**Checklist:**
- [ ] Tất cả đường dẫn ảnh đã dùng `asset()`
- [ ] Tất cả links đã dùng `route()`
- [ ] CSS/JS đã được load đúng thứ tự
- [ ] Không có lỗi 404 cho assets
- [ ] Giao diện hiển thị đúng như HTML gốc
- [ ] Responsive hoạt động tốt
- [ ] JavaScript functions hoạt động đúng

### Bước 10: Xử lý lỗi thường gặp

**Lỗi Route not defined:**
- Kiểm tra route name trong `routes/frontend/*.php`
- Đảm bảo route name đúng format: `$userPrefix . '.route-name'`
- Sử dụng `route('.route-name')` trong Blade (dấu chấm đầu tiên là prefix)

**Lỗi CSS/JS không load:**
- Kiểm tra file đã được copy vào `public/client/`
- Clear browser cache (Ctrl+Shift+R)
- Clear Laravel cache: `php artisan cache:clear` và `php artisan view:clear`
- Kiểm tra đường dẫn trong `asset()` có đúng không

**Lỗi ảnh không hiển thị:**
- Kiểm tra file ảnh đã có trong `public/client/images/`
- Kiểm tra đường dẫn trong `asset()`
- Kiểm tra quyền truy cập file

## Quy tắc quan trọng

### ✅ ĐƯỢC PHÉP THAY ĐỔI:
- Cấu trúc HTML (div, section, etc.)
- CSS classes
- Thứ tự load CSS/JS
- Layout structure
- Styling và giao diện tổng thể

### ❌ KHÔNG ĐƯỢC THAY ĐỔI:
- Các biến Blade: `{{ $variable }}`, `@yield`, `@include`
- Route names và logic routing
- Form actions và methods
- Input names (backend xử lý dựa vào name)
- JavaScript functions quan trọng
- Data attributes cần thiết cho JS

## Template câu lệnh cho Cursor

### Cho trang thông thường:
```
Tôi có giao diện HTML mới trong folder [tên_folder]. 
Hãy thay đổi giao diện hiện tại theo các bước sau:

1. Copy tất cả assets từ [tên_folder]/client/* sang public/client/
2. Cập nhật layout frontend.blade.php với CSS/JS từ file HTML gốc
3. Cập nhật header.blade.php: chuyển đường dẫn tĩnh sang asset(), links sang routes Laravel
4. Cập nhật footer.blade.php tương tự header
5. Cập nhật [tên_file].blade.php với nội dung từ [tên_file].html
6. Giữ nguyên cấu trúc HTML, chỉ chuyển đổi đường dẫn và links
7. Chưa cần render data động, chỉ cần giao diện tĩnh trước

Làm từng bước một và báo cáo tiến độ.
```

### Cho trang Form (Đăng ký, Đăng nhập):
```
Tôi có giao diện HTML mới cho trang [tên_trang] trong folder html/[tên_file].html.
Hãy thay đổi giao diện cho [tên_file].blade.php theo các quy tắc:

1. Giữ nguyên form action, method, và @csrf
2. Giữ nguyên TẤT CẢ input names (backend xử lý dựa vào name)
3. Thêm validation errors với @error directive
4. Thêm old() values cho các input text/email
5. Thêm session messages (error/success)
6. Chuyển đổi HTML structure và CSS classes từ HTML gốc
7. Chuyển đường dẫn ảnh sang asset()
8. Nếu cần, cập nhật layout auth.blade.php với CSS/JS mới

QUAN TRỌNG: Không được thay đổi input names, form action, method.

Làm từng bước một và báo cáo tiến độ.
```

## Thay đổi giao diện Form Pages (Đăng ký, Đăng nhập, v.v.)

### Đặc điểm của Form Pages:
- Sử dụng layout riêng: `layouts.frontend.auth` (thay vì `layouts.frontend.frontend`)
- Có form với validation và error handling
- Cần giữ nguyên form action, method, và input names
- Cần thêm validation errors và old values

### Bước 1: Xác định Layout
Kiểm tra file Blade hiện tại đang dùng layout nào:
```blade
@extends('layouts.frontend.auth')  // Layout cho auth pages
// hoặc
@extends('layouts.frontend.frontend')  // Layout cho pages thông thường
```

### Bước 2: Cập nhật Layout Auth (nếu cần)
Nếu file HTML mới có CSS/JS khác, cập nhật `resources/views/layouts/frontend/auth.blade.php`:
- Cập nhật CSS/JS theo thứ tự trong HTML gốc
- Cập nhật title và favicon
- Giữ nguyên cấu trúc `@include` và `@yield`

### Bước 3: Chuyển đổi Form Content

**Quy tắc QUAN TRỌNG:**
1. **GIỮ NGUYÊN form action và method:**
   ```blade
   <!-- KHÔNG thay đổi -->
   <form action="{{ route($userPrefix . '.doSignUp') }}" method="POST">
       @csrf
   ```

2. **GIỮ NGUYÊN input names:**
   ```blade
   <!-- Backend xử lý dựa vào name, KHÔNG được đổi -->
   <input name="username" ...>  <!-- ✅ Đúng -->
   <input name="email" ...>     <!-- ✅ Đúng -->
   <input name="password" ...>  <!-- ✅ Đúng -->
   ```

3. **Thêm validation errors:**
   ```blade
   <input name="username" value="{{ old('username') }}" required>
   @error('username')
       <div class="text-danger mt-1">{{ $message }}</div>
   @enderror
   ```

4. **Thêm old() values để giữ dữ liệu khi có lỗi:**
   ```blade
   <input name="email" value="{{ old('email') }}" required>
   ```

5. **Thêm session messages:**
   ```blade
   @if(session('error'))
       <div class="alert alert-danger mb-4">{{ session('error') }}</div>
   @endif
   @if(session('success'))
       <div class="alert alert-success mb-4">{{ session('success') }}</div>
   @endif
   ```

### Bước 4: Chuyển đổi HTML Structure

**Ví dụ chuyển đổi Form đăng ký:**

```html
<!-- HTML gốc -->
<section class="block-page page-login">
    <div class="container custom-w">
        <form action="">
            <div class="col-md-12 mb-4"> 
                <lable class="custom-label d-block mb-1">Tên đăng nhập</lable>
                <input class="form-control custom-default-control" type="text">
            </div>
            <button class="btn-chect-out w-100">Đăng ký</button>
        </form>
    </div>
</section>
```

```blade
<!-- Blade template -->
<section class="block-page page-login">
    <div class="container custom-w">
        <form class="form-default" action="{{ route($userPrefix . '.doSignUp') }}" method="POST">
            @csrf
            <div class="col-md-12 mb-4"> 
                <label class="custom-label d-block mb-1">Tên đăng nhập</label>
                <input class="form-control custom-default-control" type="text" name="username" value="{{ old('username') }}" required>
                @error('username')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn-chect-out w-100" type="submit">Đăng ký</button>
        </form>
    </div>
</section>
```

### Bước 5: Xử lý các trường đặc biệt

**Trường readonly (như ref_id):**
```blade
@if(isset($refId) && $refId)
<div class="col-md-12 mb-4"> 
    <label class="custom-label d-block mb-1">Mã giới thiệu</label>
    <input class="form-control custom-default-control" type="text" name="ref_id" value="{{ $refId }}" readonly>
    <small class="form-text text-muted">Mã giới thiệu: {{ $refUserName ?? $refId }}</small>
    @error('ref_id')
    <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>
@endif
```

**Trường password (không dùng old()):**
```blade
<input class="form-control custom-default-control" type="password" name="password" required>
@error('password')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror
```

### Checklist cho Form Pages:
- [ ] Form action và method đúng
- [ ] Có `@csrf` token
- [ ] Input names giữ nguyên (không đổi)
- [ ] Có `old()` values cho các input text/email
- [ ] Có `@error` directives cho validation
- [ ] Có session messages (error/success)
- [ ] Button type="submit"
- [ ] HTML structure và CSS classes giữ nguyên từ HTML gốc
- [ ] Đường dẫn ảnh đã chuyển sang `asset()`

## Ví dụ cụ thể

### Chuyển đổi Header:
```html
<!-- HTML gốc -->
<header class="header-cf-office">
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="client/images/logo.png" alt="Logo">
            </a>
            <ul class="navbar-nav">
                <li><a href="shop.html">Sản Phẩm</a></li>
                <li><a href="login.html">Đăng Nhập</a></li>
            </ul>
        </div>
    </nav>
</header>
```

```blade
<!-- Blade template -->
<header class="header-cf-office">
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('.index') }}">
                <img src="{{ asset('client/images/logo.png') }}" alt="Logo">
            </a>
            <ul class="navbar-nav">
                <li><a href="{{ route('.product') }}">Sản Phẩm</a></li>
                <li><a href="{{ route('.login') }}">Đăng Nhập</a></li>
            </ul>
        </div>
    </nav>
</header>
```

## Lưu ý cuối cùng

- **Luôn giữ backup** trước khi thay đổi
- **Test từng bước** sau mỗi thay đổi
- **Kiểm tra console browser** để phát hiện lỗi CSS/JS
- **Đảm bảo responsive** hoạt động tốt trên mobile
- **Kiểm tra cross-browser** compatibility

---

**Tác giả:** Generated from project experience  
**Ngày tạo:** 2025  
**Phiên bản:** 1.0

