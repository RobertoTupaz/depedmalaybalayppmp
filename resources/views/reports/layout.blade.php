<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPMP Report')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #000; background: #fff; }
        h1, h2, h3, h4 { font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 3px 5px; vertical-align: middle; }
        thead th { background-color: #e0e0e0; font-weight: bold; text-align: center; }
        tfoot th { background-color: #e0e0e0; font-weight: bold; }
        .no-border td, .no-border th { border: none; }
        .print-btn { position: fixed; top: 10px; right: 10px; padding: 8px 16px; background: #1d4ed8; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
        @media print {
            .print-btn { display: none; }
            body { font-size: 9px; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print / Save PDF</button>
    @yield('content')
</body>
</html>
