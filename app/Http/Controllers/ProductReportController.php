<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DailyProduction;
use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ProductReportController extends Controller
{
    public function monthly(Request $request)
    {
        $products = Product::orderBy('name')->get();

        // defaults
        $month = $request->month ?? date('Y-m');
        $productId = $request->product_id ?? ($products->first()->id ?? null);

        if (!$productId) {
            return view('reports.product-monthly', [
                'products' => $products,
                'report' => null,
                'month' => $month,
                'productId' => null
            ]);
        }

        [$year, $mon] = explode('-', $month);

        // Calculate
        $totalProduced = DailyProduction::where('product_id', $productId)
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->sum('production_qty');

        $totalSold = Sale::where('product_id', $productId)
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->sum('quantity');

        $inventory = Inventory::where('product_id', $productId)->first();

        $opening = $inventory->opening_stock ?? 0;
        $closing = $opening + $totalProduced - $totalSold;

        // daily production chart
        $dailyProduction = DailyProduction::where('product_id', $productId)
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->groupBy('date')
            ->selectRaw('date, SUM(production_qty) as qty')
            ->orderBy('date')
            ->get();

        // daily sales chart
        $dailySales = Sale::where('product_id', $productId)
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->groupBy('date')
            ->selectRaw('date, SUM(quantity) as qty')
            ->orderBy('date')
            ->get();

        $report = [
            'totalProduced' => $totalProduced,
            'totalSold' => $totalSold,
            'openingStock' => $opening,
            'closingStock' => $closing,
            'dailyProduction' => $dailyProduction,
            'dailySales' => $dailySales
        ];

        return view('reports.product-monthly', compact(
            'products',
            'report',
            'month',
            'productId'
        ));
    }

    // public function stockLedger(Request $request)
    // {
    //     $products = Product::orderBy('name')->get();
    
    //     // Default month and product
    //     $month = $request->month ?? date('Y-m');
    //     $productId = $request->product_id ?? ($products->first()->id ?? null);
    
    //     if (!$productId) {
    //         return view('reports.stock-ledger', [
    //             'products' => $products,
    //             'ledger' => [],
    //             'month' => $month,
    //             'productId' => null
    //         ]);
    //     }
    
    //     [$year, $mon] = explode('-', $month);
    //     $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $mon, $year);
    
    //     $inventory = Inventory::where('product_id', $productId)->first();
    //     $opening = $inventory->opening_stock ?? 0;
    
    //     $ledger = [];
    //     $currentStock = $opening;
    
    //     for ($day = 1; $day <= $daysInMonth; $day++) {
    
    //         $date = sprintf("%04d-%02d-%02d", $year, $mon, $day);
    
    //         $production = DailyProduction::where('product_id', $productId)
    //             ->whereDate('date', $date)
    //             ->sum('production_qty');
    
    //         $sales = Sale::where('product_id', $productId)
    //             ->whereDate('date', $date)
    //             ->sum('quantity');
    
    //         $closing = $currentStock + $production - $sales;
    
    //         $ledger[] = [
    //             'date' => $date,
    //             'opening' => $currentStock,
    //             'production' => $production,
    //             'sales' => $sales,
    //             'closing' => $closing
    //         ];
    
    //         $currentStock = $closing;
    //     }
    
    //     return view('reports.stock-ledger', compact(
    //         'products',
    //         'ledger',
    //         'month',
    //         'productId'
    //     ));
    // }
    public function stockLedger(Request $request)
    {
        $products = Product::orderBy('name')->get();
    
        $month = $request->month ?? date('Y-m');
        $productId = $request->product_id ?? ($products->first()->id ?? null);
    
        if (!$productId) {
            return view('reports.stock-ledger', [
                'products' => $products,
                'ledger' => [],
                'month' => $month,
                'productId' => null
            ]);
        }
    
        [$year, $mon] = explode('-', $month);
    
        // 👇 FIX: Only show up to today if it's current month
        if ($month == date('Y-m')) {
            $daysInMonth = (int) date('d'); // today’s date number
        } elseif ($month < date('Y-m')) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $mon, $year); // full month
        } else {
            $daysInMonth = 0; // no future data
        }
    
        $inventory = Inventory::where('product_id', $productId)->first();
        $opening = $inventory->opening_stock ?? 0;
        $currentStock = $opening;
    
        $ledger = [];
    
        for ($day = 1; $day <= $daysInMonth; $day++) {
    
            $date = sprintf("%04d-%02d-%02d", $year, $mon, $day);
    
            $production = DailyProduction::where('product_id', $productId)
                ->whereDate('date', $date)
                ->sum('production_qty');
    
            $sales = Sale::where('product_id', $productId)
                ->whereDate('date', $date)
                ->sum('quantity');
    
            $closing = $currentStock + $production - $sales;
    
            $ledger[] = [
                'date' => $date,
                'opening' => $currentStock,
                'production' => $production,
                'sales' => $sales,
                'closing' => $closing
            ];
    
            $currentStock = $closing;
        }
    
        return view('reports.stock-ledger', compact(
            'products',
            'ledger',
            'month',
            'productId'
        ));
    }
    
    
}
