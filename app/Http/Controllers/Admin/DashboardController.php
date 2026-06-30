<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.dashboard', [
            'salesToday' => Order::whereDate('created_at', today())->sum('grand_total'),
            'ordersToday' => Order::whereDate('created_at', today())->count(),
            'pendingOrders' => Order::whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'bestMenus' => MenuItem::select('menu_items.name', DB::raw('COALESCE(SUM(order_items.quantity), 0) as sold'))
                ->leftJoin('order_items', 'menu_items.id', '=', 'order_items.menu_item_id')
                ->groupBy('menu_items.id', 'menu_items.name')
                ->orderByDesc('sold')
                ->limit(5)
                ->get(),
            'recentOrders' => Order::with('area')->latest()->limit(8)->get(),
            'chart' => Order::selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ]);
    }
}
