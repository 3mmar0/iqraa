@extends('layouts.app')
@section('title', 'التذاكر')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">تذاكر الدعم</h1>
    @if ($tickets->isEmpty())
        <x-empty-state message="لا تذاكر." />
    @else
        <ul class="space-y-3">
            @foreach ($tickets as $ticket)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex justify-between gap-3">
                        <div>
                            <p class="font-medium">{{ $ticket->subject }}</p>
                            <p class="text-sm text-slate-600">{{ $ticket->student?->name }} · {{ $ticket->status }}</p>
                        </div>
                        @if (\Illuminate\Support\Facades\Route::has('support.tickets.show'))
                            <a href="{{ route('support.tickets.show', $ticket) }}" class="text-sm text-teal-700 hover:underline">عرض</a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-4">{{ $tickets->links() }}</div>
    @endif
@endsection