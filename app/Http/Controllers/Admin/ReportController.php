<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function orders(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $query = $this->filteredOrders($request)->with(['items', 'area', 'user']);

        return view('admin.reports.orders', [
            'orders' => (clone $query)->latest()->paginate(20)->withQueryString(),
            'summary' => $this->orderSummary(clone $query),
            'filters' => $this->filters($request),
        ]);
    }

    public function ordersPdf(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $query = $this->filteredOrders($request)->with(['items', 'area', 'user']);

        return view('admin.reports.orders-pdf', [
            'orders' => (clone $query)->latest()->get(),
            'summary' => $this->orderSummary(clone $query),
            'filters' => $this->filters($request),
        ]);
    }

    public function finance(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $query = $this->filteredOrders($request)
            ->whereNotIn('status', ['waiting_payment', 'cancelled']);

        return view('admin.reports.finance', [
            'orders' => (clone $query)->with(['user', 'area'])->latest()->paginate(20)->withQueryString(),
            'summary' => $this->financeSummary(clone $query),
            'dailySales' => $this->dailySales(clone $query),
            'filters' => $this->filters($request),
        ]);
    }

    public function financePdf(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $query = $this->filteredOrders($request)
            ->whereNotIn('status', ['waiting_payment', 'cancelled']);

        return view('admin.reports.finance-pdf', [
            'orders' => (clone $query)->with(['user', 'area'])->latest()->get(),
            'summary' => $this->financeSummary(clone $query),
            'dailySales' => $this->dailySales(clone $query),
            'filters' => $this->filters($request),
        ]);
    }

    private function filteredOrders(Request $request): Builder
    {
        $filters = $this->filters($request);

        return Order::query()
            ->when($filters['from'], fn (Builder $query, string $date) => $query->whereDate('orders.created_at', '>=', $date))
            ->when($filters['to'], fn (Builder $query, string $date) => $query->whereDate('orders.created_at', '<=', $date))
            ->when($filters['status'], fn (Builder $query, string $status) => $query->where('status', $status));
    }

    private function filters(Request $request): array
    {
        $data = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'status' => ['nullable', 'in:waiting_payment,paid,processing,cooking,ready_to_ship,on_delivery,delivered,cancelled'],
        ]);

        return [
            'from' => $data['from'] ?? Carbon::now()->startOfMonth()->toDateString(),
            'to' => $data['to'] ?? Carbon::now()->toDateString(),
            'status' => $data['status'] ?? null,
        ];
    }

    private function orderSummary(Builder $query): array
    {
        return [
            'total_orders' => (clone $query)->count(),
            'total_pax' => (clone $query)->join('order_items', 'orders.id', '=', 'order_items.order_id')->sum('order_items.quantity'),
            'total_amount' => (clone $query)->sum('grand_total'),
            'by_status' => (clone $query)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
        ];
    }

    private function financeSummary(Builder $query): array
    {
        $pointsDiscount = (clone $query)->get(['points_redeemed'])->sum(fn (Order $order): int => $order->pointsDiscount());

        return [
            'gross_sales' => (clone $query)->sum('subtotal'),
            'delivery_fees' => (clone $query)->sum('delivery_fee'),
            'discounts' => (clone $query)->sum('discount'),
            'points_discount' => $pointsDiscount,
            'net_sales' => (clone $query)->sum('grand_total'),
            'paid_orders' => (clone $query)->count(),
        ];
    }

    private function dailySales(Builder $query)
    {
        return (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders_count, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
