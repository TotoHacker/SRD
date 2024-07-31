<!DOCTYPE html>
<html>
<head>
    <title>Préstamos del Día</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        h1 {
            font-size: 24px;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
        }
        h2 {
            font-size: 20px;
            margin-top: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #31aa7c;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ public_path('Imagenes/encabezado.png') }}" alt="Encabezado">
        <h1>Préstamos del Día</h1>
        <p>Total de Préstamos: <strong>{{ $totalLoans }}</strong></p>

        <h2>Recursos</h2>
        <table>
            <thead>
                <tr>
                    <th>Recurso</th>
                    <th>Préstamos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resourceLoans as $resourceLoan)
                <tr>
                    <td>{{ $resourceLoan['name'] }}</td>
                    <td>{{ $resourceLoan['count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Lista de Préstamos</h2>
        <table>
            <thead>
                <tr>
                    <th>Recurso</th>
                    <th>Horario</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                @if($loan->resource)
                <tr>
                    <td>{{ $loan->resource->name }}</td>
                    <td>{{ $loan->loan_date->format('H:i') }} - {{ $loan->return_date->format('H:i') }}</td>
                    <td>{{ $loan->loan_date->format('d M Y') }}</td>
                    <td>{{ $loan->user->NameUser }}</td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="4" class="no-data">No hay préstamos registrados para hoy.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
