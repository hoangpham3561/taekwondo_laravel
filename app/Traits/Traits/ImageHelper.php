<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ImageHelper
{
    /**
     * Kiểm tra ảnh có tồn tại không, nếu không thì trả về ảnh mặc định
     * 
     * @param string|null $imagePath Đường dẫn ảnh (có thể là null hoặc rỗng)
     * @param string|null $defaultImage Đường dẫn ảnh mặc định (nếu null sẽ dùng ảnh no-image mặc định)
     * @return string Đường dẫn ảnh hợp lệ
     */
    public function getImageUrl($imagePath = null, $defaultImage = null)
    {
        // Nếu không có đường dẫn ảnh, trả về ảnh mặc định
        if (empty($imagePath)) {
            return $this->getDefaultImageUrl($defaultImage);
        }

        // Loại bỏ dấu / ở đầu nếu có
        $imagePath = ltrim($imagePath, '/');

        // Kiểm tra file có tồn tại trong public directory không
        $fullPath = public_path($imagePath);

        if (File::exists($fullPath)) {
            // Trả về đường dẫn ảnh với asset() helper
            return asset($imagePath);
        }

        // Nếu file không tồn tại, trả về ảnh mặc định
        return $this->getDefaultImageUrl($defaultImage);
    }

    /**
     * Lấy đường dẫn ảnh mặc định
     * 
     * @param string|null $customDefaultImage Đường dẫn ảnh mặc định tùy chỉnh
     * @return string Đường dẫn ảnh mặc định
     */
    protected function getDefaultImageUrl($customDefaultImage = null)
    {
        // Nếu có ảnh mặc định tùy chỉnh, sử dụng nó
        if (!empty($customDefaultImage)) {
            $customPath = ltrim($customDefaultImage, '/');
            return asset($customPath);
        }

        // Trả về ảnh no-image mặc định (chắc chắn có)
        return asset('client/images/no-image.jpg');
    }

    /**
     * Kiểm tra ảnh có tồn tại không (chỉ kiểm tra, không trả về URL)
     * 
     * @param string|null $imagePath Đường dẫn ảnh
     * @return bool True nếu ảnh tồn tại, false nếu không
     */
    public function imageExists($imagePath = null)
    {
        if (empty($imagePath)) {
            return false;
        }

        $imagePath = ltrim($imagePath, '/');
        $fullPath = public_path($imagePath);

        return File::exists($fullPath);
    }
}
