@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Invoices & Billing</h1>
        
        <form action="{{ route('invoices.generate') }}" method="POST">
            @csrf
            <button type="submit" class="bg-brand text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Generate Monthly Rent
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs uppercase text-gray-400 bg-gray-50 border-b">
                    <th class="px-6 py-4">Invoice #</th>
                    <th class="px-6 py-4">Tenant</th>
                    <th class="px-6 py-4">Due Date</th>
                    <th class="px-6 py-4">Amount Due</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoices as $invoice)
                <tr>
                    <td class="px-6 py-4 font-mono text-sm text-gray-600">INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $invoice->tenant->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-700">₱{{ number_format($invoice->amount_due, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($invoice->status == 'paid')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase text-[10px]">Paid</span>
                        @elseif($invoice->status == 'partial')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase text-[10px]">Partial</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase text-[10px]">Unpaid</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
<button onclick="openPaymentModal({{ $invoice->id }}, '{{ $invoice->tenant->name }}', {{ $invoice->amount_due - $invoice->amount_paid }})" 
        class="text-brand hover:underline font-medium text-sm">
    Record Payment
</button>                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Record Payment</h3>
        <p class="text-sm text-gray-500 mb-6">Tenant: <span id="modalTenantName" class="font-bold text-gray-800"></span></p>
        
        <form id="paymentForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount Paid (₱)</label>
                    <input type="number" name="amount" id="modalAmount" required class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                   <select name="payment_method" required class="w-full rounded-lg border-gray-300">
    <option value="cash">Cash</option>
    <option value="venmo">Venmo</option>
    <option value="bank_transfer">Bank Transfer</option>
    <option value="stripe">Stripe</option>
</select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reference (Optional)</label>
                    <input type="text" name="transaction_reference" placeholder="e.g. GCash Ref #" class="w-full rounded-lg border-gray-300">
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700">Confirm Payment</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaymentModal(invoiceId, tenantName, balance) {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
        document.getElementById('modalTenantName').innerText = tenantName;
        document.getElementById('modalAmount').value = balance;
        // Update form action URL dynamically
        document.getElementById('paymentForm').action = `/invoices/${invoiceId}/payments`;
    }

    function closeModal() {
        document.getElementById('paymentModal').classList.remove('flex');
        document.getElementById('paymentModal').classList.add('hidden');
    }
</script>
@endsection