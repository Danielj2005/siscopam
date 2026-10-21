<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago - {{ $pago['numero_comprobante'] }}</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .brand-sub {
            font-size: 10px;
            color: #64748b;
        }
        .badge-comprobante {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: right;
        }
        .badge-title {
            font-size: 12px;
            font-weight: bold;
            color: #475569;
        }
        .badge-number {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }
        .section-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-top: 15px;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
        }
        .items-table {
            margin-top: 15px;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            text-transform: uppercase;
            padding: 7px 10px;
            text-align: left;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals-table {
            margin-top: 15px;
            width: 45%;
            float: right;
        }
        .totals-table td {
            padding: 4px 8px;
        }
        .total-row {
            background-color: #f1f5f9;
            font-size: 13px;
            font-weight: bold;
        }
        .clear {
            clear: both;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
        .legal-notice {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 8px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- Encabezado / Branding Empresa -->
    <table class="header-table">
        <tr>
            <td width="60%">
                <div class="brand-title">{{ $pago['empresa_nombre'] }}</div>
                <div class="brand-sub">RIF: {{ $pago['empresa_rif'] }}</div>
                <div class="brand-sub">Sede: {{ $pago['sede_nombre'] }} | {{ $pago['sede_direccion'] }}</div>
            </td>
            <td width="40%">
                <div class="badge-comprobante">
                    <div class="badge-title">COMPROBANTE DE PAGO</div>
                    <div class="badge-number">{{ $pago['numero_comprobante'] }}</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                        Fecha: {{ $pago['fecha_emision'] }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Datos del Cliente y Métodos de Pago -->
    <table style="margin-top: 10px;">
        <tr>
            <td width="50%" style="padding-right: 6px;">
                <div class="section-box">
                    <div class="section-title">Datos del Atleta / Cliente</div>
                    <strong>Nombre:</strong> {{ $pago['cliente_nombre'] }}<br>
                    <strong>Cédula / RIF:</strong> {{ $pago['cliente_cedula'] }}<br>
                    <strong>Correo:</strong> {{ $pago['cliente_correo'] ?? 'N/A' }}
                </div>
            </td>
            <td width="50%" style="padding-left: 6px;">
                <div class="section-box">
                    <div class="section-title">Detalles de la Transacción</div>
                    <strong>Método:</strong> {{ $pago['metodo_pago'] }}<br>
                    <strong>Referencia:</strong> {{ $pago['referencia'] ?? 'EFECTIVO' }}<br>
                    <strong>Tasa BCV Aplicada:</strong> Bs. {{ number_format($pago['tasa_bcv'], 2, ',', '.') }} / USD
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabla de Ítems / Conceptos -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Concepto / Descripción</th>
                <th width="15%" style="text-align: center;">Cant.</th>
                <th width="17%" style="text-align: right;">Precio (USD)</th>
                <th width="18%" style="text-align: right;">Total (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pago['items'] as $item)
                <tr>
                    <td>
                        <strong>{{ $item['nombre'] }}</strong>
                        @if(!empty($item['descripcion']))
                            <br><span style="font-size: 9px; color: #64748b;">{{ $item['descripcion'] }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item['cantidad'] }}</td>
                    <td style="text-align: right;">${{ number_format($item['precio_usd'], 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item['total_usd'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totales -->
    <table class="totals-table">
        <tr>
            <td>Subtotal USD:</td>
            <td style="text-align: right;">${{ number_format($pago['total_usd'], 2) }}</td>
        </tr>
        <tr>
            <td>Monto Oficial VES:</td>
            <td style="text-align: right;">Bs. {{ number_format($pago['total_ves'], 2, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL PAGADO:</td>
            <td style="text-align: right; color: #16a34a;">${{ number_format($pago['total_usd'], 2) }}</td>
        </tr>
    </table>

    <div class="clear"></div>

    <!-- Pie de página y aviso de validez no fiscal -->
    <div class="footer">
        <div>¡Gracias por tu pago y preferencia!</div>
        <div class="legal-notice">
            * Este documento es un comprobante de recibo de caja digital no sustitutivo de factura fiscal. Emitido por el sistema SisCoPaM.
        </div>
        <div style="margin-top: 6px; font-weight: bold; color: #475569;">
            Desarrollado por Create Tech Solutions
        </div>
    </div>

</body>
</html>