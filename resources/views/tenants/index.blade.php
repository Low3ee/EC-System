@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tenant Directory</h1>
        <a href="{{ route('tenants.create') }}" class="bg-brand text-black px-4 py-2 rounded-lg shadow hover:bg-blue-700 hover:text-white transition">
            + Add New Tenant
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs uppercase text-gray-400 bg-gray-50 border-b">
                    <th class="px-6 py-4">Tenant Info</th>
                    <th class="px-6 py-4">Room</th>
                    <th class="px-6 py-4">Base Rent</th>
                    <th class="px-6 py-4">Balance</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($tenants as $tenant)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $tenant->name }}</div>
                        <div class="text-xs text-gray-500">{{ $tenant->email }} | {{ $tenant->phone }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">
                            Room {{ $tenant->room->room_number ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        ₱{{ number_format($tenant->base_rent, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        @php $balance = $tenant->balance; @endphp
                        <span class="text-sm font-bold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                            ₱{{ number_format($balance, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($tenant->status == 'Active')
                            <span class="flex items-center text-xs text-green-600 font-bold uppercase">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Active
                            </span>
                        @else
                            <span class="flex items-center text-xs text-gray-400 font-bold uppercase">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span> Moved Out
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('tenants.show', $tenant->id) }}" class="text-green-600 hover:text-green-900 text-sm font-medium">View</a>
                        <a href="{{ route('tenants.edit', $tenant->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="inline" onsubmit="return confirmMoveOut(this);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" data-tenant-name="{{ $tenant->name }}" data-tenant-balance="{{ $tenant->balance }}">Move Out</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden m-20 border border-gray-100">
    <h2 class="text-2xl p-5 text-center font-bold text-gray-800">Old Tenants</h2>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="text-xs uppercase text-gray-400 bg-gray-50 border-b">
                <th class="px-6 py-4 font-semibold">Tenant Info</th>
                <th class="px-6 py-4 font-semibold">Balance</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold">Move Out Date</th>
                <th class="px-6 py-4 text-right font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($movedOutTenants as $tenant)
            <tr class="hover:bg-gray-50 transition">
                {{-- Column 1: Tenant Info --}}
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">{{ $tenant->name }}</div>
                    <div class="text-xs text-gray-500">{{ $tenant->email }} | {{ $tenant->phone }}</div>
                </td>

                <td class="px-6 py-4">
                    @php $balance = $tenant->balance; @endphp
                    <span class="text-sm font-bold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                        ₱{{ number_format($balance, 2) }}
                    </span>
                </td>

                {{-- Column 2: Status --}}
                <td class="px-6 py-4">
                    <span class="inline-flex items-center text-xs text-gray-400 font-bold uppercase">
                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span> 
                        Moved Out
                    </span>
                </td>

                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $tenant->move_out_date ? $tenant->move_out_date->format('M d, Y') : 'N/A' }}
                </td>

                {{-- Column 3: Actions --}}
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('tenants.show', $tenant->id) }}" class="text-green-600 hover:text-green-900 text-sm font-medium">View</a>
                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a>
                </td>
            </tr>   
            @endforeach
        </tbody>
    </table>
</div>
    </div>
</div>
@endsection

<script>
    function confirmMoveOut(form) {
        const button = form.querySelector('button[type="submit"]');
        const tenantName = button.dataset.tenantName;
        const balance = parseFloat(button.dataset.tenantBalance);

        let message = `Are you sure you want to move out ${tenantName}?`;
        if (balance > 0) {
            message += `\n\nWarning: This tenant has an outstanding balance of ₱${balance.toFixed(2)}. This action cannot be undone.`;
        }

        return confirm(message);
    }
</script>