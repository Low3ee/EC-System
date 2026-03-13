@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tenants.index') }}" class="text-sm text-gray-500 hover:text-brand">← Back to Directory</a>
    </div>

    {{-- Tenant Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $tenant->name }}</h1>
                <p class="text-gray-500">{{ $tenant->email }} | {{ $tenant->phone }}</p>
                <p class="text-sm text-gray-600 mt-1">
                    Room: <strong>{{ $tenant->room->room_number ?? 'N/A' }}</strong> |
                    Lease Start: <strong>{{ $tenant->lease_start->format('M d, Y') }}</strong> |
                    Status: <span class="font-bold {{ $tenant->status == 'Active' ? 'text-green-600' : 'text-gray-500' }}">{{ $tenant->status }}</span>
                    @if($tenant->status == 'Moved Out' && $tenant->move_out_date)
                        | Moved Out: <strong>{{ $tenant->move_out_date->format('M d, Y') }}</strong>
                    @endif
                </p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-gray-500">Outstanding Balance</p>
                <p class="text-4xl font-extrabold {{ $tenant->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                    ₱{{ number_format($tenant->balance, 2) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Financial History --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Financial History</h2>
    <div class="space-y-6">
        @forelse($tenant->invoices as $invoice)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold">Invoice #{{ $invoice->id }}</h3>
                        <p class="text-sm text-gray-500">Issued: {{ $invoice->created_at->format('M d, Y') }} | Due: {{ $invoice->due_date->format('M d, Y') }}</p>
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
                <div class="p-6">
                    <h4 class="font-semibold text-gray-700 mb-2">Invoice Items</h4>
                    <table class="w-full text-sm mb-4">
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-right">₱{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="font-bold border-t">
                            <td class="text-right">Total:</td>
                            <td class="text-right">₱{{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                    </table>

                    <h4 class="font-semibold text-gray-700 mb-2">Payments</h4>
                     @if($invoice->payments->isEmpty())
                        <p class="text-xs text-gray-500">No payments for this invoice.</p>
                    @else
                        <table class="w-full text-sm">
                            @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->paid_at->format('M d, Y') }} - {{ ucfirst($payment->type) }}</td>
                                <td class="text-right">- ₱{{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold border-t">
                                <td class="text-right">Total Paid:</td>
                                <td class="text-right">- ₱{{ number_format($invoice->amount_paid, 2) }}</td>
                            </tr>
                        </table>
                    @endif
                </div>
                <div class="bg-gray-50 p-4 border-t text-right">
                    <span class="font-bold">Balance for this invoice: ₱{{ number_format($invoice->amountDue, 2) }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl shadow-sm border">
                <p class="text-gray-500">No invoices found for this tenant.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
