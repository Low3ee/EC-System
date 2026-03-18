<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-4 lg:p-8 bg-slate-50">
        <div class="flex w-full max-w-6xl overflow-hidden bg-white shadow-2xl rounded-[2rem] border border-gray-100">
            
            <div class="hidden lg:flex lg:w-3/5 relative bg-indigo-900">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" 
                     alt="Modern Architecture" 
                     class="absolute inset-0 h-full w-full object-cover opacity-50 mix-blend-overlay">
                
                <div class="relative z-10 flex flex-col justify-between p-16 w-full text-white">
                    <div>
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-indigo-900 font-black text-2xl tracking-tighter italic">EC</span>
                            </div>
                            <span class="text-2xl font-black tracking-widest uppercase">Easy Collection</span>
                        </div>
                        <h2 class="text-5xl font-extrabold leading-tight">Simplify your <br><span class="text-indigo-400">rental revenue.</span></h2>
                        <p class="mt-6 text-xl text-indigo-100 max-w-md leading-relaxed">
                            A system designed specifically for modern real estate management.
                        </p>
                    </div>
                    
                    <div class="flex gap-8 text-sm font-semibold text-indigo-200 uppercase tracking-[0.2em]">
                        <span>• Efficiency</span>
                        <span>• Clarity</span>
                        <span>• Results</span>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/5 p-8 sm:p-12 xl:p-20 flex flex-col justify-center">
                <div class="mb-12">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Manager Login</h1>
                    <div class="h-1.5 w-12 bg-indigo-600 rounded-full"></div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if ($errors->any())
                    <div class="mb-8 p-5 bg-red-50 border-2 border-red-200 rounded-2xl animate-pulse">
                        <div class="flex items-center gap-2 mb-2 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="font-black text-xs uppercase tracking-widest">Entry Error</span>
                        </div>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-red-700 font-extrabold text-sm italic">
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2 ml-1 tracking-wider">System Username / Email</label>
                        <x-text-input id="email" class="block w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl transition-all font-medium text-gray-700" 
                            type="email" name="email" :value="old('email')" required autofocus placeholder="manager@ecsystem.com" />
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 ml-1">
                            <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Security Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition" href="{{ route('password.request') }}">
                                    Recover Access
                                </a>
                            @endif
                        </div>
                        <x-text-input id="password" class="block w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl transition-all font-medium"
                            type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    </div>

                    <div class="flex items-center py-2">
                        <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 transition cursor-pointer">
                        <label for="remember_me" class="ml-3 text-sm font-bold text-gray-500 cursor-pointer">Trust this device</label>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-200 transition-all hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
                        Enter EC System
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span>V2.1-MLP</span>
                    <span>&copy; Easy Collection</span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>