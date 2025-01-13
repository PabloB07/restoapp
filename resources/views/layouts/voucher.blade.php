<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        @page {
            margin: 0;
            size: 80mm 297mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 8px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        .details {
            margin: 10px 0;
            font-size: 12px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .items-table th {
            border-bottom: 1px solid #000;
            text-align: left;
            padding: 5px 2px;
        }
        .items-table td {
            padding: 5px 2px;
            border-bottom: 1px dashed #ccc;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    @yield('content')

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Cerrar</button>
    </div>
</body>
</html>
