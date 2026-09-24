<div class="w-full max-w-md bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/50 p-10 relative z-10">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
            <i class="fa-solid fa-key text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Ubah Password</h1>
        <p class="text-gray-500 text-sm">Silakan ubah password Anda demi keamanan akun.</p>
    </div>

    @if (session()->has('warning'))
        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        {{ session('warning') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="updatePassword" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input type="password" wire:model="current_password"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="••••••••">
            </div>
            @error('current_password')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-key text-gray-400"></i>
                </div>
                <input type="password" wire:model="password"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Minimal 6 karakter">
            </div>
            @error('password')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-check text-gray-400"></i>
                </div>
                <input type="password" wire:model="password_confirmation"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ketik ulang password baru">
            </div>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex justify-center items-center">
            <span wire:loading.remove wire:target="updatePassword">Simpan Password</span>
            <span wire:loading wire:target="updatePassword"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
        </button>
        
        <div class="mt-4 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition">
                    Batal & Logout
                </button>
            </form>
        </div>
    </form>
</div>
