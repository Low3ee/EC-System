@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('rooms.index') }}" class="text-sm text-gray-500 hover:text-brand">← Back to Rooms</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Add New Room</h1>
    </div>

    <form action="{{ route('rooms.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        @csrf

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                <p class="font-bold">Please correct the errors below:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="room_number" class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                <input type="text" name="room_number" id="room_number" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" value="{{ old('room_number') }}">
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                <input type="text" name="type" id="type" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" placeholder="e.g. Studio, 1-Bedroom" value="{{ old('type') }}">
            </div>

            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity (Max Tenants)</label>
                <input type="number" name="capacity" id="capacity" required min="1" class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" value="{{ old('capacity', 1) }}">
            </div>

            <div>
                <label for="default_rent" class="block text-sm font-medium text-gray-700 mb-1">Default Monthly Rent (₱)</label>
                <input type="number" step="0.01" name="default_rent" id="default_rent" required class="w-full rounded-lg border-gray-300 focus:border-brand focus:ring-brand" placeholder="5000.00" value="{{ old('default_rent') }}">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <a href="{{ route('rooms.index') }}" class="px-8 py-3 text-gray-600 hover:text-gray-800 mr-4">Cancel</a>
            <button type="submit" class="bg-brand text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-blue-700 transition">
                Save Room
            </button>
        </div>
    </form>
</div>
@endsection