<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Relatório</h1>

    <table>
        <thead>
            <tr>
                <th>Campo 1</th>
                <th>Campo 2</th>
                <th>Campo 3</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dados as $item)
                <tr>
                    <td>{{ $item['campo1'] ?? ' na'}}</td>
                    <td>{{ $item['campo2'] ?? ' na'}}</td>
                    <td>{{ $item['campo3'] ?? ' na'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>