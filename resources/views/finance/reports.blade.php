@extends('layouts.finance')
@section('title', 'تقارير مالية')
@section('content')
    <h1 class="mb-6 text-2xl font-bold text-teal-900">طلب تقرير</h1>
    @if (\Illuminate\Support\Facades\Route::has('finance.reports.store'))
        <form method="POST" action="{{ route('finance.reports.store') }}" class="max-w-md space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf
            <input type="text" name="type" required placeholder="نوع التقرير" class="w-full rounded border border-slate-300 px-3 py-2">
            <select name="format" class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="csv">CSV</option>
                <option value="xlsx">XLSX</option>
                <option value="pdf">PDF</option>
            </select>
            <button type="submit" class="rounded bg-teal-700 px-4 py-2 text-white">إرسال للطابور</button>
        </form>
    @endif
@endsection