<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\BaseHelper;

class CheckRole
{
    /**
     * Kiểm tra xem admin có thuộc nhóm được phép không
     * 
     * VÍ DỤ SỬ DỤNG:
     * Route::middleware(['role:super_admin'])->group(...) 
     *   → Chỉ super_admin mới vào được
     * 
     * Route::middleware(['role:super_admin|admin_ketoan'])->group(...) 
     *   → Cả super_admin VÀ admin_ketoan đều vào được
     */
    public function handle(Request $request, Closure $next, string $allowedRoles): Response
    {
        // Lấy thông tin admin đang đăng nhập
        $admin = auth()->guard('admin')->user();

        // Nếu chưa đăng nhập → đá về trang login
        if (!$admin) {
            $baseHelper = new BaseHelper();
            $adminPrefix = $baseHelper->getAdminPrefix();
            return redirect()->route($adminPrefix . '.login')
                ->with('error', 'Vui lòng đăng nhập.');
        }

        // Nếu là super_admin → cho phép vào tất cả
        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        // Lấy role của admin sử dụng getRoleName() method (VD: 'admin_ketoan')
        $adminRole = $admin->getRoleName();

        // Tách chuỗi 'super_admin|admin_ketoan' thành mảng ['super_admin', 'admin_ketoan']
        $allowedRolesArray = explode('|', $allowedRoles);

        // Kiểm tra role của admin có nằm trong danh sách cho phép không
        if (!in_array($adminRole, $allowedRolesArray)) {
            // Không có quyền → quay về dashboard với thông báo lỗi
            $baseHelper = new BaseHelper();
            $adminPrefix = $baseHelper->getAdminPrefix();
            return redirect()->route($adminPrefix . '.orders.index')
                ->with('error', 'Bạn không có quyền truy cập chức năng này.');
        }

        // Có quyền → cho phép tiếp tục
        return $next($request);
    }
}