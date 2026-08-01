@php
    $paths = [
        'home' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0h6',
        'users' => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm14 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75',
        'shield' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
        'scroll' => 'M8 6h11a2 2 0 012 2v11a2 2 0 01-2 2H8M8 6a2 2 0 00-2 2v12a2 2 0 002 2h1M8 6V4a2 2 0 012-2h3',
        'cpu' => 'M9 9h6v6H9zM4 9h2m12 0h2M4 15h2m12 0h2M9 4v2m6-2v2M9 18v2m6-2v2',
        'bell' => 'M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0',
        'lock' => 'M7 11V7a5 5 0 0110 0v4M5 11h14v10H5z',
    ];
    $d = $paths[$icon] ?? $paths['home'];
@endphp
<svg class="h-5 w-5 shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
</svg>
