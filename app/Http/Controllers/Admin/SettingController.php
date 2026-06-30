<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryArea;
use App\Models\DeliverySlot;
use App\Models\OperatingHour;
use App\Models\Voucher;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.settings.index', [
            'areas' => DeliveryArea::orderBy('district')->get(),
            'vouchers' => Voucher::latest()->get(),
            'slots' => DeliverySlot::orderBy('day_of_week')->orderBy('starts_at')->get(),
            'hours' => OperatingHour::orderBy('id')->get(),
        ]);
    }

    public function storeArea(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        DeliveryArea::updateOrCreate(
            $request->validate(['district' => ['required'], 'village' => ['required']]),
            $request->validate(['delivery_fee' => ['required', 'integer', 'min:0'], 'eta_minutes' => ['required', 'integer', 'min:1']]) + ['is_active' => $request->boolean('is_active', true)]
        );

        return back()->with('success', 'Area pengiriman disimpan.');
    }

    public function storeVoucher(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $data = $request->validate([
            'code' => ['required', 'max:40'],
            'name' => ['required', 'max:120'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1'],
            'minimum_spend' => ['required', 'integer', 'min:0'],
            'max_discount' => ['nullable', 'integer', 'min:0'],
            'expires_at' => ['nullable', 'date'],
        ]);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        Voucher::updateOrCreate(['code' => $data['code']], $data);

        return back()->with('success', 'Voucher disimpan.');
    }

    public function storeSlot(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $data = $request->validate([
            'day_of_week' => ['required'],
            'starts_at' => ['required', 'date_format:H:i'],
            'ends_at' => ['required', 'date_format:H:i'],
            'max_orders' => ['required', 'integer', 'min:1'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        DeliverySlot::updateOrCreate(['day_of_week' => $data['day_of_week'], 'starts_at' => $data['starts_at']], $data);

        return back()->with('success', 'Slot pengiriman disimpan.');
    }

    public function storeHour(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $data = $request->validate([
            'day_of_week' => ['required'],
            'opens_at' => ['nullable', 'date_format:H:i'],
            'closes_at' => ['nullable', 'date_format:H:i'],
            'cutoff_at' => ['required', 'date_format:H:i'],
        ]);
        $data['is_closed'] = $request->boolean('is_closed');
        OperatingHour::updateOrCreate(['day_of_week' => $data['day_of_week']], $data);

        return back()->with('success', 'Jam operasional disimpan.');
    }
}
