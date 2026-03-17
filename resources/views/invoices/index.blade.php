@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Invoices & Billing</h1>
        <form action="{{ route('invoices.generate') }}" method="POST">
            @csrf
            <button type="submit" class="bg-brand text-black font-medium py-2 px-4 border rounded-lg hover:bg-brand-dark transition">
                Generate Monthly Invoices
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-sm border-b border-gray-100">
                        <th class="px-6 py-4 font-medium">Invoice ID</th>
                        <th class="px-6 py-4 font-medium">Tenant</th>
                        <th class="px-6 py-4 font-medium">Room</th>
                        <th class="px-6 py-4 font-medium">Total Amount</th>
                        <th class="px-6 py-4 font-medium">Due Date</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 text-gray-700 border-b border-gray-50 last:border-0">
                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $invoice->id }}</td>
                            <td class="px-6 py-4">{{ $invoice->tenant?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">#{{ $invoice->room?->room_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4">₱{{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                            <td class="px-6 py-4">{{ $invoice->due_date?->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    @if($invoice->status == 'paid') bg-green-100 text-green-800
                                    @elseif($invoice->status == 'partial') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-brand hover:underline">View Details</a>
                                <a href="{{ route('invoices.email', $invoice) }}" class="text-brand hover:underline">Email Invoice</a>
                                <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="text-brand hover:underline">Print Invoice</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-8 text-gray-500">No invoices found. Try generating monthly invoices.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-100">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection