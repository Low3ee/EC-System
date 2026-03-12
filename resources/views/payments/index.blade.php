@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Transaction History</h1>
        <p class="text-sm text-gray-500">A record of all rent collected.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs uppercase text-gray-400 bg-gray-50 border-b">
                    <th class="px-6 py-4">Ref #</th>
                    <th class="px-6 py-4">Tenant</th>
                    <th class="px-6 py-4">Method</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-mono text-gray-500">
                        {{ $payment->transaction_reference ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $payment->invoice->tenant->name }}</div>
                        <div class="text-[10px] text-gray-400">INV-{{ $payment->invoice_id }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold uppercase">
                            {{ str_replace('_', ' ', $payment->payment_method) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $payment->paid_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 font-black text-green-600">
                        +₱{{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        No transactions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection