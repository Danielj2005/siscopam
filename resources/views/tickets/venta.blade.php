<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 78mm;
            margin: 0 auto;
            padding: 5mm;
            font-size: 11px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 2px 0; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <div class="bold" style="font-size: 13px;">{{ $venta->empresa->nombre }}</div>
        <div>RIF: {{ $venta->empresa->rif }}</div>
        <div>{{ $venta->sede->nombre }}</div>
        <div>{{ $venta->sede->direccion }}</div>
    </div>

    <div class="divider"></div>

    <div><strong>Ticket:</strong> TICK-{{ strtoupper(substr($venta->id, 0, 8)) }}</div>
    <div><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y h:i A') }}</div>
    <div><strong>Cajero:</strong> {{ $venta->vendedor->name }}</div>
    <div><strong>Cliente:</strong> {{ $venta->cliente ? $venta->cliente->nombre . ' ' . $venta->cliente->apellido : 'CONTADO' }}</div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>CANT / PROD</th>
                <th class="text-right">P.USD</th>
                <th class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $det)
            <tr>
                <td colspan="3" class="bold">{{ $det->producto->nombre }}</td>
            </tr>
            <tr>
                <td>{{ $det->cantidad }} x ${{ number_format($det->precio_unitario_usd, 2) }}</td>
                <td></td>
                <td class="text-right">${{ number_format($det->subtotal_usd, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="bold">TOTAL USD:</td>
            <td class="text-right bold">${{ number_format($venta->total_usd, 2) }}</td>
        </tr>
        <tr>
            <td>TASA BCV:</td>
            <td class="text-right">{{ number_format($venta->tasa_bcv_usada, 2) }} VES</td>
        </tr>
        <tr>
            <td class="bold">TOTAL VES:</td>
            <td class="text-right bold">Bs. {{ number_format($venta->total_ves, 2) }}</td>
        </tr>
        <tr>
            <td>MÉTODO:</td>
            <td class="text-right">{{ $venta->metodo_pago }}</td>
        </tr>
        @if($venta->referencia)
        <tr>
            <td>REF:</td>
            <td class="text-right">{{ $venta->referencia }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>
    <div class="text-center" style="margin-top: 8px;">
        ¡Gracias por su compra!<br>
        <strong>SisCoPaM SaaS</strong>
    </div>
</body>
</html>