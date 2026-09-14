<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Pengaturan Aplikasi</h2>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Kelola preferensi sistem, termasuk logo untuk setiap modul
                aplikasi.</p>
        </div>
    </div>

    <!-- Modul Logo Settings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-image mr-2 text-blue-500"></i> Kustomisasi
                Logo Modul</h3>
            <p class="text-sm text-gray-500 mt-1">Unggah gambar dengan format (PNG, JPG, WEBP), maksimal 2MB, dan rasio
                (1:1) persegi.</p>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- SIMARS Logo -->
            <div
                class="border border-gray-200 rounded-lg p-4 text-center hover:border-gray-400 transition-colors bg-gray-50/50">
                <h4 class="font-bold text-gray-800 mb-4">Logo Utama (SIMARS)</h4>
                <div
                    class="w-24 h-24 mx-auto bg-white border border-gray-200 rounded-lg shadow-sm flex items-center justify-center mb-4 overflow-hidden relative">
                    @if ($simars_logo)
                        <img src="{{ $simars_logo->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($hasSimarsLogo)
                        <img src="{{ asset('logos/simars_logo.png') }}?{{ time() }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-hospital text-3xl text-gray-300"></i>
                    @endif
                </div>

                <form wire:submit.prevent="uploadLogo('simars')">
                    <div class="mb-3">
                        <input type="file" wire:model="simars_logo" id="simars_logo" class="hidden">
                        <label for="simars_logo"
                            class="cursor-pointer inline-block w-full py-2 px-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-50 transition text-center">
                            <i class="fa-solid fa-folder-open mr-1"></i> Pilih Gambar
                        </label>
                        @error('simars_logo')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" @if (!$simars_logo) disabled @endif
                            class="flex-1 bg-gray-700 disabled:opacity-50 hover:bg-gray-800 text-white text-sm font-medium py-2 rounded transition">
                            Simpan
                        </button>
                        @if ($hasSimarsLogo)
                            <button type="button" wire:click="deleteLogo('simars')" wire:confirm="Hapus logo SIMARS?"
                                class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-medium py-2 px-3 rounded transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        @endif
                    </div>
                </form>
                <div wire:loading wire:target="simars_logo" class="mt-2 text-xs text-gray-500">Memproses...</div>
            </div>

            <!-- SIGAP Logo -->
            <div
                class="border border-gray-200 rounded-lg p-4 text-center hover:border-blue-300 transition-colors bg-blue-50/30">
                <h4 class="font-bold text-gray-800 mb-4">Logo SIGAP</h4>
                <div
                    class="w-24 h-24 mx-auto bg-white border border-gray-200 rounded-lg shadow-sm flex items-center justify-center mb-4 overflow-hidden relative">
                    @if ($sigap_logo)
                        <img src="{{ $sigap_logo->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($hasSigapLogo)
                        <img src="{{ asset('logos/sigap_logo.png') }}?{{ time() }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-box-archive text-3xl text-gray-300"></i>
                    @endif
                </div>

                <form wire:submit.prevent="uploadLogo('sigap')">
                    <div class="mb-3">
                        <input type="file" wire:model="sigap_logo" id="sigap_logo" class="hidden">
                        <label for="sigap_logo"
                            class="cursor-pointer inline-block w-full py-2 px-3 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition text-center">
                            <i class="fa-solid fa-folder-open mr-1"></i> Pilih Gambar
                        </label>
                        @error('sigap_logo')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" @if (!$sigap_logo) disabled @endif
                            class="flex-1 bg-blue-600 disabled:opacity-50 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded transition">
                            Simpan
                        </button>
                        @if ($hasSigapLogo)
                            <button type="button" wire:click="deleteLogo('sigap')" wire:confirm="Hapus logo SIGAP?"
                                class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-medium py-2 px-3 rounded transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        @endif
                    </div>
                </form>
                <div wire:loading wire:target="sigap_logo" class="mt-2 text-xs text-blue-500">Memproses...</div>
            </div>

            <!-- SIPARSI Logo -->
            <div
                class="border border-gray-200 rounded-lg p-4 text-center hover:border-emerald-300 transition-colors bg-emerald-50/30">
                <h4 class="font-bold text-gray-800 mb-4">Logo SIPARSI</h4>
                <div
                    class="w-24 h-24 mx-auto bg-white border border-gray-200 rounded-lg shadow-sm flex items-center justify-center mb-4 overflow-hidden relative">
                    @if ($siparsi_logo)
                        <img src="{{ $siparsi_logo->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($hasSiparsiLogo)
                        <img src="{{ asset('logos/siparsi_logo.png') }}?{{ time() }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-hospital-user text-3xl text-gray-300"></i>
                    @endif
                </div>

                <form wire:submit.prevent="uploadLogo('siparsi')">
                    <div class="mb-3">
                        <input type="file" wire:model="siparsi_logo" id="siparsi_logo" class="hidden">
                        <label for="siparsi_logo"
                            class="cursor-pointer inline-block w-full py-2 px-3 text-sm font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 rounded hover:bg-emerald-100 transition text-center">
                            <i class="fa-solid fa-folder-open mr-1"></i> Pilih Gambar
                        </label>
                        @error('siparsi_logo')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" @if (!$siparsi_logo) disabled @endif
                            class="flex-1 bg-emerald-600 disabled:opacity-50 hover:bg-emerald-700 text-white text-sm font-medium py-2 rounded transition">
                            Simpan
                        </button>
                        @if ($hasSiparsiLogo)
                            <button type="button" wire:click="deleteLogo('siparsi')" wire:confirm="Hapus logo SIPARSI?"
                                class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-medium py-2 px-3 rounded transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        @endif
                    </div>
                </form>
                <div wire:loading wire:target="siparsi_logo" class="mt-2 text-xs text-emerald-500">Memproses...</div>
            </div>

            <!-- ADMIN Logo -->
            <div
                class="border border-gray-200 rounded-lg p-4 text-center hover:border-purple-300 transition-colors bg-purple-50/30">
                <h4 class="font-bold text-gray-800 mb-4">Logo Admin</h4>
                <div
                    class="w-24 h-24 mx-auto bg-white border border-gray-200 rounded-lg shadow-sm flex items-center justify-center mb-4 overflow-hidden relative">
                    @if ($admin_logo)
                        <img src="{{ $admin_logo->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($hasAdminLogo)
                        <img src="{{ asset('logos/admin_logo.png') }}?{{ time() }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-users-gear text-3xl text-gray-300"></i>
                    @endif
                </div>

                <form wire:submit.prevent="uploadLogo('admin')">
                    <div class="mb-3">
                        <input type="file" wire:model="admin_logo" id="admin_logo" class="hidden">
                        <label for="admin_logo"
                            class="cursor-pointer inline-block w-full py-2 px-3 text-sm font-medium text-purple-600 bg-purple-50 border border-purple-200 rounded hover:bg-purple-100 transition text-center">
                            <i class="fa-solid fa-folder-open mr-1"></i> Pilih Gambar
                        </label>
                        @error('admin_logo')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" @if (!$admin_logo) disabled @endif
                            class="flex-1 bg-purple-600 disabled:opacity-50 hover:bg-purple-700 text-white text-sm font-medium py-2 rounded transition">
                            Simpan
                        </button>
                        @if ($hasAdminLogo)
                            <button type="button" wire:click="deleteLogo('admin')" wire:confirm="Hapus logo Admin?"
                                class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-medium py-2 px-3 rounded transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        @endif
                    </div>
                </form>
                <div wire:loading wire:target="admin_logo" class="mt-2 text-xs text-purple-500">Memproses...</div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', (event) => {
                Swal.fire({
                    title: event[0].title,
                    text: event[0].text,
                    icon: event[0].icon,
                    confirmButtonColor: '#3b82f6',
                });
            });
        });
    </script>
</div>
