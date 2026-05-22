@extends('reports.layout')

@section('title', 'Order Summary – ' . $group)

@section('content')
<div style="padding: 20px;">

    <h2 class="text-center" style="font-size: 13px; margin-bottom: 8px;">
        SUMMARY OF ORDERS<br>
        FOR CY {{ now()->year + 1 }}
    </h2>

    <p style="margin: 6px 0;"><strong>GROUP:</strong> <u>{{ strtoupper($group) }}</u></p>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">ITEMS</th>
                @foreach($offices as $office)
                    <th>{{ $office->name }}</th>
                @endforeach
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['item'] }}</td>
                    @foreach($offices as $office)
                        <td class="text-center">{{ $row['quantities'][$office->id] ?? '' }}</td>
                    @endforeach
                    <td class="text-center"><strong>{{ $row['total'] }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
