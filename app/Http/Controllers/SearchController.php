<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    /**
     * Perform a global search across Tenants, Rooms, Invoices, and Payments.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => 'nullable|string|min:2|max:50',
        ]);

        $query = $validated['q'] ?? null;

        if (!$query) {
            return response()->json([]);
        }

        $tenants = $this->searchTenants($query);
        $rooms = $this->searchRooms($query);
        $invoices = $this->searchInvoices($query);
        $payments = $this->searchPayments($query);

        $results = $tenants->concat($rooms)->concat($invoices)->concat($payments);

        return response()->json($results->values()->take(10));
    }

    /**
     * Search for tenants by name, email, or phone.
     */
    private function searchTenants(string $query): Collection
    {
        return Tenant::with('room')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->limit(5)->get()
            ->map(function (Tenant $tenant) {
                $description = $tenant->status === 'Moved Out' ? 'Tenant (Moved Out)' : 'Active Tenant';
                if ($tenant->room) {
                    $description .= ' in Room ' . $tenant->room->room_number;
                }
                return [
                    'title' => $tenant->name,
                    'description' => $description,
                    'type' => 'Tenant',
                    'url' => route('tenants.show', $tenant),
                ];
            });
    }

    /**
     * Search for rooms by room number or type.
     */
    private function searchRooms(string $query): Collection
    {
        return Room::withCount('tenants')
            ->where('room_number', 'LIKE', "%{$query}%")
            ->orWhere('type', 'LIKE', "%{$query}%")
            ->limit(5)->get()
            ->map(function (Room $room) {
                return [
                    'title' => 'Room ' . $room->room_number,
                    'description' => "Type: {$room->type}, {$room->tenants_count} occupant(s)",
                    'type' => 'Room',
                    'url' => route('rooms.show', $room),
                ];
            });
    }

    /**
     * Search for invoices by ID or associated tenant name.
     */
    private function searchInvoices(string $query): Collection
    {
        return Invoice::with('tenant')
            ->where(function ($q) use ($query) {
                if (ctype_digit($query)) {
                    $q->where('id', '=', $query);
                }
                $q->orWhereHas('tenant', fn ($tq) => $tq->where('name', 'LIKE', "%{$query}%"));
            })
            ->latest()->limit(5)->get()
            ->map(function (Invoice $invoice) {
                if (!$invoice->tenant) return null;
                return [
                    'title' => "Invoice #{$invoice->id}",
                    'description' => "For {$invoice->tenant->name} - Status: " . ucfirst($invoice->status),
                    'type' => 'Invoice',
                    'url' => route('invoices.show', $invoice),
                ];
            })->filter();
    }

    /**
     * Search for payments by ID or transaction reference.
     */
    private function searchPayments(string $query): Collection
    {
        return Payment::with('invoice.tenant')
            ->where('transaction_reference', 'LIKE', "%{$query}%")
            ->orWhere('id', 'LIKE', "%{$query}%")
            ->latest()->limit(5)->get()
            ->map(function (Payment $payment) {
                return [
                    'title' => "Payment #{$payment->id}",
                    'description' => "Amount: $" . number_format($payment->amount, 2) . ($payment->transaction_reference ? " (Ref: {$payment->transaction_reference})" : ""),
                    'type' => 'Payment',
                    'url' => $payment->invoice ? route('invoices.show', $payment->invoice) : '#',
                ];
            });
    }
}