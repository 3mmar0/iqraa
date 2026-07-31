@extends('layouts.app')
@section('title', 'المحادثة المباشرة')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">المحادثة المباشرة</h1>
    @if ($messages->isEmpty())
        <x-empty-state message="لا رسائل محادثة مباشرة." />
    @else
        <ul class="space-y-2">
            @foreach ($messages as $message)
                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm">
                    {{ $message->sender?->name }} · تذكرة #{{ $message->ticket_id }}: {{ $message->body }}
                </li>
            @endforeach
        </ul>
    @endif
@endsection