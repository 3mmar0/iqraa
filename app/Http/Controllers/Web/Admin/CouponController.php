<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function index(Request $request): View
    {
        $query = Coupon::query()->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('code', 'like', "%{$search}%");
        }

        if ($request->query('active') === '1') {
            $query->where('active', true);
        } elseif ($request->query('active') === '0') {
            $query->where('active', false);
        }

        if ($type = $request->query('discount_type')) {
            $query->where('discount_type', $type);
        }

        $coupons = $query->paginate(20)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCoupon($request);
        $validated['active'] = $request->boolean('active');

        $coupon = Coupon::query()->create($validated);

        $this->audit->log($request->user(), 'coupon.created', Coupon::class, $coupon->id);

        return redirect()->route('admin.coupons.index')->with('status', 'تم إنشاء الكوبون.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $this->validateCoupon($request, $coupon);
        $validated['active'] = $request->boolean('active');

        $coupon->update($validated);

        $this->audit->log($request->user(), 'coupon.updated', Coupon::class, $coupon->id);

        return redirect()->route('admin.coupons.index')->with('status', 'تم تحديث الكوبون.');
    }

    public function destroy(Request $request, Coupon $coupon): RedirectResponse
    {
        $id = $coupon->id;
        $coupon->delete();

        $this->audit->log($request->user(), 'coupon.deleted', Coupon::class, $id);

        return redirect()->route('admin.coupons.index')->with('status', 'تم حذف الكوبون.');
    }

    public function activate(Request $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update(['active' => true]);
        $this->audit->log($request->user(), 'coupon.activated', Coupon::class, $coupon->id);

        return back()->with('status', 'تم تفعيل الكوبون.');
    }

    public function deactivate(Request $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update(['active' => false]);
        $this->audit->log($request->user(), 'coupon.deactivated', Coupon::class, $coupon->id);

        return back()->with('status', 'تم إيقاف الكوبون.');
    }

    public function generate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:50'],
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $created = 0;

        for ($i = 0; $i < (int) $validated['count']; $i++) {
            do {
                $code = Str::upper(Str::random(8));
            } while (Coupon::query()->where('code', $code)->exists());

            Coupon::query()->create([
                'code' => $code,
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'usage_limit' => $validated['usage_limit'] ?? null,
                'expires_at' => $validated['expires_at'] ?? null,
                'active' => true,
            ]);

            $created++;
        }

        $this->audit->log($request->user(), 'coupon.batch_generated', Coupon::class, null, [
            'count' => $created,
        ]);

        return back()->with('status', "تم إنشاء {$created} كوبون.");
    }

    public function duplicate(Request $request, Coupon $coupon): RedirectResponse
    {
        do {
            $code = $coupon->code.'-'.Str::upper(Str::random(4));
        } while (Coupon::query()->where('code', $code)->exists());

        $copy = Coupon::query()->create([
            'code' => Str::limit($code, 20, ''),
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'active' => false,
            'usage_limit' => $coupon->usage_limit,
            'used_count' => 0,
            'expires_at' => $coupon->expires_at,
        ]);

        $this->audit->log($request->user(), 'coupon.duplicated', Coupon::class, $copy->id, [
            'source_id' => $coupon->id,
        ]);

        return redirect()->route('admin.coupons.edit', $copy)->with('status', 'تم نسخ الكوبون.');
    }

    public function limitUsage(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validate([
            'usage_limit' => ['required', 'integer', 'min:1'],
        ]);

        $coupon->update(['usage_limit' => $validated['usage_limit']]);

        $this->audit->log($request->user(), 'coupon.limit_updated', Coupon::class, $coupon->id, [
            'usage_limit' => $validated['usage_limit'],
        ]);

        return back()->with('status', 'تم تحديث حد الاستخدام.');
    }

    public function assignCourse(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);

        // No coupon_course pivot in v1 — recorded in audit for later schema support.
        $this->audit->log($request->user(), 'coupon.course_assigned_stub', Coupon::class, $coupon->id, [
            'course_id' => $course->id,
            'course_title' => $course->title,
            'note' => 'Requires coupon_course pivot table for persistence.',
        ]);

        return back()->with('status', "تم تسجيل ربط الكوبون بالمقرر «{$course->title}» (يتطلب جدول pivot مستقبلاً).");
    }

    /** @return array<string, mixed> */
    private function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        return $request->validate([
            'code' => [
                'required', 'string', 'min:6', 'max:20', 'alpha_num',
                Rule::unique('coupons', 'code')->ignore($coupon?->id),
            ],
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'active' => ['sometimes', 'boolean'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ], [
            'code.required' => 'رمز الكوبون مطلوب.',
            'code.unique' => 'رمز الكوبون مستخدم مسبقاً.',
        ]);
    }
}
