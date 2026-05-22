<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Order;
use App\Models\Supply;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function myPpmp(Office $office): View
    {
        $orders = $office->orders()->with('supply')->orderBy('created_at')->get();

        return view('reports.my-ppmp', compact('office', 'orders'));
    }

    public function groupPpmp(string $group): View
    {
        $offices = Office::where('group', $group)->orderBy('name')->get();

        $supplyIds = Order::whereIn('office_id', $offices->pluck('id'))
            ->distinct()
            ->pluck('supply_id');

        $supplySummaries = Supply::whereIn('id', $supplyIds)
            ->orderBy('item')
            ->get()
            ->map(function (Supply $supply) use ($offices) {
                $cols     = array_fill(0, 16, 0);
                $totalQty = 0;

                Order::whereIn('office_id', $offices->pluck('id'))
                    ->where('supply_id', $supply->id)
                    ->get()
                    ->each(function (Order $order) use (&$cols, &$totalQty) {
                        $month = (int) explode('-', $order->month_needed)[1];
                        $qty   = $order->quantity;
                        $totalQty += $qty;

                        if ($month >= 1 && $month <= 3) {
                            $cols[$month - 1] += $qty;
                            $cols[3]          += $qty;
                        } elseif ($month >= 4 && $month <= 6) {
                            $cols[$month]  += $qty;
                            $cols[7]       += $qty;
                        } elseif ($month >= 7 && $month <= 9) {
                            $cols[$month + 1] += $qty;
                            $cols[11]         += $qty;
                        } elseif ($month >= 10 && $month <= 12) {
                            $cols[$month + 2] += $qty;
                            $cols[15]         += $qty;
                        }
                    });

                return ['supply' => $supply, 'cols' => $cols, 'totalQty' => $totalQty];
            });

        return view('reports.group-ppmp', compact('group', 'supplySummaries'));
    }

    public function orderSummary(string $group): View
    {
        $offices = Office::where('group', $group)->orderBy('name')->get();

        $rows = Order::whereIn('office_id', $offices->pluck('id'))
            ->with('supply')
            ->get()
            ->groupBy('supply_id')
            ->map(function ($orders) use ($offices) {
                $quantities = [];
                $total      = 0;

                foreach ($offices as $office) {
                    $qty                    = $orders->where('office_id', $office->id)->sum('quantity');
                    $quantities[$office->id] = $qty ?: null;
                    $total                  += $qty;
                }

                return [
                    'item'       => $orders->first()->supply->item,
                    'quantities' => $quantities,
                    'total'      => $total,
                ];
            })
            ->sortBy('item')
            ->values();

        return view('reports.order-summary', compact('group', 'offices', 'rows'));
    }
}
