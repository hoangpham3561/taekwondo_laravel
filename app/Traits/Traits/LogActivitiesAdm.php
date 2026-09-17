<?php

namespace App\Traits;

use App\Models\Log_Activity_Adm as LogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

trait LogActivitiesAdm
{
    /**
     * Tạo log cho hoạt động của admin
     *
     * @param string $subject Tiêu đề hành động
     * @param Request $request Request object
     * @param string|array|null $task Chi tiết hành động (có thể là string hoặc array)
     * @return void
     */
    public static function createLog($subject, Request $request, $task = null)
    {
        try {
            $log = [];
            $log['subject'] = $subject;
            $log['url'] = $request->fullUrl();

            // Nếu task là array, chuyển thành JSON
            if (is_array($task)) {
                $log['task'] = json_encode($task, JSON_UNESCAPED_UNICODE);
            } else {
                $log['task'] = $task;
            }

            $log['method'] = $request->method();
            $log['ip'] = $request->ip();
            $log['agent'] = $request->header('user-agent');
            $log['user_id'] = Auth::guard('admin')->check() ? Auth::guard('admin')->user()->id : null;

            if (!empty($log)) {
                $LogModel = new LogModel();
                $LogModel->create($log);
            }
        } catch (\Exception $ex) {
            // Log lỗi nhưng không làm gián đoạn flow chính
            Log::error('Error creating admin activity log: ' . $ex->getMessage(), [
                'subject' => $subject ?? 'Unknown',
                'exception' => $ex
            ]);
        }
    }

    /**
     * Chuyển đổi data thành JSON
     *
     * @param mixed $data
     * @return string
     */
    public static function jsonCall($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
