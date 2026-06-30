<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\OperatingHour;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = Category::where('is_active', true)->withCount('menuItems')->get();
        $popularMenus = MenuItem::with('category')->available()->where('is_popular', true)->limit(6)->get();
        $flashSales = MenuItem::with('category')->available()->whereNotNull('flash_sale_price')->limit(3)->get();
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $cutoff = OperatingHour::where('day_of_week', strtolower(now()->englishDayOfWeek))->first();
        $cutoffAt = Carbon::today()->setTimeFromTimeString($cutoff?->cutoff_at ?? '20:00:00');

        if (now()->greaterThan($cutoffAt)) {
            $cutoffAt->addDay();
        }

        return view('home', compact('categories', 'popularMenus', 'flashSales', 'plans', 'cutoffAt'));
    }
}
