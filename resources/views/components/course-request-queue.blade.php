@if ($requests->isEmpty())
    <p class="text-slate-500">لا توجد طلبات معلّقة.</p>
@else
    <ul class="space-y-4">
        @foreach ($requests as $item)
            <li class="rounded-xl border border-slate-200 bg-white p-4">
                <p class="font-medium">{{ $item->course->title }}</p>
                <p class="text-sm text-slate-600">الطالب: {{ $item->user->name }} ({{ $item->user->email }})</p>
                @if ($item->message)
                    <p class="mt-1 text-sm">{{ $item->message }}</p>
                @endif
                <div class="mt-3 flex gap-2">
                    <form method="POST" action="{{ route('staff.course-requests.approve', $item) }}">
                        @csrf
                        <button class="rounded bg-[var(--color-primary)] px-3 py-1.5 text-sm text-white">موافقة</button>
                    </form>
                    <form method="POST" action="{{ route('staff.course-requests.reject', $item) }}">
                        @csrf
                        <button class="rounded border border-red-300 px-3 py-1.5 text-sm text-red-700">رفض</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@endif
