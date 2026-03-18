@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tenants.index') }}" class="text-sm text-gray-500 hover:text-brand">← Back to Directory</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Tenant: {{ $tenant->name }}</h1>
    </div>

    <form action="{{ route('tenants.update', $tenant) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" value="{{ old('name', $tenant->name) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" value="{{ old('email', $tenant->email) }}">
            </div>
            <div>
                <label class="block text-sm font--medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" value="{{ old('phone', $tenant->phone) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Room</label>
                <select name="room_id" id="room_select" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand">
                    <option value="">Select a room...</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-price="{{ $room->default_rent }}" @if(old('room_id', $tenant->room_id) == $room->id) selected @endif>
                            Room {{ $room->room_number }} (₱{{ number_format($room->default_rent, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Base Rent (₱)</label>
                <input type="number" name="base_rent" id="base_rent" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" placeholder="5000" value="{{ old('base_rent', $tenant->base_rent) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Due Day</label>
                <input type="number" name="due_day" min="1" max="31" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" placeholder="1 to 31" value="{{ old('due_day', $tenant->due_day) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lease Start Date</label>
                <input type="date" name="lease_start" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" value="{{ old('lease_start', $tenant->lease_start) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand">
                    <option value="Active" @if(old('status', $tenant->status) == 'Active') selected @endif>Active</option>
                    <option value="Moved Out" @if(old('status', $tenant->status) == 'Moved Out') selected @endif>Moved Out</option>
                </select>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-brand text-black px-8 py-3 rounded-lg font-bold shadow-lg hover:text-white hover:bg-blue-700 transition">
                Save Changes
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
            }
        });
    });
</script>
@endsection
