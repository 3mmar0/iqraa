@extends('layouts.admin')

@section('title', 'مستخدم جديد')
@section('heading', 'إنشاء مستخدم')
@section('subheading', 'إضافة حساب جديد مع الأدوار المناسبة')

@section('header-actions')
    <a href="{{ route('admin.users.index', ['type' => $type ?? 'students']) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">رجوع</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}" class="mx-auto max-w-3xl space-y-6">
        @csrf
        <input type="hidden" name="type" value="{{ $type ?? 'students' }}">
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 sm:p-6">
            <h2 class="mb-4 text-base font-semibold text-slate-900">البيانات الأساسية</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="name">الاسم</label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="email">البريد</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="phone">الهاتف</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="university">الجامعة</label>
                    <input id="university" name="university" value="{{ old('university') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="status">الحالة</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                        <option value="active" @selected(old('status', 'active') === 'active')>نشط</option>
                        <option value="invited" @selected(old('status') === 'invited')>مدعو</option>
                        <option value="disabled" @selected(old('status') === 'disabled')>معطّل</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="password">كلمة المرور</label>
                    <input id="password" type="password" name="password" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    <p class="mt-1 text-xs text-slate-500">مطلوبة إذا كانت الحالة «نشط».</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 sm:p-6">
            <h2 class="mb-4 text-base font-semibold text-slate-900">الأدوار</h2>
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($roles as $role)
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-3 py-3 text-sm hover:border-teal-300 hover:bg-teal-50/40">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(collect(old('roles', []))->contains($role->id) || $role->slug === 'student') class="rounded border-slate-300 text-teal-700 focus:ring-teal-600">
                        <span>
                            <span class="block font-medium text-slate-800">{{ $role->name_ar }}</span>
                            <span class="block text-xs text-slate-500">{{ $role->slug }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-xl bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">حفظ المستخدم</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">إلغاء</a>
        </div>
    </form>
@endsection
