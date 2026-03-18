<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;

class InvoiceController extends Controller
{
    /**
     * Generate monthly invoices for all active tenants.
     */
    public function generateMonthlyInvoices(Request $request)
    {
        // Get all active tenants with their room and room utilities loaded.
        $activeTenants = Tenant::with('room.utilities')->where('status', 'Active')->whereNotNull('room_id')->get();

        $dueDate = Carbon::now()->addDays(15);
        $generatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($activeTenants as $tenant) {
                $room = $tenant->room;
                if (!$room) {
                    continue;
                }

                // A tenant should only have one monthly bill.
                $existingInvoice = Invoice::where('tenant_id', $tenant->id)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->exists();

                if ($existingInvoice) {
                    continue;
                }

                $invoiceItems = [];
                $totalAmount = 0;

                // Add Rent
                $invoiceItems[] = [
                    'description' => 'Monthly Rent',
                    'amount' => $tenant->base_rent,
                ];
                $totalAmount += $tenant->base_rent;

                // Add Utilities (pro-rated if multiple tenants are in the room)
                $activeTenantsInRoom = $room->tenants()->where('status', 'Active')->count();
                $numberOfTenants = $activeTenantsInRoom > 0 ? $activeTenantsInRoom : 1;

                foreach ($room->utilities as $utility) {
                    $description = $utility->pivot->description ?: $utility->name;
                    $amountPerTenant = $utility->pivot->amount / $numberOfTenants;

                    $invoiceItems[] = [
                        'description' => $description . ($numberOfTenants > 1 ? ' (pro-rated)' : ''),
                        'amount' => $amountPerTenant,
                    ];
                    $totalAmount += $amountPerTenant;
                }

                if ($totalAmount <= 0) {
                    continue;
                }

                // Create the main invoice record
                $invoice = Invoice::create([
                    'room_id' => $room->id,
                    'tenant_id' => $tenant->id,
                    'total_amount' => $totalAmount,
                    'due_date' => $dueDate,
                    'status' => 'unpaid',
                ]);

                // Create all invoice items
                $invoice->items()->createMany($invoiceItems);
                $generatedCount++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred during invoice generation. Error: ' . $e->getMessage());
        }

        if ($generatedCount > 0) {
            return back()->with('success', "$generatedCount monthly invoices have been generated successfully.");
        } else {
            return back()->with('info', 'No new invoices needed to be generated. All active tenants are up to date for this month.');
        }
    }

    public function index()
    {
        $invoices = Invoice::with('tenant', 'room')->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items', 'tenant', 'room');
        return view('invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('items', 'tenant', 'room');
        return view('invoices.receipt', compact('invoice'));
    }

    public function email(Invoice $invoice)
    {
        $invoice->load('items', 'tenant', 'room');

        if (!$invoice->tenant || !$invoice->tenant->email) {
            return back()->with('error', 'Tenant has no email address configured.');
        }

        try {
            Mail::send([], [], function ($message) use ($invoice) {
        $message->to($invoice->tenant->email)
                ->subject("Invoice #{$invoice->id}")
                // This renders your existing blade view into the email body
                ->html(view('invoices.email-receipt', compact('invoice'))->render());
    });

            return back()->with('success', "Invoice #{$invoice->id} has been emailed to {$invoice->tenant->email}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}