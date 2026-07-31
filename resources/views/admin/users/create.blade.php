@extends('layouts.app')
@section('title', 'مستخدم جديد')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">إنشاء طالب (إداري)</h1>
    <form method="POST" action="{{ route('admin.users.store') }}" class="max-w-lg space-y-3 rounded-xl border border-slate-200 bg-white p-4">
        @csrf
        <input type="text" name="name" required value="{{ old('name') }}" placeholder="الاسم" class="w-full rounded border border-slate-300 px-3 py-2">
        <input type="email" name="email" required value="{{ old('email') }}" placeholder="البريد" class="w-full rounded border border-slate-300 px-3 py-2">
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="الهاتف" class="w-full rounded border border-slate-300 px-3 py-2">
        <select name="status" class="w-full rounded border border-slate-300 px-3 py-2">
            <option value="invited">مدعو</option>
            <option value="active">نشط</option>
        </select>
        <input type="password" name="password" placeholder="كلمة المرور (للنشط)" class="w-full rounded border border-slate-300 px-3 py-2">
        <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">حفظ</button>
    </form>
@endsection