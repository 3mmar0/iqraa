@extends('layouts.app')

@section('title', 'غير مصرح')

@section('content')
    <div class="rounded-xl border border-red-200 bg-red-50 p-8 text-center">
        <h1 class="text-2xl font-bold text-red-800">غير مصرح</h1>
        <p class="mt-2 text-red-700">{{ $exception->getMessage() ?: 'ليس لديك صلاحية الوصول لهذه الصفحة.' }}</p>
        <a href="{{ url('/') }}" class="mt-4 inline-block text-teal-700 hover:underline">العودة للرئيسية</a>
    </div>
@endsection
