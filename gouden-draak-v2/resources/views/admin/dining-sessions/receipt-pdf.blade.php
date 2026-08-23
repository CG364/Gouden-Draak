<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt - Table {{ $diningSession->table->nr }}</title>
    <style>
        @page {
            margin: 8pt;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 8pt;
            color: #111111;
        }

        .header {
            text-align: center;
            margin-bottom: 6pt;
        }

        .logo {
            max-width: 90pt;
            max-height: 50pt;
            margin-bottom: 4pt;
        }

        .restaurant-name {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
        }

        .meta {
            margin: 4pt 0 8pt;
            text-align: center;
            font-size: 7.5pt;
            color: #444444;
        }

        hr {
            margin: 6pt 0;
            border: none;
            border-top: 1px dashed #999999;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding-bottom: 3pt;
            border-bottom: 1px solid #333333;
            text-align: left;
            text-transform: uppercase;
            font-size: 7pt;
            color: #666666;
        }

        th.numeric,
        td.numeric {
            text-align: right;
        }

        td {
            padding: 2.5pt 0;
            vertical-align: top;
            font-size: 8pt;
        }

        .item-name {
            font-weight: bold;
        }

        .item-notes {
            display: block;
            font-weight: normal;
            font-style: italic;
            color: #555555;
            font-size: 7pt;
        }

        .total-row td {
            padding-top: 5pt;
            border-top: 1px solid #333333;
            font-weight: bold;
            font-size: 9pt;
        }

        .footer {
            margin-top: 10pt;
            text-align: center;
            font-size: 7pt;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- Place your logo at public/img/logo.png to have it appear here. --}}
        @if (file_exists(public_path('img/logo.png')))
            <img src="{{ public_path('img/logo.png') }}" class="logo" alt="De Gouden Draak">
        @endif
        <p class="restaurant-name">De Gouden Draak</p>
    </div>

    <p class="meta">
        Table {{ $diningSession->table->nr }}<br>
        {{ $diningSession->started_at->format('d-m-Y H:i') }}
    </p>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="numeric">Qty</th>
                <th class="numeric">Price</th>
                <th class="numeric">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lines as $line)
                <tr>
                    <td>
                        <span class="item-name">{{ $line['name'] }}</span>
                        @if ($line['notes'])
                            <span class="item-notes">{{ $line['notes'] }}</span>
                        @endif
                    </td>
                    <td class="numeric">{{ $line['quantity'] }}</td>
                    <td class="numeric">&euro;{{ number_format($line['unitPrice'], 2) }}</td>
                    <td class="numeric">&euro;{{ number_format($line['lineTotal'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No items ordered yet.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td class="numeric">&euro;{{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p class="footer">Thank you for dining with us!</p>
</body>
</html>
