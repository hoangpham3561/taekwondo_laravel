<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ImageUpload
{
    /**
     * Upload ảnh lên API và lấy link
     * 
     * @param \Illuminate\Http\UploadedFile|array $file File cần upload
     * @return string Link ảnh hoặc rỗng nếu lỗi
     */
    public function getLinkImage($file)
    {
        $apiKey = 'c1122392d5ae460b7ab7531cbdfb10ea';
        $apiUrl = 'https://upload.ev-studio.co/uploadvip?key=' . $apiKey;

        // Xử lý file từ Laravel Request hoặc $_FILES
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
        } elseif (is_array($file) && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
            $filePath = $file['tmp_name'];
            $fileName = $file['name'] ?? 'image.jpg';
        } else {
            return '';
        }

        try {
            $response = Http::timeout(30)
                ->attach('image', file_get_contents($filePath), $fileName)
                ->post($apiUrl, ['filename' => $fileName]);
            if ($response->successful()) {
                $json = $response->json();
                return $json['success'] ? ($json['path'] ?? '') : '';
            }
        } catch (\Exception $e) {
            // Log error nếu cần
        }

        return '';
    }
}
