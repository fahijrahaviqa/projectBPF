<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total menu
        $totalMenu = MenuItem::count();

        // Hitung pesanan hari ini
        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();

        // Hitung reservasi hari ini
        $reservationsToday = Reservation::whereDate('reservation_date', Carbon::today())->count();

        // Hitung total pendapatan (dalam 30 hari terakhir)
        $totalRevenue = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('total_amount');

        // Data untuk line chart (7 hari terakhir)
        $chartData = $this->getChartData();

        // Ambil pesanan terbaru (5 terakhir)
        $recentOrders = Order::with(['user', 'orderItems.menuItem'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->user->name,
                    'menu_items' => $order->orderItems->map(function ($item) {
                        return $item->menuItem->name . ' (' . $item->quantity . 'x)';
                    })->join(', '),
                    'total' => $order->total_amount,
                    'status' => $order->status,
                ];
            });

        return view('home', compact(
            'totalMenu',
            'ordersToday',
            'reservationsToday',
            'totalRevenue',
            'recentOrders',
            'chartData'
        ));
    }

    private function getChartData()
    {
        // Ambil data 7 hari terakhir
        $dates = collect();
        $orderCounts = collect();
        $revenues = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates->push($date->format('d M'));

            // Hitung jumlah pesanan per hari
            $orderCount = Order::whereDate('created_at', $date)->count();
            $orderCounts->push($orderCount);

            // Hitung pendapatan per hari
            $revenue = Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $revenues->push($revenue);
        }

        // Hitung distribusi status pesanan
        $statusCounts = [
            Order::where('status', 'pending')->count(),
            Order::where('status', 'processing')->count(),
            Order::where('status', 'completed')->count(),
            Order::where('status', 'cancelled')->count(),
        ];

        return [
            'labels' => $dates->all(),
            'orderCounts' => $orderCounts->all(),
            'revenues' => $revenues->all(),
            'statusCounts' => $statusCounts,
        ];
    }
} 