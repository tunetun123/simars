<div class="flex flex-col lg:h-[calc(100vh-2rem)] space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center flex-shrink-0 gap-4 sm:gap-0">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Manajemen Dokumen SIPARSI</h2>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Pilih standar pada panel untuk melihat dan mengelola
                dokumen.</p>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            @if (session()->has('message'))
                <div
                    class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-2 rounded-md shadow-sm text-sm flex items-center w-full">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('message') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Main Workspace (Split Pane) -->
    <div class="flex-1 flex flex-col lg:flex-row gap-4 lg:overflow-hidden pb-4">

        <!-- Left Pane: Master (Navigation) -->
        <div
            class="w-full lg:w-1/4 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden max-h-[40vh] lg:max-h-none">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex-shrink-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchStandard"
                        placeholder="Cari Standar (Misal: TKRS)..."
                        class="w-full text-sm rounded border border-gray-300 pl-9 pr-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-6">
                @forelse($groups as $group)
                    @if ($group->categories->count() > 0)
                        <div>
                            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 pl-2">
                                {{ $group->name }}</h4>
                            <div class="space-y-1">
                                @foreach ($group->categories as $category)
                                    <button wire:click="selectCategory({{ $category->id }})"
                                        class="w-full text-left px-3 py-2 rounded text-sm transition font-medium {{ $selectedCategoryId === $category->id ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border border-transparent' }}">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-8 text-sm text-gray-500 italic">Standar tidak ditemukan.</div>
                @endforelse
            </div>
        </div>

        <!-- Right Pane: Detail (EP & Documents) -->
        <div
            class="flex-1 w-full bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col lg:overflow-hidden relative">
            @if ($selectedCategory)
                <div class="p-5 border-b border-gray-100 bg-emerald-50/40 flex-shrink-0">
                    <h2 class="text-lg font-bold text-emerald-800">{{ $selectedCategory->name }}</h2>
                    <p class="text-xs text-gray-500 mt-1">{{ $selectedCategory->group?->name }}</p>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    @forelse($selectedCategory->assessmentElements as $ep)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" x-data="{ openEp: true }">
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-2 gap-3 sm:gap-0">
                                <div class="flex items-center cursor-pointer flex-wrap" @click="openEp = !openEp">
                                    <i class="fa-solid fa-chevron-down text-gray-400 transition-transform mr-2"
                                        :class="{ '-rotate-90': !openEp }"></i>
                                    <i class="fa-solid fa-list-check text-blue-500 w-6"></i>
                                    <span class="font-bold text-gray-800 text-lg">{{ $ep->name }}</span>
                                    <span
                                        class="ml-3 bg-white px-2 py-0.5 rounded text-xs font-bold text-gray-500 border border-gray-200 shadow-sm">{{ $ep->documents->count() }}
                                        Dokumen</span>
                                </div>

                                @if ($canUploadCategory)
                                    <button wire:click.stop="create({{ $ep->id }})"
                                        class="bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-bold py-1.5 px-3 rounded shadow-sm transition flex items-center border border-emerald-300">
                                        <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i> Unggah File
                                    </button>
                                @endif
                            </div>

                            <div x-show="openEp" class="mt-4 pt-4 border-t border-gray-200" style="display: none;">
                                @if ($ep->documents->count() > 0)
                                    @foreach ($ep->documents->groupBy('sub_point')->sortKeys() as $subPoint => $docs)
                                        <div class="mb-5 last:mb-0">
                                            <h5
                                                class="text-sm font-bold text-gray-700 mb-3 border-l-2 border-emerald-500 pl-2 bg-white inline-block pr-3 py-1 rounded-r shadow-sm">
                                                Poin {{ $subPoint ?: '-' }}</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach ($docs as $doc)
                                                    <div
                                                        class="bg-white p-3 rounded border border-gray-200 shadow-sm flex flex-col hover:border-emerald-300 transition group relative">
                                                        <div
                                                            class="absolute top-2 right-2 flex space-x-1 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition">
                                                            @if ($canUploadCategory)
                                                                <button wire:click="edit({{ $doc->id }})"
                                                                    class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 w-6 h-6 rounded flex items-center justify-center transition"
                                                                    title="Edit"><i
                                                                        class="fa-solid fa-pen text-[10px]"></i></button>
                                                                <button onclick="confirmDeleteDoc({{ $doc->id }})"
                                                                    class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 w-6 h-6 rounded flex items-center justify-center transition"
                                                                    title="Hapus"><i
                                                                        class="fa-solid fa-trash text-[10px]"></i></button>
                                                            @endif
                                                        </div>

                                                        <div class="flex justify-between items-start mb-2 pr-12">
                                                            <div class="font-semibold text-gray-800 text-sm line-clamp-2"
                                                                title="{{ $doc->title }}">{{ $doc->title }}</div>
                                                        </div>
                                                        <div class="text-[11px] text-gray-500 mb-3 flex items-center">
                                                            <i class="fa-solid fa-file mr-1.5 text-gray-400"></i>
                                                            <span class="truncate">{{ $doc->file_name }}</span>
                                                        </div>
                                                        <div
                                                            class="mt-auto pt-2 border-t border-gray-100 flex justify-between items-center">
                                                            <div class="text-[10px] text-gray-400">
                                                                Oleh: {{ $doc->uploader?->name ?? 'Sistem' }}
                                                            </div>
                                                            <div class="flex space-x-1 items-center">
                                                                <button wire:click="preview({{ $doc->id }})"
                                                                    class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 w-7 h-7 rounded flex items-center justify-center transition"
                                                                    title="Pratinjau"><i
                                                                        class="fa-solid fa-eye text-[11px]"></i></button>
                                                                <a href="{{ route('siparsi.documents.download', $doc->id) }}"
                                                                    class="text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 w-7 h-7 rounded flex items-center justify-center transition"
                                                                    title="Unduh"><i
                                                                        class="fa-solid fa-download text-[11px]"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p
                                        class="text-sm text-gray-500 italic py-3 text-center border border-dashed border-gray-300 rounded bg-white">
                                        Belum ada dokumen yang diunggah untuk EP ini.</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fa-regular fa-folder-open text-5xl mb-4 text-gray-200"></i>
                            <p class="text-gray-500 font-medium text-sm">Tidak ada Elemen Penilaian pada Standar ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-gray-400">
                    <div
                        class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-sm">
                        <i class="fa-solid fa-hand-pointer text-3xl text-gray-300"></i>
                    </div>
                    <p class="font-bold text-lg text-gray-500">Pilih Standar di Kiri</p>
                    <p class="text-sm mt-1 max-w-sm mx-auto">Klik salah satu standar di menu navigasi sebelah kiri untuk
                        melihat daftar Elemen Penilaian dan mengelola dokumennya.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Upload/Edit Modal -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-auto overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $documentId ? 'Edit Dokumen' : 'Unggah Dokumen SIPARSI' }}</h3>
                    <button wire:click="$set('isModalOpen', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="bg-blue-50 border border-blue-100 p-3 rounded-md mb-4">
                            <span class="text-xs text-blue-500 font-bold uppercase tracking-wider block mb-1">Target
                                Penilaian</span>
                            <span
                                class="text-sm font-semibold text-blue-900">{{ $assessment_element_name ?? 'Pilih EP...' }}</span>
                            <!-- Hidden input to still bind the ID -->
                            <input type="hidden" wire:model="assessment_element_id">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sub Poin (A, B, C, dst)</label>
                            <input type="text" wire:model.live="sub_point" placeholder="Misal: A"
                                style="text-transform: uppercase;"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 uppercase">
                            @error('sub_point')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
                            <input type="text" wire:model="title"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Contoh: SK Direktur tentang Pelayanan">
                            @error('title')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File (Maks: 20MB)</label>
                            <input type="file" wire:model="file"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            @if ($documentId)
                                <p class="text-[10px] text-gray-400 mt-1">*Biarkan kosong jika tidak ingin mengubah
                                    file dokumen lama.</p>
                            @endif
                            <div wire:loading wire:target="file" class="text-xs text-emerald-500 mt-1"><i
                                    class="fa-solid fa-spinner fa-spin mr-1"></i> Mengunggah file...</div>
                            @error('file')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('isModalOpen', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-md shadow-sm hover:bg-emerald-700">Simpan
                            Dokumen</button>
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
                        <p class="text-xs text-emerald-600 font-bold mt-1">{{ $previewDoc->assessmentElement?->name }}
                            &bull; <span
                                class="text-gray-500 font-normal font-mono">{{ $previewDoc->file_name }}</span></p>
                    </div>
                    <button wire:click="closePreviewModal"
                        class="text-gray-400 hover:text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="flex-grow p-0 bg-gray-100 overflow-hidden relative" style="min-height: 60vh;">
                    @if (in_array(strtolower(pathinfo($previewDoc->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                        <div class="w-full h-full flex items-center justify-center p-4 overflow-auto">
                            <img src="{{ route('siparsi.documents.view', $previewDoc->id) }}"
                                alt="{{ $previewDoc->title }}"
                                class="max-w-full max-h-full object-contain shadow-sm border border-gray-200">
                        </div>
                    @elseif(strtolower(pathinfo($previewDoc->file_name, PATHINFO_EXTENSION)) === 'pdf')
                        <iframe src="{{ route('siparsi.documents.view', $previewDoc->id) }}"
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
                            <a href="{{ route('siparsi.documents.download', $previewDoc->id) }}"
                                class="mt-6 inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">
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
                    confirmButtonColor: '#10b981',
                });
            });
        });

        function confirmDeleteDoc(id) {
            Swal.fire({
                title: 'Hapus Dokumen?',
                text: "File fisik dokumen akreditasi ini akan dihapus permanen!",
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
