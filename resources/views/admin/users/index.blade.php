@extends('layouts.app')
@section('title', 'المستخدمون')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-teal-900">المستخدمون</h1>
        @if (\Illuminate\Support\Facades\Route::has('admin.users.create'))
            <a href="{{ route('admin.users.create') }}" class="rounded bg-teal-700 px-4 py-2 text-sm text-white">مستخدم جديد</a>
        @endif
    </div>
    <ul class="space-y-2">
        @foreach ($users as $user)
            <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">
                {{ $user->name }} · {{ $user->email }} · {{ $user->status }} · {{ $user->roles->pluck('slug')->join(', ') }}
            </li>
        @endforeach
    </ul>
    <div class="mt-4">{{ $users->links() }}</div>
@endsection