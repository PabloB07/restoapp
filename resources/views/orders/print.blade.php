<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden #{{ $order->id }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
            .container { margin: 0; padding: 0; }
        }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items th, .items td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .totals { margin-top: 20px; text-align: right; }
        .buttons { text-align: center; margin-top: 20px; }
        .btn { padding: 10px 20px; margin: 0 5px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ isset($companySettings) ? $companySettings->name : 'VASTAGO' }}</h1>
            @if(isset($companySettings))
                @if($companySettings->business_line)
                    <p>{{ $companySettings->business_line }}</p>
                @endif
                @if($companySettings->rut)
                    <p>RUT: {{ $companySettings->rut }}</p>
                @endif
                @if($companySettings->address)
                    <p>{{ $companySettings->address }}</p>
                @endif
                @if($companySettings->email)
                    <p>Email: {{ $companySettings->email }}</p>
                @endif
                @if($companySettings->phone)
                    <p>Tel: {{ $companySettings->phone }}</p>
                @endif
            @endif
            <h2>Orden #{{ $order->id }}</h2>
        </div>

        <div class="details">
            <p><strong>Mesa:</strong> {{ $order->table->title }}</p>
            <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Atendido por:</strong> {{ $order->user->username }}</p>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>${{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p><strong>Subtotal:</strong> ${{ number_format($order->total, 0, ',', '.') }}</p>
            <p><strong>Total con IVA:</strong> ${{ number_format($order->total * 1.19, 0, ',', '.') }}</p>
            <p><strong>Propina Sugerida (10%):</strong> ${{ number_format($order->total * 0.10, 0, ',', '.') }}</p>
            <hr>
            <h3>Total Final: ${{ number_format($order->total * 1.29, 0, ',', '.') }}</h3>
        </div>

        <div class="buttons no-print">
            <button onclick="window.print()" class="btn">Imprimir</button>
            <a href="{{ route(auth()->user()->role . '.orders.index') }}" class="btn">Volver</a>
        </div>
    </div>
</body>
</html>
