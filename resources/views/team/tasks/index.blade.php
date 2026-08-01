@extends('layouts.team')
@section('title', 'المهام')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-[var(--color-ink)]">مهام الفريق</h1>
    @if ($tasks->isEmpty())
        <x-empty-state message="لا مهام." />
    @else
        <ul class="space-y-3">
            @foreach ($tasks as $task)
                <li class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="font-medium">{{ $task->title }}</p>
                    <p class="text-sm text-slate-600">{{ $task->assignee?->name }} · {{ $task->status }}</p>
                    @if (\Illuminate\Support\Facades\Route::has('team.tasks.update'))
                        <form method="POST" action="{{ route('team.tasks.update', $task) }}" class="mt-2 flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="rounded border border-slate-300 px-2 py-1 text-sm">
                                @foreach (['open','in_progress','done','cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($task->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded bg-[var(--color-primary)] px-3 py-1 text-sm text-white">تحديث</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
@endsection