<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra #{{ $purchaseOrder->number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 80px;
        }

        .header .company-info {
            display: inline-block;
            vertical-align: top;
            text-align: left;
            margin-left: 20px;
        }

        h1,
        h2,
        h3 {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .totals {
            text-align: right;
        }

        .totals th,
        .totals td {
            border: none;
        }

        .totals td {
            padding-right: 20px;
        }

        .company-details,
        .provider-details {
            margin-bottom: 20px;
        }

        .company-details p,
        .provider-details p {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ storage_path('app/public/' . $purchaseOrder->company->logo_path) }}" alt="Logo de la Empresa">
        <div class="company-info">
            <h1>{{ $purchaseOrder->company->company_name }}</h1>
            <p><strong>RUC:</strong> {{ $purchaseOrder->company->ruc }}</p>
            <p><strong>Dirección:</strong> {{ $purchaseOrder->company->address }}</p>
            <p><strong>Correo Electrónico:</strong> {{ $purchaseOrder->company->email }}</p>
        </div>
    </div>

    <hr>

    <h2>Orden de Compra #{{ $purchaseOrder->number }}</h2>
    <p><strong>Fecha de Creación:</strong> {{ $purchaseOrder->created_at->format('d/m/Y') }}</p>
    <p><strong>Fecha Esperada de Entrega:</strong>
        {{ \Carbon\Carbon::parse($purchaseOrder->expected_delivery)->format('d/m/Y') }}</p>
    <p><strong>Estado:</strong> {{ $purchaseOrder->status }}</p>
    <p><strong>Notas:</strong> {{ $purchaseOrder->notes ?? 'Ninguna' }}</p>

    <div class="provider-details">
        <h3>Datos del Proveedor</h3>
        <p><strong>Nombre:</strong> {{ $purchaseOrder->provider->name }}</p>
        <p><strong>RUC:</strong> {{ $purchaseOrder->provider->ruc }}</p>
        <p><strong>Dirección:</strong> {{ $purchaseOrder->provider->address }}</p>
        <p><strong>Teléfono:</strong> {{ $purchaseOrder->provider->phone }}</p>
    </div>

    <h3>Detalles de la Orden de Compra</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Fecha de Vencimiento</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Sub Total</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->purchaseOrderDetail as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->product->code }}</td>
                    <td>{{ $detail->product->description ?? '--' }}</td>
                    <td>{{ $detail->expiration_date ? \Carbon\Carbon::parse($detail->expiration_date)->format('d/m/Y') : '--' }}
                    </td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->price, 2) }}</td>
                    <td>{{ number_format($detail->sub_total = $detail->quantity * $detail->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <th>Total de la Orden:</th>
            <td>S/ {{ number_format($purchaseOrder->amount, 2) }}</td>
        </tr>
    </table>

    <h3>Usuario que Generó la Orden</h3>
    <p><strong>Nombre:</strong> {{ $purchaseOrder->user->name }}</p>
    <p><strong>DNI:</strong> {{ $purchaseOrder->user->dni }}</p>
    <p><strong>Teléfono:</strong> {{ $purchaseOrder->user->phone }}</p>

    <h3>Datos del Almacén</h3>
    <p><strong>Nombre:</strong> {{ $purchaseOrder->warehouse->name }}</p>
    <p><strong>Ubicación:</strong> {{ $purchaseOrder->warehouse->location }}</p>

    {{-- <div class="footer">
        <p>Gracias por su preferencia.</p>
    </div> --}}
</body>

</html>
