@props(['status' => 'draft', 'label' => null])

@php
    $map = [
        'published' => ['class' => 'admin-chip-published', 'label' => 'منشور'],
        'draft' => ['class' => 'admin-chip-draft', 'label' => 'مسودة'],
        'archived' => ['class' => 'admin-chip-archived', 'label' => 'مؤرشف'],
        'hidden' => ['class' => 'admin-chip-hidden', 'label' => 'مخفي'],
        'scheduled' => ['class' => 'admin-chip-scheduled', 'label' => 'مجدول'],
        'active' => ['class' => 'admin-chip-active', 'label' => 'نشط'],
        'disabled' => ['class' => 'admin-chip-disabled', 'label' => 'معطّل'],
    ];
    $meta = $map[$status] ?? ['class' => 'admin-chip-draft', 'label' => $status];
    $text = $label ?? $meta['label'];
@endphp

<span {{ $attributes->merge(['class' => 'admin-chip '.$meta['class']]) }}>{{ $text }}</span>
