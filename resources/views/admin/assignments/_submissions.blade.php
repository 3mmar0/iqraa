@php
    $submissionStatusLabels = [
        'submitted' => 'مُسلَّم',
        'graded' => 'مُقيَّم',
        'resubmit_requested' => 'طلب إعادة',
    ];
@endphp

@if ($assignment->submissions->isEmpty())
    <div class="p-5">
        <x-admin.empty-state title="لا تسليمات بعد" description="عندما يسلّم الطلاب الواجب ستظهر هنا." />
    </div>
@else
    <div class="divide-y divide-slate-100">
        @foreach ($assignment->submissions as $submission)
            <div class="space-y-3 px-5 py-4">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $submission->user?->name ?? '—' }}</p>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $submissionStatusLabels[$submission->status] ?? $submission->status }}
                            · {{ $submission->submitted_at?->format('Y-m-d H:i') ?? '—' }}
                            @if ($assignment->due_at && $submission->submitted_at && $submission->submitted_at->gt($assignment->due_at))
                                · <span class="text-amber-700">متأخر</span>
                            @endif
                            · الدرجة: {{ $submission->score ?? '—' }}
                        </p>
                        @if ($submission->body)
                            <p class="mt-2 whitespace-pre-line text-xs text-slate-600">{{ $submission->body }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap items-end gap-2">
                    <form method="POST" action="{{ route('admin.assignments.submissions.grade', [$assignment, $submission]) }}" class="flex flex-wrap items-end gap-2">
                        @csrf
                        @if (! empty($returnCourse))
                            @include('admin.courses._return_fields', ['course' => $returnCourse, 'tab' => 'assignments'])
                        @endif
                        <div>
                            <label class="admin-label">الدرجة</label>
                            <input type="number" min="0" max="100" step="0.01" name="score" required class="admin-input w-28" value="{{ $submission->score }}">
                        </div>
                        <button class="admin-btn admin-btn-primary admin-btn-sm">رصد الدرجة</button>
                    </form>
                    <form method="POST" action="{{ route('admin.assignments.submissions.resubmit', [$assignment, $submission]) }}" onsubmit="return confirm('طلب إعادة التسليم؟');">
                        @csrf
                        @if (! empty($returnCourse))
                            @include('admin.courses._return_fields', ['course' => $returnCourse, 'tab' => 'assignments'])
                        @endif
                        <button class="admin-btn admin-btn-ghost admin-btn-sm">طلب إعادة تسليم</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif
