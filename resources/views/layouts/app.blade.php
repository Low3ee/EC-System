<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Easy Collection' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { brand: '#1e40af' } } }
        }
    </script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    <aside class="w-64 bg-brand text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6 font-bold text-xl tracking-wider uppercase">
            EasyCollect
        </div>
      <nav class="flex-1 px-4 py-4 space-y-2">
    <a href="{{ route('dashboard') }}" 
       class="block px-4 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-blue-800' : 'hover:bg-blue-700' }} transition">
       Dashboard
    </a>
    <a href="{{ route('rooms.index') }}" 
       class="block px-4 py-2 rounded {{ request()->routeIs('rooms.*') ? 'bg-blue-800' : 'hover:bg-blue-700' }} transition">
       Rooms
    </a>
    <a href="{{ route('tenants.index') }}" 
       class="block px-4 py-2 rounded {{ request()->routeIs('tenants.*') ? 'bg-blue-800' : 'hover:bg-blue-700' }} transition">
       Tenants
    </a>
     <a href="{{ route('invoices.index') }}" 
       class="block px-4 py-2 rounded {{ request()->routeIs('tenants.*') ? 'bg-blue-800' : 'hover:bg-blue-700' }} transition">
       Invoices
    </a>
</nav>
        <div class="p-4 border-t border-blue-900 text-xs text-blue-300">
            v2.0 MVP
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
            <button class="md:hidden text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
            <div class="text-sm text-gray-500 font-medium">
                Welcome back, <span class="text-gray-800 font-bold">Admin</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
            @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-medium rounded shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-medium rounded shadow-sm">
        {{ session('error') }}
    </div>
@endif
            @yield('content')
        </main>
    </div>

</body>
</html>