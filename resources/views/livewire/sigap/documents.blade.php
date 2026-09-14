<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Manajemen Arsip Dokumen</h2>
        @can('create sigap documents')
            <button wire:click="create"
                class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex items-center">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Unggah Dokumen
            </button>
        @endcan
    </div>

    <!-- Toolbar: Filter & View Toggle -->
    <div
        class="bg-white p-4 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0">
        <div class="flex flex-col sm:flex-row sm:space-x-4 items-start sm:items-center w-full md:w-auto gap-2 sm:gap-0">
            <label for="filterCategory" class="text-sm font-medium text-gray-600">Filter Kategori:</label>
            <select wire:model.live="filterCategory" id="filterCategory"
                class="w-full sm:w-auto rounded-md border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex bg-gray-100 rounded-md p-1 border border-gray-200 self-end md:self-auto">
            <button wire:click="setViewMode('grid')"
                class="px-3 py-1.5 text-sm rounded-md transition {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}"
                title="Tampilan Card">
                <i class="fa-solid fa-border-all"></i>
            </button>
            <button wire:click="setViewMode('table')"
                class="px-3 py-1.5 text-sm rounded-md transition {{ $viewMode === 'table' ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}"
                title="Tampilan Tabel">
                <i class="fa-solid fa-list"></i>
            </button>
        </div>
    </div>

    @if ($viewMode === 'table')
        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm">
                            <th class="px-6 py-3 font-medium">No. Referensi</th>
                            <th class="px-6 py-3 font-medium">Judul Dokumen</th>
                            <th class="px-6 py-3 font-medium">Kategori</th>
                            <th class="px-6 py-3 font-medium">Tanggal Dokumen</th>
                            <th class="px-6 py-3 font-medium">Metadata File</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-sm text-blue-600 font-bold font-mono whitespace-nowrap">
                                    {{ $doc->reference_code ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                    {{ $doc->title }}
                                    <div class="text-xs text-gray-500 font-normal mt-1">
                                        {{ Str::limit($doc->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span
                                        class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs">{{ $doc->category?->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $doc->document_date ? $doc->document_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if ($doc->file_size)
                                        <div class="flex items-center space-x-2">
                                            <i class="fa-solid fa-file-pdf text-red-400"></i>
                                            <span class="text-xs">{{ number_format($doc->file_size / 1024, 2) }}
                                                KB</span>
                                        </div>
                                        <div class="text-[10px] truncate max-w-[120px] mt-1"
                                            title="{{ $doc->file_name }}">{{ $doc->file_name }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-right space-x-1 whitespace-nowrap">
                                    <button wire:click="preview({{ $doc->id }})"
                                        class="inline-block text-indigo-500 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition"
                                        title="Pratinjau Dokumen">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <a href="{{ route('sigap.documents.download', $doc->id) }}"
                                        class="inline-block text-green-500 hover:text-green-700 bg-green-50 hover:bg-green-100 p-2 rounded-md transition"
                                        title="Unduh File">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                    @can('edit sigap documents')
                                        <button wire:click="edit({{ $doc->id }})"
                                            class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    @endcan
                                    @can('delete sigap documents')
                                        <button onclick="confirmDeleteDoc({{ $doc->id }})"
                                            class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                    <p>Tidak ada dokumen arsip ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Grid/Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-4">
            @forelse($documents as $doc)
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col">
                    <div class="p-5 flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <span
                                class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">{{ $doc->category?->name }}</span>
                            <span class="text-xs text-gray-400"><i class="fa-regular fa-calendar mr-1"></i>
                                {{ $doc->document_date ? $doc->document_date->format('d M Y') : '-' }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-1 line-clamp-2" title="{{ $doc->title }}">
                            {{ $doc->title }}</h3>
                        <div class="text-xs text-blue-500 font-mono mb-3">{{ $doc->reference_code ?? '-' }}</div>
                        <p class="text-sm text-gray-500 line-clamp-3 mb-4">
                            {{ $doc->description ?? 'Tidak ada deskripsi.' }}</p>

                        @if ($doc->file_size)
                            <div
                                class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-100 mt-auto">
                                <div class="bg-red-100 p-2.5 rounded text-red-500 flex-shrink-0">
                                    <i class="fa-solid fa-file-pdf text-lg"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-medium text-gray-700 truncate"
                                        title="{{ $doc->file_name }}">{{ $doc->file_name }}</div>
                                    <div class="text-[10px] text-gray-500 mt-0.5">
                                        {{ number_format($doc->file_size / 1024, 2) }} KB &bull;
                                        {{ strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION)) }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div class="text-xs text-gray-500 truncate max-w-[100px]"
                            title="{{ $doc->uploader?->name ?? 'Sistem' }}">
                            <i class="fa-regular fa-user mr-1"></i> {{ $doc->uploader?->name ?? 'Sistem' }}
                        </div>
                        <div class="flex space-x-2">
                            <button wire:click="preview({{ $doc->id }})"
                                class="text-indigo-600 hover:text-indigo-800 bg-indigo-100 hover:bg-indigo-200 w-8 h-8 rounded-md flex items-center justify-center transition"
                                title="Pratinjau Dokumen">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                            <a href="{{ route('sigap.documents.download', $doc->id) }}"
                                class="text-green-600 hover:text-green-800 bg-green-100 hover:bg-green-200 w-8 h-8 rounded-md flex items-center justify-center transition"
                                title="Unduh File">
                                <i class="fa-solid fa-download text-xs"></i>
                            </a>
                            @can('edit sigap documents')
                                <button wire:click="edit({{ $doc->id }})"
                                    class="text-blue-600 hover:text-blue-800 bg-blue-100 hover:bg-blue-200 w-8 h-8 rounded-md flex items-center justify-center transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                            @endcan
                            @can('delete sigap documents')
                                <button onclick="confirmDeleteDoc({{ $doc->id }})"
                                    class="text-red-600 hover:text-red-800 bg-red-100 hover:bg-red-200 w-8 h-8 rounded-md flex items-center justify-center transition"
                                    title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-lg border border-dashed border-gray-300">
                    <i class="fa-regular fa-folder-open text-5xl mb-4 text-gray-300"></i>
                    <p class="text-gray-500 font-medium">Tidak ada dokumen arsip ditemukan.</p>
                </div>
            @endforelse
        </div>
    @endif

    <div class="mb-4">
        {{ $documents->links() }}
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden mt-10 mb-10">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">{{ $documentId ? 'Edit Dokumen' : 'Unggah Dokumen' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
                            <input type="text" wire:model="title"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('title')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select wire:model="document_category_id"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('document_category_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tgl Dokumen</label>
                                <input type="date" wire:model="document_date"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('document_date')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File (Max: 20MB)</label>
                            <input type="file" wire:model="file"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if ($documentId)
                                <p class="text-[10px] text-gray-400 mt-1">*Abaikan jika tidak ingin mengganti file
                                    lama.</p>
                            @endif
                            <div wire:loading wire:target="file" class="text-xs text-blue-500 mt-1"><i
                                    class="fa-solid fa-spinner fa-spin mr-1"></i> Mengunggah file...</div>
                            @error('file')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat
                                (Opsional)</label>
                            <textarea wire:model="description" rows="3"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('description')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Preview Modal -->
    @if ($isPreviewModalOpen && $previewDoc)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $previewDoc->title }}</h3>
                        <p class="text-xs text-gray-500 font-mono mt-1">{{ $previewDoc->reference_code }} &bull;
                            {{ $previewDoc->file_name }}</p>
                    </div>
                    <button wire:click="closePreviewModal"
                        class="text-gray-400 hover:text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="flex-grow p-0 bg-gray-100 overflow-hidden relative" style="min-height: 60vh;">
                    @if (in_array(strtolower(pathinfo($previewDoc->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                        <div class="w-full h-full flex items-center justify-center p-4 overflow-auto">
                            <img src="{{ route('sigap.documents.view', $previewDoc->id) }}"
                                alt="{{ $previewDoc->title }}"
                                class="max-w-full max-h-full object-contain shadow-sm border border-gray-200">
                        </div>
                    @elseif(strtolower(pathinfo($previewDoc->file_name, PATHINFO_EXTENSION)) === 'pdf')
                        <iframe src="{{ route('sigap.documents.view', $previewDoc->id) }}"
                            class="w-full h-full border-0 min-h-[60vh]"></iframe>
                    @else
                        <div
                            class="w-full h-full flex flex-col items-center justify-center p-12 text-center min-h-[40vh]">
                            <i class="fa-solid fa-file-circle-exclamation text-6xl text-gray-400 mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-700">Pratinjau tidak tersedia</h4>
                            <p class="text-gray-500 mt-2">File dengan format
                                <b>{{ strtoupper(pathinfo($previewDoc->file_name, PATHINFO_EXTENSION)) }}</b> tidak
                                dapat dipratinjau langsung di peramban.
                            </p>
                            <a href="{{ route('sigap.documents.download', $previewDoc->id) }}"
                                class="mt-6 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                <i class="fa-solid fa-download mr-2"></i> Unduh File
                            </a>
                        </div>
                    @endif
                </div>
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
                    confirmButtonColor: '#3b82f6',
                });
            });
        });

        function confirmDeleteDoc(id) {
            Swal.fire({
                title: 'Hapus Dokumen?',
                text: "File fisik dokumen juga akan ikut terhapus dari server penyimpanan aman!",
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
