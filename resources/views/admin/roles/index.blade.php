@extends('layouts.app')
@section('title', 'الأدوار')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">الأدوار والصلاحيات</h1>
    @foreach ($roles as $role)
        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4">
            <h2 class="mb-3 font-semibold">{{ $role->name_ar }} ({{ $role->slug }})</h2>
            @if (\Illuminate\Support\Facades\Route::has('admin.roles.update'))
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    @checked($role->permissions->contains('id', $permission->id))>
                                {{ $permission->name_ar ?? $permission->slug }}
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="rounded bg-teal-700 px-3 py-1.5 text-sm text-white">حفظ</button>
                </form>
            @endif
        </div>
    @endforeach
@endsection