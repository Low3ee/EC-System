<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Horizon 99 Real Estate Dasboard</h1>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                <p class="text-sm text-gray-500 uppercase font-bold">Total Collected</p>
                <p class="text-2xl font-black text-gray-800">₱{{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Pending Balance</p>
                    <p class="text-2xl font-black text-gray-800">₱{{ number_format($stats['pending_amount'], 2) }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                <p class="text-sm text-gray-500 uppercase font-bold">Occupied Rooms</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['occupied_rooms'] }} / {{ $stats['rooms']->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gray-400">
                <p class="text-sm text-gray-500 uppercase font-bold">Vacant Single Units</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['available_rooms'] }}</p>
            </div>
             <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gray-400">
                <p class="text-sm text-gray-500 uppercase font-bold">Vacant Bedspacers</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['available_beds'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gray-400">
                <p class="text-sm text-gray-500 uppercase font-bold">Population</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['population'] }} / {{ $stats['total_capacity'] }}</p>
            </div>
        </div>

       {{-- Room Map --}}
<div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Room Map</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach ($stats['rooms'] as $room)
            <a href="{{ route('rooms.show', $room) }}" class="block bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-brand transition-all duration-200 ease-in-out">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-900">Room #{{ $room->room_number }}</h3>
                    <span class="text-xs font-bold px-2 py-1 rounded-full {{ $room->tenants->count() >= $room->capacity ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $room->tenants->count() }} / {{ $room->capacity }}
                    </span>
                </div>
                
                <div class="h-20">
                    @if($room->tenants->isNotEmpty())
                        <ul class="space-y-1 text-sm text-gray-600">
                            @foreach($room->tenants as $tenant)
                                <li class="truncate flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $tenant->name }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="h-full flex items-center justify-center">
                            <p class="text-sm text-gray-400 italic">Vacant</p>
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>


        {{-- Recent Activity --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-700">Recent Rent Collections</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs uppercase text-gray-400 bg-gray-50">
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Tenant</th>
                        <th class="px-6 py-3">Method</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentPayments as $payment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->paid_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $payment->invoice->tenant->name }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded capitalize">{{ $payment->payment_method }}</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">+₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600"><a href="{{route('invoices.show', $payment->invoice->id)}}" class="text-brand hover:underline">View Invoice</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">No payments recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>