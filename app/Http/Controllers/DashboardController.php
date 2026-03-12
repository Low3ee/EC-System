<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use App\Models\Invoice;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
{
    $totalRevenue = Payment::sum('amount');

    $unpaidInvoices = Invoice::where('status', '!=', 'paid');
    $pendingAmount = $unpaidInvoices->sum('amount_due') - $unpaidInvoices->sum('amount_paid');

    $rooms = Room::with(['tenants' => function ($query) {
        $query->where('status', 'Active');
    }])->get();

    $bedSpacer = Room::where('capacity', '>', 1)->get();
    
    // Calculate bedspacer stats
    $totalCapacity = $rooms->sum('capacity');
    $population = $rooms->sum(fn($room) => $room->tenants->count());
    $availableBeds = $bedSpacer->sum('capacity') - $bedSpacer->sum(fn($bedSpacer) => $bedSpacer->tenants->count());
    $occupiedBeds = $rooms->sum(fn($room) => $room->tenants->count());

    $stats = [
        'total_revenue'  => $totalRevenue,
        'pending_amount' => max(0, $pendingAmount),
        'occupied_rooms' => $rooms->filter(fn($r) => $r->tenants->count() > 0)->count(),
        'vacant_rooms'   => $rooms->filter(fn($r) => $r->tenants->count() === 0)->count(),
        'occupied_beds'  => $occupiedBeds,
        'available_beds' => $availableBeds,
        'total_capacity' => $totalCapacity,
        'population'     => $population,

    ];

    $recentPayments = Payment::with('invoice.tenant')->latest()->take(5)->get();

    return view('dashboard', compact('stats', 'rooms', 'recentPayments'));
}
}