<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>فاتورة {{ $order->number }}</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; max-width: 800px; margin: 2rem auto; color: #1e293b; }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th, td { border: 1px solid #e2e8f0; padding: 0.5rem 0.75rem; text-align: right; }
        th { background: #f8fafc; }
        .meta { color: #64748b; font-size: 0.875rem; }
        .total { font-size: 1.25rem; font-weight: bold; margin-top: 1rem; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <button onclick="window.print()" style="margin-bottom:1rem;padding:0.5rem 1rem;">طباعة</button>
    <h1>فاتورة طلب</h1>
    <p class="meta">رقم: {{ $order->number }} · تاريخ: {{ $order->created_at?->format('Y-m-d') }}</p>
    <p><strong>العميل:</strong> {{ $order->user?->name }} · {{ $order->user?->email }}</p>
    @if ($order->coupon)
        <p><strong>كوبون:</strong> {{ $order->coupon->code }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>الوصف</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>المجموع</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ number_format((float) $item->unit_price, 2) }} {{ $order->currency }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((float) $item->unit_price * $item->quantity, 2) }} {{ $order->currency }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">الإجمالي: {{ number_format((float) $order->total, 2) }} {{ $order->currency }}</p>
    <p class="meta">الحالة: {{ $order->status }}</p>
</body>
</html>
