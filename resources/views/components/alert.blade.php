@if (session('status'))
    <div class="mb-4 rounded-2xl border border-[var(--color-primary)]/25 bg-[var(--color-primary-light)] px-4 py-3 text-sm text-[var(--color-primary-hover)]" role="status">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-2xl border border-[var(--color-danger)]/30 bg-[var(--color-sand)] px-4 py-3 text-sm text-[var(--color-danger)]" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-2xl border border-[var(--color-danger)]/30 bg-[var(--color-sand)] px-4 py-3 text-sm text-[var(--color-danger)]" role="alert">
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
