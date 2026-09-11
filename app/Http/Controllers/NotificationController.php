<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * عرض قائمة الإشعارات الخاصة بالمستخدم مع التبويبات.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $tab = $request->query('tab', 'all');

        $query = $user->notifications()->latest();

        // حساب العدادات لكل تبويب
        $counts = [
            'all' => $user->notifications()->count(),
            'unread' => $user->notifications()->where('is_read', false)->count(),
            'requests' => $user->notifications()->where(function ($q) {
                $q->where('type', 'like', '%request%')
                    ->orWhere('type', 'like', '%provider%')
                    ->orWhere('type', 'like', '%problem%');
            })->count(),
        ];

        // تطبيق فلتر التبويب
        if ($tab === 'unread') {
            $query->where('is_read', false);
        } elseif ($tab === 'requests') {
            $query->where(function ($q) {
                $q->where('type', 'like', '%request%')
                    ->orWhere('type', 'like', '%provider%')
                    ->orWhere('type', 'like', '%problem%');
            });
        }

        $notifications = $query->paginate(12)->withQueryString();

        return view('notifications.index', compact('notifications', 'counts', 'tab'));
    }

    /**
     * تحديث حالة الإشعار إلى مقروء.
     */
    public function markAsRead(Request $request, Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403, 'غير مصرح لك بإجراء هذه العملية.');
        }

        $notification->update(['is_read' => true]);

        return back()->with('status', 'notification-read');
    }

    /**
     * تحديد كافة إشعارات المستخدم كمقروءة دفعة واحدة.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('status', 'all-notifications-read');
    }
}
