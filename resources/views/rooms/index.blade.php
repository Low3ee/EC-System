@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Room Management</h1>
        <a href="{{ route('rooms.create') }}" class="bg-brand text-black px-4 py-2 rounded-lg shadow hover:text-white hover:bg-blue-700 transition">
            + Add New Room
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs uppercase text-gray-400 bg-gray-50 border-b">
                    <th class="px-6 py-4">Room #</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Default Rent</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Occupant</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rooms as $room)
                        <tr class="hover:bg-gray-50 transition">
                            
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $room->room_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $room->type }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                ₱{{ number_format($room->default_rent, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($room->is_available)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase text-[10px]">Available</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase text-[10px]">Occupied</span>
                                @endif
                            </td>
                          <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                {{ $room->tenants->pluck('name')->implode(', ') ?: 'Vacant' }}
                          </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{route('rooms.show', $room->id)}}"><span>View</span></a>

                                <a href="{{ route('rooms.edit', $room->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a>
                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this room? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        No rooms found. <a href="{{ route('rooms.create') }}" class="text-brand hover:underline">Add one now</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection