<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $totalSharedCapacity = Room::where('capacity', '>', 1)->sum('capacity');
        $occupiedSharedBeds = Tenant::where('status', 'Active')
            ->whereHas('room', function($query) {
                $query->where('capacity', '>', 1);
            })->count();

        $stats = [
            'total_tenants' => Tenant::count(),
            'total_revenue' => Invoice::sum('amount_paid'),
            'pending_amount' => Invoice::sum(DB::raw('total_amount - amount_paid')),
            'total_capacity' => Room::sum('capacity'),
            'vacant_rooms' => Room::where('is_available', true)->count(),
            'available_beds' => $totalSharedCapacity - $occupiedSharedBeds,
            'population' => Tenant::where('status', 'Active')->count(),
            'rooms' => Room::all(),
            'occupied_rooms' => Tenant::where('status', 'Active')->distinct('room_id')->count(),
            'available_rooms' => Room::where('is_available', true)->where('capacity', '=', 1)->count(),
            'outstanding_balance' => Invoice::where('status', '!=', 'paid')->sum(DB::raw('total_amount - amount_paid')),
            'total_collected' => Invoice::sum('amount_paid'),
            
        ];

        $recentPayments = Payment::with('invoice.tenant')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentPayments'));
    }
}
