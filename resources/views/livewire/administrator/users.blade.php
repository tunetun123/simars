<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Kelola akun, peran, dan akses pengguna di dalam sistem.</p>
        </div>
        <button wire:click="create()"
            class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Actions & Search Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                placeholder="Cari nama atau email...">
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                            / Peran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Metode Login</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->roles->count() > 0)
                                    @foreach ($user->roles as $userRole)
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 mr-1">
                                            {{ $userRole->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-400 text-sm italic">Belum diset</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->provider)
                                    <span class="text-sm text-gray-600"><i class="fa-solid fa-shield-halved mr-1"></i>
                                        SSO ({{ ucfirst($user->provider) }})</span>
                                @else
                                    <span class="text-sm text-gray-600"><i class="fa-solid fa-key mr-1"></i>
                                        Lokal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $user->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                @if (auth()->id() !== $user->id)
                                    <button onclick="confirmDelete({{ $user->id }})"
                                        class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-auto overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $userId ? 'Ubah Pengguna' : 'Tambah Pengguna Baru' }}
                    </h3>
                    <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan nama">
                            @error('name')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" wire:model="email"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="email@contoh.com">
                            @error('email')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peran (Role)</label>
                            <select wire:model="role"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $rl)
                                    <option value="{{ $rl->name }}">{{ $rl->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                                @if ($userId)
                                    <span class="text-gray-400 font-normal text-xs">(Kosongkan jika tidak ingin
                                        diubah)</span>
                                @endif
                            </label>
                            <input type="text" wire:model="password"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="{{ $userId ? 'Biarkan kosong' : 'Default: password123' }}">
                            @error('password')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                        <button type="button" wire:click="closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: data[0].icon,
                    confirmButtonColor: '#3b82f6'
                });
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengguna ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', id);
                }
            });
        }
    </script>
</div>
