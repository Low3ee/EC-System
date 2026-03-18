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

    @if ($invoice->status != 'paid' && ($invoice->total_amount - $invoice->amount_paid) > 0)
    <div class="mt-8 border-t pt-8">
        <h3 class="font-bold text-gray-800 mb-4 text-xl">Record a Payment</h3>
        <form action="{{ route('payments.store', $invoice) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Amount --}}
                <div class="md:col-span-2">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <div class="mt-1">
                        <input type="number" name="amount" id="amount" step="0.01" max="{{ $invoice->total_amount - $invoice->amount_paid }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-light focus:ring-brand-light sm:text-sm" placeholder="0.00">
                    </div>
                     @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                     <label for="type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                    <select id="type" name="type" required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-brand-light focus:outline-none focus:ring-brand-light sm:text-sm">
                        <option value="rent">Rent</option>
                        <option value="utility">Utility</option>
                    </select>
                </div>

                {{-- Payment Method --}}
                <div>
                     <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-brand-light focus:outline-none focus:ring-brand-light sm:text-sm">
                        <option value="cash" selected>Cash</option>
                        <option value="gcash">Gcash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-light focus:ring-brand-light sm:text-sm"></textarea>
                    </div>
                </div>

                {{-- Transaction Reference --}}
                <div class="md:col-span-2">
                    <label for="transaction_reference" class="block text-sm font-medium text-gray-700">Transaction Reference (Optional)</label>
                    <div class="mt-1">
                        <input type="text" name="transaction_reference" id="transaction_reference" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-light focus:ring-brand-light sm:text-sm" placeholder="e.g. Gcash Reference No.">
                    </div>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button type="submit" class="bg-brand hover:bg-brand-dark text-white font-bold py-2 px-6 rounded-lg transition">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
    @endif
    <div class="text-center mt-8 flex justify-center space-x-4">
        <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600">
            Print Receipt
        </a>
        <a href="{{ route('invoices.email', $invoice) }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">
            Email Receipt
        </a>
    </div>
</div>
@endsection

