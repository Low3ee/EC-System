@extends('layouts.app')

@section('content')
@php
    // Fetch all available utilities for the dropdown
    $allUtilities = \App\Models\Utility::all();
@endphp
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('rooms.index') }}" class="text-sm text-gray-500 hover:text-brand">← Back to Rooms</a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Room Details: #{{ $room->room_number }}</h1>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('rooms.edit', $room) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                Edit Room
            </a>
        </div>
    </div>

    {{-- Room Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-semibold">Type</p>
            <p class="text-lg font-bold text-gray-800">{{ $room->type }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-semibold">Monthly Rent</p>
            <p class="text-lg font-bold text-brand">₱{{ number_format($room->default_rent, 2) }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-semibold">Capacity</p>
            <p class="text-lg font-bold text-gray-800">{{ $room->tenants->count() }} / {{ $room->capacity }} Beds</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-semibold">Status</p>
            @if($room->tenants->count() >= $room->capacity)
                <span class="inline-block mt-1 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-bold">Fully Occupied</span>
            @else
                <span class="inline-block mt-1 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">Available</span>
            @endif
        </div>
    </div>

    {{-- Utilities Section --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Add Utility Form --}}
        <div class="md:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Add Utility Bill</h3>
            <form action="{{ route('rooms.utilities.store', $room) }}" method="POST">
                @csrf
                <div class="mb-4" x-data="{ selectedUtility: '{{ $allUtilities->first()->id ?? '' }}', utilities: {{ $allUtilities->keyBy('id') }} }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Utility Type</label>
                    <select name="utility_id" x-model="selectedUtility" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand focus:ring focus:ring-brand focus:ring-opacity-50 border p-2">
                        @foreach($allUtilities as $utility)
                            <option value="{{ $utility->id }}">{{ $utility->name }}</option>
                        @endforeach
                    </select>

                    <div x-show="utilities[selectedUtility] && utilities[selectedUtility].name === 'Other'" x-transition class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="description" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand focus:ring focus:ring-brand focus:ring-opacity-50 border p-2" placeholder="e.g. Aircon Maintenance Fee">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Amount (₱)</label>
                    <input type="number" step="0.01" name="amount" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand focus:ring focus:ring-brand focus:ring-opacity-50 border p-2" placeholder="0.00" required>
                </div>
                <button type="submit" class="w-full bg-gray-800 text-white font-medium py-2 rounded-lg hover:bg-gray-900 transition">
                    Add Utility
                </button>
            </form>
        </div>

        {{-- Assigned Utilities List --}}
        <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">Assigned Utilities</h2>
                <span class="text-sm text-gray-500">Total: ₱{{ number_format($room->utilities->sum('pivot.amount'), 2) }}</span>
            </div>
            
            @if($room->utilities->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach($room->utilities as $utility)
                        <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">{{ $utility->name }}
                                    @if($utility->name === 'Other' && $utility->pivot->description)
                                        <span class="text-gray-500 font-normal">- {{ $utility->pivot->description }}</span>
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">Monthly: ₱{{ number_format($utility->pivot->amount, 2) }}</p>
                            </div>
                            <form action="{{ route('rooms.utilities.destroy', [$room, $utility]) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-8 text-center text-gray-500">
                    <p>No utilities assigned to this room yet.</p>
                </div>
            @endif
        </div>
    </div>
    
    @push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    @endpush
    
    {{-- Current Tenants List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Current Tenants</h2>
        </div>
        
        @if($room->tenants->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 text-sm border-b border-gray-100">
                            <th class="px-6 py-4 font-medium">Name</th>
                            <th class="px-6 py-4 font-medium">Contact</th>
                            <th class="px-6 py-4 font-medium">Lease Start</th>
                            <th class="px-6 py-4 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($room->tenants as $tenant)
                            <tr class="hover:bg-gray-50 text-gray-700 border-b border-gray-50 last:border-0">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $tenant->name }}</td>
                                <td class="px-6 py-4">{{ $tenant->phone }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($tenant->lease_start)->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('tenants.index') }}" class="text-brand hover:underline">View Profile</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <p>No tenants currently assigned to this room.</p>
            </div>
        @endif
    </div>
</div>
@endsection