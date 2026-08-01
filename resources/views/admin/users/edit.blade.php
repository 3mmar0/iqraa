@extends('layouts.admin')

@section('title', 'تعديل مستخدم')
@section('heading', 'تعديل مستخدم')
@section('subheading', $user->email)

@section('header-actions')
    <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">رجوع</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mx-auto max-w-3xl space-y-6">
        @csrf
        @method('PUT')
        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 sm:p-6">
            <h2 class="mb-4 text-base font-semibold text-slate-900">البيانات الأساسية</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="name">الاسم</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="email">البريد</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="phone">الهاتف</label>
                    <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="university">الجامعة</label>
                    <input id="university" name="university" value="{{ old('university', $user->university) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="status">الحالة</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                        <option value="active" @selected(old('status', $user->status) === 'active')>نشط</option>
                        <option value="invited" @selected(old('status', $user->status) === 'invited')>مدعو</option>
                        <option value="disabled" @selected(old('status', $user->status) === 'disabled')>معطّل</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="password">كلمة مرور جديدة</label>
                    <input id="password" type="password" name="password" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="password_confirmation">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--color-line)] bg-white p-5 sm:p-6">
            <h2 class="mb-4 text-base font-semibold text-slate-900">الأدوار</h2>
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($roles as $role)
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-3 py-3 text-sm hover:border-teal-300 hover:bg-teal-50/40">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                               @checked(collect(old('roles', $user->roles->pluck('id')->all()))->contains($role->id))
                               class="rounded border-slate-300 text-teal-700 focus:ring-teal-600">
                        <span>
                            <span class="block font-medium text-slate-800">{{ $role->name_ar }}</span>
                            <span class="block text-xs text-slate-500">{{ $role->slug }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="rounded-xl bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">حفظ التغييرات</button>
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">إلغاء</a>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mx-auto mt-6 max-w-3xl" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم نهائياً؟');">
        @csrf
        @method('DELETE')
        <button type="submit" class="rounded-xl border border-rose-200 px-5 py-2.5 text-sm font-medium text-rose-700 hover:bg-rose-50">حذف المستخدم</button>
    </form>
@endsection
