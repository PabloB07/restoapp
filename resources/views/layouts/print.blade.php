<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Voucher</title>
    <style>
        @page {
            margin: 0;
            size: 80mm 297mm;
        }

        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 5mm;
            }
            .no-print { display: none; }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .print-area { padding: 10px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px dashed #ccc;
        }

        .text-center { text-align: center; }
        .text-sm { font-size: 11px; }
        .mb-4 { margin-bottom: 1rem; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    @yield('content')
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() { window.close(); }, 500);
        }
    </script>
</body>
</html>
