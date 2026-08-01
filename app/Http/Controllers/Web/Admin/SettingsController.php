<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Settings\Services\PlatformSettingsService;

class SettingsController extends Controller
{
    public const TABS = [
        'general',
        'platform',
        'authentication',
        'email',
        'telegram',
        'payments',
        'media',
        'storage',
        'cache',
        'queue',
        'seo',
        'theme',
        'languages',
        'security',
        'backup',
        'maintenance',
        'logs',
    ];

    /** @var array<string, string> */
    private const TAB_LABELS = [
        'general' => 'عام',
        'platform' => 'المنصة',
        'authentication' => 'المصادقة',
        'email' => 'البريد',
        'telegram' => 'تيليجرام',
        'payments' => 'المدفوعات',
        'media' => 'الوسائط',
        'storage' => 'التخزين',
        'cache' => 'الذاكرة المؤقتة',
        'queue' => 'الطابور',
        'seo' => 'SEO',
        'theme' => 'المظهر',
        'languages' => 'اللغات',
        'security' => 'الأمان',
        'backup' => 'النسخ الاحتياطي',
        'maintenance' => 'الصيانة',
        'logs' => 'السجلات',
    ];

    public function __construct(private readonly PlatformSettingsService $settings)
    {
    }

    public function index(Request $request, string $tab = 'general'): View
    {
        if (! in_array($tab, self::TABS, true)) {
            abort(404);
        }

        $settings = $this->settings->all();
        $tabs = $this->buildTabs($tab);

        return view('admin.settings.index', compact('settings', 'tab', 'tabs'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tab = $request->validate([
            'tab' => ['required', 'string', Rule::in(self::TABS)],
        ])['tab'];

        if ($tab === 'languages') {
            return back()->with('status', 'الواجهة ثابتة بالعربية ولا يمكن تغييرها من هذه الصفحة.');
        }

        if ($tab === 'logs') {
            return back()->with('status', 'راجع تبويب السجلات للاطلاع على سجلات النظام.');
        }

        $allowedKeys = $this->keysForTab($tab);
        $values = [];

        foreach ($allowedKeys as $key) {
            $field = $this->fieldName($key);
            $default = PlatformSettingsService::DEFAULTS[$key] ?? null;

            if (is_bool($default)) {
                $values[$key] = $request->boolean($field);
            } elseif ($request->has($field)) {
                $values[$key] = $request->input($field);
            }
        }

        if ($values !== []) {
            $this->settings->setMany($values, $request->user());
        }

        return redirect()
            ->route('admin.settings.index', ['tab' => $tab])
            ->with('status', 'تم حفظ الإعدادات.');
    }

    /** @return list<array{label: string, href: string, active: bool}> */
    private function buildTabs(string $active): array
    {
        return array_map(fn (string $key) => [
            'label' => self::TAB_LABELS[$key],
            'href' => route('admin.settings.index', ['tab' => $key]),
            'active' => $key === $active,
        ], self::TABS);
    }

    /** @return list<string> */
    private function keysForTab(string $tab): array
    {
        $prefix = $tab.'.';

        return array_values(array_filter(
            array_keys(PlatformSettingsService::DEFAULTS),
            fn (string $key) => str_starts_with($key, $prefix),
        ));
    }

    private function fieldName(string $key): string
    {
        return str_replace('.', '_', $key);
    }
}
