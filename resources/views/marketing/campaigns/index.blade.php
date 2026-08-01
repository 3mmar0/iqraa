@extends('layouts.marketing')
@section('title', 'الحملات')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-[var(--color-ink)]">الحملات</h1>
        @if (\Illuminate\Support\Facades\Route::has('marketing.campaigns.create'))
            <a href="{{ route('marketing.campaigns.create') }}" class="rounded bg-[var(--color-primary)] px-4 py-2 text-sm text-white">حملة جديدة</a>
        @endif
    </div>
    @if ($campaigns->isEmpty())
        <x-empty-state message="لا حملات." />
    @else
        <ul class="space-y-2">
            @foreach ($campaigns as $campaign)
                <li class="rounded-xl border border-slate-200 bg-white p-4">{{ $campaign->name }} · {{ $campaign->status }}</li>
            @endforeach
        </ul>
    @endif
@endsection