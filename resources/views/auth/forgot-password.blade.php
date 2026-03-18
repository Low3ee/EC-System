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
                        <h2 class="text-5xl font-extrabold leading-tight">Recover your <br><span class="text-indigo-400">access.</span></h2>
                        <p class="mt-6 text-xl text-indigo-100 max-w-md leading-relaxed">
                            No problem. Just let us know your email address and we'll send you a secure reset link.
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/5 p-8 sm:p-12 xl:p-20 flex flex-col justify-center">
                <div class="mb-12">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Reset Access</h1>
                    <div class="h-1.5 w-12 bg-indigo-600 rounded-full"></div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2 ml-1 tracking-wider">Registered Email Address</label>
                        <x-text-input id="email" class="block w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl transition-all font-medium text-gray-700" 
                            type="email" name="email" :value="old('email')" required autofocus placeholder="manager@ecsystem.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-200 transition-all hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
                        Send Reset Link
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase tracking-widest">
                            ← Back to Login
                        </a>
                    </div>
                </form>

                <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span>V2.1-MLP</span>
                    <span>&copy; Easy Collection</span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>