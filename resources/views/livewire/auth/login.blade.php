<div
    class="w-full max-w-md bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/50 p-10 relative z-10">
    <div class="text-center mb-8">
        <div
            class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
            @if (file_exists(public_path('logos/simars_logo.png')))
                <img src="{{ asset('logos/simars_logo.png') }}?{{ time() }}" class="w-full h-full object-cover">
            @else
                <i class="fa-solid fa-hospital text-3xl"></i>
            @endif
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">SIMARS</h1>
        <p class="text-gray-500 text-sm">Sistem Informasi Manajemen dan Administrasi<br />Rumah Sakit Bhayangkara Palu
        </p>
    </div>

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-regular fa-envelope text-gray-400"></i>
                </div>
                <input type="email" wire:model="email"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="admin@sigap.local">
            </div>
            @error('email')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input type="password" wire:model="password"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="••••••••">
            </div>
            @error('password')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" wire:model="remember"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
            </label>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex justify-center items-center">
            <span wire:loading.remove wire:target="login">Masuk</span>
            <span wire:loading wire:target="login"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
        </button>
    </form>
</div>
