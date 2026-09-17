<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait ImageUpload
{
    /**
     * Upload ảnh lên API hoặc local storage và lấy link
     * 
     * @param \Illuminate\Http\UploadedFile|array $file File cần upload
     * @param string $folder Thư mục lưu trữ local (mặc định: 'images/users')
     * @return string Link ảnh hoặc rỗng nếu lỗi
     */
    public function getLinkImage($file, $folder = 'images/users')
    {
        // Xử lý file từ Laravel Request hoặc $_FILES
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
            $uploadedFile = $file;
        } elseif (is_array($file) && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
            $filePath = $file['tmp_name'];
            $fileName = $file['name'] ?? 'image.jpg';
            $uploadedFile = null;
        } else {
            return '';
        }

        // Validate file size (2MB)
        if ($uploadedFile && $uploadedFile->getSize() > 2097152) {
            Log::error('ImageUpload: File size exceeds 2MB', [
                'file_name' => $fileName,
                'file_size' => $uploadedFile->getSize(),
            ]);
            return '';
        }

        // Validate file type
        if ($uploadedFile) {
            $validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!in_array($uploadedFile->getMimeType(), $validTypes)) {
                Log::error('ImageUpload: Invalid file type', [
                    'file_name' => $fileName,
                    'mime_type' => $uploadedFile->getMimeType(),
                ]);
                return '';
            }
        }

        // Try upload to API first
        $apiResult = $this->uploadToApi($filePath, $fileName);
        if (!empty($apiResult)) {
            return $apiResult;
        }

        // Fallback to local storage
        return $this->uploadToLocal($uploadedFile ?: $file, $folder);
    }

    /**
     * Upload ảnh lên API external
     * 
     * @param string $filePath Đường dẫn file
     * @param string $fileName Tên file
     * @return string Link ảnh hoặc rỗng nếu lỗi
     */
    protected function uploadToApi($filePath, $fileName)
    {
        $apiKey = 'c1122392d5ae460b7ab7531cbdfb10ea';
        $apiUrl = 'https://upload.ev-studio.co/uploadvip?key=' . $apiKey;

        try {
            $response = Http::timeout(30)
                ->withoutVerifying() // Bỏ qua SSL verification
                ->attach('image', file_get_contents($filePath), $fileName)
                ->post($apiUrl, ['filename' => $fileName]);
            
            if ($response->successful()) {
                $json = $response->json();
                if (isset($json['success']) && $json['success']) {
                    return $json['path'] ?? '';
                }
            }
            
            // Log API response if failed
            Log::warning('ImageUpload API failed', [
                'file_name' => $fileName,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('ImageUpload getLinkImage API error: ' . $e->getMessage(), [
                'file_name' => $fileName,
                'api_url' => $apiUrl,
            ]);
        }

        return '';
    }

    /**
     * Upload ảnh lên local storage
     * 
     * @param \Illuminate\Http\UploadedFile|array $file File cần upload
     * @param string $folder Thư mục lưu trữ
     * @return string Link ảnh hoặc rỗng nếu lỗi
     */
    protected function uploadToLocal($file, $folder = 'images/users')
    {
        try {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                // Generate unique filename
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Store file in public storage
                $path = $file->storeAs($folder, $fileName, 'public');
                
                if ($path) {
                    // Return public URL
                    return Storage::url($path);
                }
            } elseif (is_array($file) && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                // Handle $_FILES array
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $destination = storage_path('app/public/' . $folder . '/' . $fileName);
                
                // Create directory if not exists
                $dir = dirname($destination);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    return Storage::url($folder . '/' . $fileName);
                }
            }
        } catch (\Exception $e) {
            Log::error('ImageUpload local upload error: ' . $e->getMessage(), [
                'file_name' => $file['name'] ?? 'unknown',
                'folder' => $folder,
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return '';
    }
}