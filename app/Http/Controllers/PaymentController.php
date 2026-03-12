<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Store a new payment for a specific invoice.
     */
    public function store(Request $request, Invoice $invoice)
    {
        // 1. Validation: Ensure the math makes sense
        $validated = $request->validate([
            'amount' => [
                'required', 
                'numeric', 
                'min:0.01', 
                'max:' . ($invoice->amount_due - $invoice->amount_paid) 
            ],
            'payment_method' => 'required|string|in:cash,venmo,bank_transfer,stripe',
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($invoice, $validated) {
                
                $invoice->payments()->create([
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'transaction_reference' => $validated['transaction_reference'] ?? null,
                    'paid_at' => now(),
                ]);

                $invoice->increment('amount_paid', $validated['amount']);
                // Refresh the model to get the updated amount_paid from the database
                $invoice->refresh();

                if (($invoice->amount_due - $invoice->amount_paid) <= 0.01) {
                    $invoice->update(['status' => 'paid']);
                } else {
                    $invoice->update(['status' => 'partial']);
                }
            });

            return redirect()->back()->with('success', 'Payment recorded successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Show a history of all payments 
     */
    public function index()
    {
        $payments = Payment::with('invoice.tenant')->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }
}