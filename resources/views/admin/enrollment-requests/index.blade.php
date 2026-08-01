@extends('layouts.admin')

@section('title', 'طلبات الالتحاق')
@section('heading', 'طلبات الالتحاق')
@section('subheading', 'مراجعة طلبات الطلاب للالتحاق بالمقررات')

@section('content')
    <div class="mb-5 flex flex-wrap gap-2">
        @foreach (['pending' => 'معلّقة', 'approved' => 'مقبولة', 'rejected' => 'مرفوضة', 'all' => 'الكل'] as $key => $label)
            <a href="{{ route('admin.enrollment-requests.index', ['status' => $key]) }}"
               class="rounded-xl px-4 py-2 text-sm font-medium {{ request('status', 'pending') === $key ? 'bg-teal-700 text-white' : 'border border-slate-200 bg-white text-slate-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="space-y-4">
        @forelse ($requests as $item)
            <article class="rounded-2xl border border-[var(--color-line)] bg-white p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="font-semibold text-slate-900">{{ $item->course?->title }}</h2>
                        <p class="mt-1 text-sm text-slate-600">الطالب: {{ $item->user?->name }} ({{ $item->user?->email }})</p>
                        @if ($item->message)
                            <p class="mt-2 text-sm text-slate-700">{{ $item->message }}</p>
                        @endif
                        <p class="mt-2 text-xs text-slate-400">{{ $item->created_at?->diffForHumans() }} · الحالة: {{ $item->status }}</p>
                    </div>
                    @if ($item->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.enrollment-requests.approve', $item) }}">
                                @csrf
                                <button class="rounded-xl bg-teal-700 px-4 py-2 text-sm font-medium text-white">موافقة</button>
                            </form>
                            <form method="POST" action="{{ route('admin.enrollment-requests.reject', $item) }}">
                                @csrf
                                <button class="rounded-xl border border-rose-200 px-4 py-2 text-sm font-medium text-rose-700">رفض</button>
                            </form>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <x-empty-state message="لا توجد طلبات في هذه الحالة." />
        @endforelse
    </div>

    @if ($requests->hasPages())
        <div class="mt-4">{{ $requests->links() }}</div>
    @endif
@endsection
