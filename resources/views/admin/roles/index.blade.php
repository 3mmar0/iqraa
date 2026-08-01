@extends('layouts.admin')

@section('title', 'الأدوار والصلاحيات')
@section('heading', 'الأدوار والصلاحيات')
@section('subheading', 'تحكم في من يستطيع الموافقة على الالتحاق والوصول للوحات')

@section('content')
    <div class="space-y-5">
        @foreach ($roles as $role)
            <section class="overflow-hidden rounded-2xl border border-[var(--color-line)] bg-white shadow-[0_8px_24px_-16px_rgba(12,31,28,0.3)]">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/80 px-5 py-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $role->name_ar }}</h2>
                        <p class="text-xs text-slate-500">{{ $role->slug }} · لوحة: {{ $role->dashboard_key ?: '—' }}</p>
                    </div>
                    <span class="rounded-full bg-[var(--color-primary-light)] px-3 py-1 text-xs font-medium text-[var(--color-primary-hover)]">{{ $role->permissions->count() }} صلاحية</span>
                </div>
                <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="p-5">
                    @csrf
                    @method('PUT')
                    <div class="mb-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($permissions as $permission)
                            <label class="flex items-start gap-3 rounded-xl border border-slate-200 px-3 py-3 text-sm hover:border-[var(--color-primary)] hover:bg-[var(--color-primary-light)]/30">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       @checked($role->permissions->contains('id', $permission->id))
                                       class="mt-0.5 rounded border-slate-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                <span>
                                    <span class="block font-medium text-slate-800">{{ $permission->name_ar }}</span>
                                    <span class="block text-xs text-slate-500">{{ $permission->slug }}</span>
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500">لا توجد صلاحيات معرّفة بعد.</p>
                        @endforelse
                    </div>
                    <button type="submit" class="rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)]">حفظ صلاحيات {{ $role->name_ar }}</button>
                </form>
            </section>
        @endforeach
    </div>
@endsection
