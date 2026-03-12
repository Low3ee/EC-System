<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('tenant')->latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    public function generateMonthlyInvoices()
    {
        $tenants = Tenant::where('is_active', true)->get();
        $count = 0;
        $month = Carbon::now()->format('F Y');

        foreach ($tenants as $tenant) {
            // Check if tenant already has a 'rent' invoice for this month
            $exists = Invoice::where('tenant_id', $tenant->id)
                ->where('type', 'rent')
                ->whereMonth('due_date', Carbon::now()->month)
                ->whereYear('due_date', Carbon::now()->year)
                ->exists();

            if (!$exists) {
                Invoice::create([
                    'tenant_id' => $tenant->id,
                    'amount_due' => $tenant->base_rent,
                    'amount_paid' => 0,
                    'due_date' => Carbon::now()->day($tenant->due_day),
                    'type' => 'rent',
                    'status' => 'unpaid',
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "$count invoices generated for $month!");
    }
}