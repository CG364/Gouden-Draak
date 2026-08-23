<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('menu.title') }} - De Gouden Draak</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin: 0;
            color: #7f1d1d;
        }

        .tagline {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin: 4px 0 20px;
        }

        h2 {
            font-size: 15px;
            border-bottom: 1px solid #7f1d1d;
            padding-bottom: 4px;
            margin-top: 22px;
            color: #7f1d1d;
        }

        h2.special {
            color: #b91c1c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px 0;
            vertical-align: top;
            border-bottom: 1px dashed #cccccc;
            font-size: 11px;
        }

        .menu-number {
            color: #888888;
            width: 26px;
        }

        .dish-name {
            font-weight: bold;
        }

        .dish-description {
            font-weight: normal;
            color: #555555;
            font-size: 9px;
        }

        .price {
            text-align: right;
            white-space: nowrap;
            width: 70px;
        }

        .price-original {
            display: block;
            text-decoration: line-through;
            color: #999999;
            font-size: 9px;
        }

        .price-discounted {
            color: #b91c1c;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>De Gouden Draak</h1>
    <p class="tagline">{{ __('menu.title') }}</p>

    @foreach ($dishKinds as $dishKind)
    <h2 class="{{ $dishKind['id'] === 'special-offers' ? 'special' : '' }}">{{ $dishKind['name'] }}</h2>

    <table>
        @foreach ($dishKind['dishes'] as $dish)
        <tr>
            <td class="menu-number">{{ $dish['menuNumber'] }}</td>
            <td>
                <span class="dish-name">{{ $dish['name'] }}</span>
                @if ($dish['description'])
                <span class="dish-description"> - {{ $dish['description'] }}</span>
                @endif
            </td>
            <td class="price">
                @if ($dish['discountedPrice'] !== null)
                <span class="price-original">&euro; {{ number_format($dish['price'], 2) }}</span>
                <span class="price-discounted">&euro; {{ number_format($dish['discountedPrice'], 2) }}</span>
                @else
                &euro; {{ number_format($dish['price'], 2) }}
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    @endforeach
</body>

</html>