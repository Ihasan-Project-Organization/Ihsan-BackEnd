<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    /**
     * استعراض إعدادات النظام العامة وعتبات الترقية.
     */
    public function index(): View
    {
        $settings = [
            'tier_2_tasks_threshold'  => (int) SystemSetting::get('tier_2_tasks_threshold', 10),
            'tier_2_rating_threshold' => (float) SystemSetting::get('tier_2_rating_threshold', 4.0),
            'tier_3_tasks_threshold'  => (int) SystemSetting::get('tier_3_tasks_threshold', 30),
            'tier_3_rating_threshold' => (float) SystemSetting::get('tier_3_rating_threshold', 4.3),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * تحديث إعدادات عتبات الترقية للنظام.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tier_2_tasks_threshold'  => ['required', 'integer', 'min:1'],
            'tier_2_rating_threshold' => ['required', 'numeric', 'between:1.0,5.0'],
            'tier_3_tasks_threshold'  => ['required', 'integer', 'gt:tier_2_tasks_threshold'],
            'tier_3_rating_threshold' => ['required', 'numeric', 'between:1.0,5.0'],
        ], [
            'tier_2_tasks_threshold.required'  => 'عتبة مهام المستوى الثاني مطلوبة.',
            'tier_2_tasks_threshold.integer'   => 'عتبة مهام المستوى الثاني يجب أن تكون رقماً صحيحاً.',
            'tier_2_tasks_threshold.min'       => 'عتبة مهام المستوى الثاني يجب ألا تقل عن مهمة واحدة.',
            'tier_2_rating_threshold.required' => 'عتبة تقييم المستوى الثاني مطلوبة.',
            'tier_2_rating_threshold.numeric'  => 'عتبة تقييم المستوى الثاني يجب أن تكون قيمة رقمية.',
            'tier_2_rating_threshold.between'  => 'عتبة تقييم المستوى الثاني يجب أن تكون بين 1.0 و 5.0.',
            'tier_3_tasks_threshold.required'  => 'عتبة مهام المستوى الثالث مطلوبة.',
            'tier_3_tasks_threshold.integer'   => 'عتبة مهام المستوى الثالث يجب أن تكون رقماً صحيحاً.',
            'tier_3_tasks_threshold.gt'        => 'عتبة مهام المستوى الثالث يجب أن تكون أكبر من عتبة المستوى الثاني.',
            'tier_3_rating_threshold.required' => 'عتبة تقييم المستوى الثالث مطلوبة.',
            'tier_3_rating_threshold.numeric'  => 'عتبة تقييم المستوى الثالث يجب أن تكون قيمة رقمية.',
            'tier_3_rating_threshold.between'  => 'عتبة تقييم المستوى الثالث يجب أن تكون بين 1.0 و 5.0.',
        ]);

        SystemSetting::set(
            'tier_2_tasks_threshold',
            $validated['tier_2_tasks_threshold'],
            'الحد الأدنى لعدد المهام المكتملة للانتقال إلى المستوى الثاني (Tier 2)'
        );

        SystemSetting::set(
            'tier_2_rating_threshold',
            $validated['tier_2_rating_threshold'],
            'الحد الأدنى لمتوسط التقييم للانتقال إلى المستوى الثاني (Tier 2)'
        );

        SystemSetting::set(
            'tier_3_tasks_threshold',
            $validated['tier_3_tasks_threshold'],
            'الحد الأدنى لعدد المهام المكتملة للانتقال إلى المستوى الثالث (Tier 3)'
        );

        SystemSetting::set(
            'tier_3_rating_threshold',
            $validated['tier_3_rating_threshold'],
            'الحد الأدنى لمتوسط التقييم للانتقال إلى المستوى الثالث (Tier 3)'
        );

        \App\Models\AdminAuditLog::log(
            'updated_settings',
            'SystemSetting',
            null,
            'تم تحديث عتبات الترقية ومعايير النظام العامة بنجاح',
            $validated
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم حفظ إعدادات النظام وعتبات الترقية بنجاح.');
    }
}
