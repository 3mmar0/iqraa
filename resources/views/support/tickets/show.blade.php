@extends('layouts.support')
@section('title', $ticket->subject)
@section('content')
    <h1 class="mb-2 text-2xl font-bold text-teal-900">{{ $ticket->subject }}</h1>
    <p class="mb-6 text-sm text-slate-600">{{ $ticket->student?->name }} · {{ $ticket->status }}</p>

    <div class="mb-6 space-y-3">
        @foreach ($ticket->messages as $message)
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-sm">
                <p class="font-medium">{{ $message->sender?->name ?? 'نظام' }}</p>
                <p class="mt-1">{{ $message->body }}</p>
            </div>
        @endforeach
    </div>

    @if ($ticket->status !== 'closed')
        @if (\Illuminate\Support\Facades\Route::has('support.tickets.reply'))
            <form method="POST" action="{{ route('support.tickets.reply', $ticket) }}" class="mb-4 max-w-xl space-y-3">
                @csrf
                <textarea name="body" rows="3" required class="w-full rounded border border-slate-300 px-3 py-2" placeholder="ردك"></textarea>
                <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">رد</button>
            </form>
        @endif
        @if (\Illuminate\Support\Facades\Route::has('support.tickets.close'))
            <form method="POST" action="{{ route('support.tickets.close', $ticket) }}">
                @csrf
                <button type="submit" class="rounded border border-red-300 px-4 py-2 text-red-700">إغلاق التذكرة</button>
            </form>
        @endif
    @endif
@endsection