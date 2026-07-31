@extends('layouts.app')

@section('title', 'طلبات الالتحاق')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">طابور طلبات الالتحاق</h1>
    @include('components.course-request-queue', ['requests' => $requests])
@endsection
