<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\BaseHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends BaseController
{
    public function index(Request $request): View
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('pages.backend.notifications.index', [
            'notifications' => $notifications,
            'adminPrefix' => BaseHelper::getAdminPrefix(),
            'unreadCount' => $admin->unreadNotifications()->count(),
        ]);
    }

    public function open(string $id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->where('id', $id)->firstOrFail();

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        $data = $notification->data;
        $target = is_array($data) ? ($data['url'] ?? null) : null;

        if (! empty($target)) {
            return redirect()->to($target);
        }

        return redirect()->route($this->adminPrefix . '.notifications.index');
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->where('id', $id)->firstOrFail();

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Đã đánh dấu đã đọc.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Đã đánh dấu đọc tất cả thông báo.');
    }
}
