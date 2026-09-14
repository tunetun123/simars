<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Master Data: Elemen Penilaian (EP)</h2>
        <button wire:click="create"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Tambah EP
        </button>
    </div>

    <!-- Toolbar: Filter -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex space-x-4 items-center">
        <label for="filterCategory" class="text-sm font-medium text-gray-600">Filter Standar:</label>
        <select wire:model.live="filterCategory" id="filterCategory"
            class="rounded-md border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 max-w-xs w-full">
            <option value="">Semua Standar</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->group?->name }} - {{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="px-6 py-3 font-medium">Standar & Bab</th>
                        <th class="px-6 py-3 font-medium">Nama EP</th>
                        <th class="px-6 py-3 font-medium text-center">Jumlah Dokumen</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($elements as $el)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $el->category?->name }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $el->category?->group?->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-emerald-700">{{ $el->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                <span
                                    class="bg-gray-100 text-gray-600 px-2 py-1 rounded-md text-xs font-bold">{{ $el->documents_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <button wire:click="edit({{ $el->id }})"
                                    class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="confirmDelete({{ $el->id }})"
                                    class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition"
                                    title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data Elemen
                                Penilaian (EP).</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $elements->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">{{ $elementId ? 'Edit EP' : 'Tambah EP' }}</h3>
                    <button wire:click="$set('isModalOpen', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Standar</label>
                            <select wire:model="document_category_id"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Standar --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">[{{ $cat->group?->name }}]
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('document_category_id')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Elemen Penilaian
                                (EP)</label>
                            <input type="text" wire:model="name"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Contoh: EP 1.1">
                            @error('name')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('isModalOpen', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-md shadow-sm hover:bg-emerald-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', (event) => {
                Swal.fire({
                    title: event[0].title,
                    text: event[0].text,
                    icon: event[0].icon,
                    confirmButtonColor: '#10b981',
                });
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Elemen Penilaian?',
                text: "Semua Dokumen Akreditasi di dalamnya akan ikut terhapus dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', id)
                }
            })
        }
    </script>
</div>
