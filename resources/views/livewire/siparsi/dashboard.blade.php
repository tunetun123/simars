<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Akreditasi</h2>
        <p class="text-gray-500">Ringkasan data SIPARSI RS Bhayangkara Palu.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Bab</p>
                <h3 class="text-3xl font-bold text-emerald-600">{{ $totalGroups }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500">
                <i class="fa-solid fa-layer-group text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Standar</p>
                <h3 class="text-3xl font-bold text-emerald-600">{{ $totalCategories }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500">
                <i class="fa-solid fa-tags text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total EP</p>
                <h3 class="text-3xl font-bold text-emerald-600">{{ $totalElements }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500">
                <i class="fa-solid fa-list-check text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Dokumen</p>
                <h3 class="text-3xl font-bold text-emerald-600">{{ $totalDocuments }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500">
                <i class="fa-solid fa-folder-open text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Unggahan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="px-6 py-3 font-medium">Judul Dokumen</th>
                        <th class="px-6 py-3 font-medium">Elemen Penilaian</th>
                        <th class="px-6 py-3 font-medium">Skor (Point)</th>
                        <th class="px-6 py-3 font-medium">Pengunggah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentDocuments as $doc)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-800">{{ $doc->title }}</div>
                                <div class="text-xs text-gray-500">{{ $doc->file_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $doc->assessmentElement?->name }}
                                <span
                                    class="block text-xs text-emerald-600">{{ $doc->assessmentElement?->category?->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-blue-600">
                                {{ $doc->point }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $doc->uploader?->name }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada dokumen yang
                                diunggah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
