@extends('layouts.admin')

@section('title', $group->name)
@section('heading', $group->name)
@section('subheading', 'أعضاء المجموعة')

@section('header-actions')
    <a href="{{ route('admin.groups.edit', $group) }}" class="rounded-xl border bg-white px-4 py-2.5 text-sm">تعديل</a>
@endsection

@section('content')
    <div class="mb-6 grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border bg-white p-5">
            <dl class="grid gap-2 text-sm">
                <div><dt class="text-slate-500 inline">السنة: </dt><dd class="inline font-medium">{{ $group->academicYear?->name ?? '—' }}</dd></div>
                <div><dt class="text-slate-500 inline">الفصل: </dt><dd class="inline font-medium">{{ $group->semester?->name ?? '—' }}</dd></div>
                <div><dt class="text-slate-500 inline">الحالة: </dt><dd class="inline font-medium">{{ $group->status }}</dd></div>
            </dl>
        </div>
        <form method="POST" action="{{ route('admin.groups.attachMember', $group) }}" class="rounded-2xl border bg-white p-5">
            @csrf
            <label class="mb-2 block text-sm font-medium">إضافة عضو</label>
            <select name="user_id" required class="mb-3 w-full rounded-xl border px-3 py-2 text-sm">
                <option value="">اختر طالباً...</option>
                @foreach ($students as $student)
                    @if (! $group->users->contains('id', $student->id))
                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                    @endif
                @endforeach
            </select>
            <button class="rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm text-white">إضافة</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-right">الاسم</th>
                    <th class="px-4 py-3 text-right">البريد</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($group->users as $member)
                    <tr>
                        <td class="px-4 py-3">{{ $member->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->email }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.groups.detachMember', [$group, $member]) }}" class="inline" onsubmit="return confirm('إزالة العضو؟');">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-2 py-1 text-xs text-rose-700">إزالة</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-10 text-center text-slate-500">لا أعضاء.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
