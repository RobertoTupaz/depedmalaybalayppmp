@extends('reports.layout')

@section('title', 'Group PPMP – ' . $group)

@section('content')
<div style="padding: 20px;">

    <h2 class="text-center" style="font-size: 13px; margin-bottom: 4px;">
        REVISED PROJECT PROCUREMENT MANAGEMENT PLAN<br>
        FOR CY {{ now()->year + 1 }}
    </h2>

    <p style="margin: 6px 0;"><strong>GROUP:</strong> <u>{{ strtoupper($group) }}</u></p>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 20%;">ITEM AND SPECIFICATION</th>
                <th rowspan="2">Unit</th>
                <th colspan="16">QUANTITY REQUIREMENT</th>
                <th rowspan="2">TOTAL QTY</th>
                <th rowspan="2">UNIT PRICE</th>
                <th rowspan="2">MARK-UP PRICE</th>
                <th rowspan="2">TOTAL AMOUNT</th>
            </tr>
            <tr>
                <th>Jan</th><th>Feb</th><th>Mar</th><th>Q1</th>
                <th>Apr</th><th>May</th><th>Jun</th><th>Q2</th>
                <th>Jul</th><th>Aug</th><th>Sep</th><th>Q3</th>
                <th>Oct</th><th>Nov</th><th>Dec</th><th>Q4</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0.0; @endphp
            @foreach($supplySummaries as $row)
                @php
                    $supply     = $row['supply'];
                    $mup        = $supply->markedUpPrice();
                    $cols       = $row['cols'];   // array of 16 values
                    $totalQty   = $row['totalQty'];
                    $lineTotal  = round($mup * $totalQty, 2);
                    $grandTotal += $lineTotal;
                @endphp
                <tr>
                    <td>{{ $supply->item }}</td>
                    <td class="text-center">{{ $supply->unit_of_measure }}</td>
                    @for($i = 0; $i < 16; $i++)
                        <td class="text-center">{{ $cols[$i] ?: '' }}</td>
                    @endfor
                    <td class="text-center">{{ $totalQty }}</td>
                    <td class="text-right">{{ number_format($supply->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($mup, 2) }}</td>
                    <td class="text-right">{{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="21" class="text-right">TOTAL</th>
                <th class="text-right">{{ number_format($grandTotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>

</div>
@endsection
