<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Manajemen Role & Hak Akses</h2>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Kelola peran (role) dan tetapkan hak akses modul untuk tiap
                peran.</p>
        </div>
        <button wire:click="create()"
            class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Role
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hak
                            Akses (Permissions)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-shield-halved text-blue-500 mr-3 text-lg"></i>
                                    <div class="text-sm font-bold text-gray-900">{{ $role->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-2xl">
                                    @if ($role->name === 'Super Admin')
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-red-100 text-red-800">
                                            All Permissions (Bypass)
                                        </span>
                                    @else
                                        @forelse($role->permissions as $perm)
                                            <span
                                                class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $perm->name }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 text-sm italic">Belum ada hak akses</span>
                                        @endforelse
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($role->name !== 'Super Admin')
                                    <button wire:click="edit({{ $role->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition"
                                        title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="confirmDeleteRole({{ $role->id }})"
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data role yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 overflow-y-auto p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-5xl mx-auto overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $roleId ? 'Ubah Role' : 'Tambah Role Baru' }}
                    </h3>
                    <button wire:click="closeModal()"
                        class="text-gray-400 hover:text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="store" class="flex-1 flex flex-col overflow-hidden">
                    <div class="p-6 overflow-y-auto flex-1 bg-gray-50/50">
                        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Role</label>
                            <input type="text" wire:model="name"
                                class="w-full rounded-md border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Misal: Asesor Siparsi">
                            @error('name')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center mb-4">
                                <i class="fa-solid fa-shield-check text-blue-500 mr-2 text-lg"></i>
                                <label class="block text-base font-bold text-gray-800">Konfigurasi Hak Akses
                                    (Permissions)</label>
                            </div>

                            <div class="space-y-6">
                                @foreach ($permissionGroups as $group => $perms)
                                    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                                        <div
                                            class="border-b border-gray-100 pb-2 mb-4 flex items-center justify-between">
                                            <h4 class="font-bold text-gray-800 text-sm flex items-center">
                                                @if ($group === 'Administrator (Sistem)')
                                                    <i class="fa-solid fa-user-gear text-red-500 mr-2 w-4"></i>
                                                @elseif($group === 'Modul SIGAP')
                                                    <i class="fa-solid fa-file-signature text-blue-500 mr-2 w-4"></i>
                                                @elseif($group === 'Modul SIPARSI (Global)')
                                                    <i class="fa-solid fa-folder-tree text-emerald-500 mr-2 w-4"></i>
                                                @elseif($group === 'SIPARSI (Hak Akses Per Standar)')
                                                    <i class="fa-solid fa-list-check text-orange-500 mr-2 w-4"></i>
                                                @else
                                                    <i class="fa-solid fa-puzzle-piece text-gray-500 mr-2 w-4"></i>
                                                @endif
                                                {{ $group }}
                                            </h4>
                                            @if ($group === 'SIPARSI (Hak Akses Per Standar)')
                                                <span
                                                    class="text-[10px] bg-orange-100 text-orange-800 px-2 py-0.5 rounded border border-orange-200">Hak
                                                    Akses Unggah Khusus</span>
                                            @endif
                                        </div>

                                        @if ($group === 'SIPARSI (Hak Akses Per Standar)')
                                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                                @foreach ($perms as $perm)
                                                    @php
                                                        $std = strtoupper(
                                                            str_replace('upload siparsi ', '', $perm->name),
                                                        );
                                                    @endphp
                                                    <label
                                                        class="relative flex items-center p-2 rounded-md border cursor-pointer hover:bg-orange-50 transition {{ in_array($perm->name, $selectedPermissions) ? 'border-orange-500 bg-orange-50' : 'border-gray-200 bg-gray-50' }}">
                                                        <input type="checkbox" wire:model="selectedPermissions"
                                                            value="{{ $perm->name }}"
                                                            class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                                        <span
                                                            class="ml-2 text-xs font-bold text-gray-700">{{ $std }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <p class="text-[10px] text-gray-500 mt-3 italic">* Cukup centang standar
                                                spesifik yang diizinkan jika *user* ini tidak memiliki izin global
                                                "create siparsi documents".</p>
                                        @else
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach ($perms as $perm)
                                                    <label
                                                        class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded transition">
                                                        <input type="checkbox" wire:model="selectedPermissions"
                                                            value="{{ $perm->name }}"
                                                            class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                                        <span class="text-sm text-gray-700">{{ $perm->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedPermissions')
                                <span class="text-red-500 text-xs mt-2 block font-medium"><i
                                        class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div
                        class="px-6 py-4 bg-white border-t border-gray-100 flex justify-end space-x-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                        <button type="button" wire:click="closeModal()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 transition flex items-center">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Konfigurasi Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        function confirmDeleteRole(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Role beserta konfigurasi hak aksesnya akan dihapus permanen!",
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
