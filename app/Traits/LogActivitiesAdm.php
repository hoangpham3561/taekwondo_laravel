<?php

namespace App\Traits;

use App\Models\Log_Activity_Adm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogActivitiesAdm
{
    /**
     * Backward-compatible logger used by old controllers.
     *
     * @param string $subject
     * @param \Illuminate\Http\Request $request
     * @param string|array|null $task
     * @return void
     */
    public static function createLog($subject, \Illuminate\Http\Request $request, $task = null): void
    {
        try {
            $admin = Auth::guard('admin')->user();

            Log_Activity_Adm::create([
                'user_id' => $admin->id ?? $admin->UserID ?? null,
                'subject' => $subject,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'task' => is_array($task) ? json_encode($task) : $task,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log admin activity: ' . $e->getMessage());
        }
    }

    /**
     * Log admin activity
     *
     * @param string $subject
     * @param string $task
     * @param array|null $data
     * @return void
     */
    public function logActivityAdm(string $subject, string $task, ?array $data = null): void
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return;
            }

            Log_Activity_Adm::create([
                'user_id' => $admin->id ?? $admin->UserID ?? null,
                'subject' => $subject,
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'ip' => Request::ip(),
                'agent' => Request::userAgent(),
                'task' => is_array($data) ? json_encode($data) : $task,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Failed to log admin activity: ' . $e->getMessage());
        }
    }

    /**
     * Log activity with automatic subject detection
     *
     * @param string $action
     * @param mixed $model
     * @param array|null $data
     * @return void
     */
    public function logActivity(string $action, $model = null, ?array $data = null): void
    {
        $subject = $action;
        
        if ($model) {
            $modelName = class_basename($model);
            $subject = $action . ' ' . $modelName;
            
            if (isset($model->id)) {
                $subject .= ' (ID: ' . $model->id . ')';
            } elseif (isset($model->UserID)) {
                $subject .= ' (ID: ' . $model->UserID . ')';
            }
        }

        $task = $action;
        if ($data) {
            $task = json_encode($data);
        }

        $this->logActivityAdm($subject, $task, $data);
    }
}