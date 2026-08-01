<h3 class="mb-3 font-semibold">الملتحقون ({{ $course->enrollments->count() }})</h3>
<ul class="divide-y divide-slate-100 text-sm">
    @forelse ($course->enrollments as $enrollment)
        <li class="py-2">{{ $enrollment->user?->name }} <span class="text-slate-500">({{ $enrollment->user?->email }})</span></li>
    @empty
        <li class="py-6 text-slate-500">لا ملتحقين بعد.</li>
    @endforelse
</ul>
