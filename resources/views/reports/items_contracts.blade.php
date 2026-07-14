<!-- resources/views/reports/items_contracts.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Contrato</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <h5>Contrato: {{ $contract->description }} - {{ $contract->provider->description }} </h5>
    <h5>Sub-Componente: {{ $items[0]->component->description }}</h5>
    
    <table>
        <thead>
            <tr>
                <th>N° item</th>
                <th>Rubro (Cod. - Descripción)</th>
                <th>Cant.</th>
                <th>Unid.</th>
                <th>Precio UNIT. Mano de Obra</th>
                <th>Precio UNIT. Materiales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->item_number }}</td>
                    <td>{{ $item->rubro->code }} - {{ $item->rubro->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->rubro->orderPresentations->description }}</td>
                    <td>{{ number_format($item->unit_price_mo, '0', ',', '.') }}</td>
                    <td>{{ number_format($item->unit_price_mat, '0', ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4"><strong>TOTALES:</strong></td>
                <td><strong>{{ number_format($tot_price_mo, '0', ',', '.') }}</strong></td>
                <td><strong>{{ number_format($tot_price_mat, '0', ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>