@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Invoice #{{ $invoice->id }}</h1>
            <p class="text-gray-500">Issued: {{ $invoice->created_at->format('M d, Y') }}</p>
            <p class="text-gray-500">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
        </div>
        <div>
            <span class="px-3 py-1 text-sm font-bold rounded-full
                @if($invoice->status == 'paid') bg-green-100 text-green-800
                @elseif($invoice->status == 'partial') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="font-bold text-gray-800 mb-2">Billed To:</h3>
            <p class="text-gray-700">{{ $invoice->tenant->name }}</p>
            <p class="text-gray-700">{{ $invoice->tenant->email }}</p>
            <p class="text-gray-700">{{ $invoice->tenant->phone }}</p>
        </div>
        <div>
            <h3 class="font-bold text-gray-800 mb-2">For Room:</h3>
            <p class="text-gray-700">Room #{{ $invoice->room->room_number ?? 'N/A' }}</p>
            <p class="text-gray-700">{{ $invoice->room->type ?? 'N/A'}}</p>
        </div>
    </div>

    <h3 class="font-bold text-gray-800 mb-2">Invoice Items:</h3>
    <div class="overflow-x-auto mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-500 text-sm border-b-2 border-gray-200">
                    <th class="px-4 py-3 font-medium">Description</th>
                    <th class="px-4 py-3 font-medium text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($invoice->items as $item)
                    <tr class="text-gray-700 border-b border-gray-100">
                        <td class="px-4 py-3">{{ $item->description }}</td>
                        <td class="px-4 py-3 text-right">₱{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-gray-800 font-bold">
                    <td class="px-4 py-3 text-right">Total Amount Due</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                 <tr class="text-gray-600">
                    <td class="px-4 py-3 text-right">Amount Paid</td>
                    <td class="px-4 py-3 text-right text-green-600">- ₱{{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                 <tr class="text-gray-900 font-extrabold text-lg">
                    <td class="px-4 py-3 text-right">Balance Due</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($invoice->total_amount - $invoice->amount_paid, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="text-center mt-8">
        <a href="{{ route('invoices.index') }}" class="text-sm text-gray-500 hover:text-brand">← Back to All Invoices</a>
    </div>

    {{-- Payment Recording Section --}}
    <div class="mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Payment History</h3>

        @if($invoice->payments->isEmpty())
            <p class="text-gray-500 text-sm">No payments have been recorded for this invoice yet.</p>
        @else
            <div class="overflow-x-auto mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 text-sm border-b-2 border-gray-200">
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium">Type</th>
                            <th class="px-4 py-3 font-medium">Description</th>
                            <th class="px-4 py-3 font-medium text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($invoice->payments as $payment)
                            <tr class="text-gray-700 border-b border-gray-100">
                                <td class="px-4 py-3">{{ $payment->paid_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ ucfirst($payment->type) }}</td>
                                <td class="px-4 py-3">{{ $payment->description }}</td>
                                <td class="px-4 py-3 text-right">₱{{ number_format($payment->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($invoice->status != 'paid')
            <div class="mt-8 p-6 bg-gray-50 rounded-lg border">
                <h4 class="font-bold text-gray-700 mb-4">Record a New Payment</h4>
                <form action="{{ route('payments.store', $invoice) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" step="0.01" required
                                class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand focus:border-brand sm:text-sm"
                                placeholder="0.00">
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand focus:border-brand sm:text-sm">
                                <option value="rent">Rent</option>
                                <option value="utility">Utility</option>
                            </select>
                             @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                             <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                             <input type="text" name="description" id="description"
                                class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand focus:border-brand sm:text-sm"
                                placeholder="e.g., February rent">
                             @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 text-center">
                        <button type="submit"
                            class="bg-brand-600 border border-black text-black font-bold py-2 px-4 rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection