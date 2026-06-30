<?php

namespace App\Http\Controllers;

use App\Models\Order;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index', [
            'orders' => Order::with(['items', 'area'])->where('user_id', auth()->id())->latest()->paginate(10),
            'addresses' => auth()->user()->addresses()->with('area')->latest()->get(),
        ]);
    }
}
