@extends('layouts.admin')

@section('title', 'الإعلانات')
@section('heading', 'الإعلانات')
@section('subheading', 'إنشاء وجدولة ونشر الإعلانات')

@section('header-actions')
    <a href="{{ route('admin.announcements.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">إعلان جديد</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl border bg-white p-4 sm:grid-cols-3">
        <div class="sm:col-span-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="بحث بالعنوان..." class="w-full rounded-xl border px-3 py-2.5 text-sm">
        </div>
        <div>
            <select name="status" class="w-full rounded-xl border px-3 py-2.5 text-sm">
                <option value="">كل الحالات</option>
                <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                <option value="scheduled" @selected(request('status') === 'scheduled')>مجدول</option>
                <option value="published" @selected(request('status') === 'published')>منشور</option>
            </select>
        </div>
        <div class="sm:col-span-3 flex gap-2">
            <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm text-white">تصفية</button>
            <a href="{{ route('admin.announcements.index') }}" class="rounded-xl border px-4 py-2.5 text-sm">مسح</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">العنوان</th>
                    <th class="px-4 py-3 text-right">الكاتب</th>
                    <th class="px-4 py-3 text-right">المقرر</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">النشر</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($announcements as $announcement)
                    @php
                        $status = \App\Http\Controllers\Web\Admin\AnnouncementController::statusLabel($announcement);
                    @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $announcement->title }}</td>
                        <td class="px-4 py-3">{{ $announcement->author?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $announcement->course?->title ?? 'عام' }}</td>
                        <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs">{{ $status }}</span></td>
                        <td class="px-4 py-3 text-slate-500">{{ $announcement->published_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="rounded-lg border px-2 py-1 text-xs">تعديل</a>
                                <form method="POST" action="{{ route('admin.announcements.publish', $announcement) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">نشر</button></form>
                                <form method="POST" action="{{ route('admin.announcements.draft', $announcement) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">مسودة</button></form>
                                <form method="POST" action="{{ route('admin.announcements.sendNotification', $announcement) }}" class="inline">@csrf<button class="rounded-lg border px-2 py-1 text-xs">إشعار</button></form>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="inline" onsubmit="return confirm('حذف؟');">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-2 py-1 text-xs text-rose-700">حذف</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">لا إعلانات.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($announcements->hasPages())
            <div class="border-t px-4 py-3">{{ $announcements->links() }}</div>
        @endif
    </div>
@endsection
