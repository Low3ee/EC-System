@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tenants.index') }}" class="text-sm text-gray-500 hover:text-brand">← Back to Directory</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Add New Tenant</h1>
    </div>

    <form action="{{ route('tenants.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger text-red-600 font-bold">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Room</label>
                <select name="room_id" id="room_select" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand">
                    <option value="">Select a vacant room...</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-price="{{ $room->default_rent }}">
                            Room {{ $room->room_number }} (₱{{ number_format($room->default_rent, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Base Rent (₱)</label>
                {{-- Added id="base_rent" here --}}
                <input type="number" name="base_rent" id="base_rent" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" placeholder="5000" value="" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Due Day</label>
                <input type="number" name="due_day" value="{{ old('due_day') }}" min="1" max="31" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" placeholder="1 to 31">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lease Start Date</label>
                <input type="date" name="lease_start" value="{{ old('lease_start') }}" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-brand text-black px-8 py-3 rounded-lg font-bold shadow-lg hover:text-white hover:bg-blue-700 transition">
                Save Tenant & Assign Room
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomSelect = document.getElementById('room_select');
        const rentInput = document.getElementById('base_rent');

        roomSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');

            if (price) {
                rentInput.value = price;
            } else {
                rentInput.value = '';
            }
        });
    });
</script>
@endsection