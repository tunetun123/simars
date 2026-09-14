<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Card: Total Kategori -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Kategori</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalCategories }}</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full">
                <i class="fa-solid fa-tags text-blue-500 text-2xl"></i>
            </div>
        </div>

        <!-- Card: Total Dokumen -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Dokumen</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalDocuments }}</p>
            </div>
            <div class="p-3 bg-green-50 rounded-full">
                <i class="fa-solid fa-folder-open text-green-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Recent Documents Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-400"></i> Dokumen Terbaru
            </h3>
            <a href="/sigap/documents" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="px-6 py-3 font-medium">Judul Dokumen</th>
                        <th class="px-6 py-3 font-medium">Kategori</th>
                        <th class="px-6 py-3 font-medium">Diunggah Oleh</th>
                        <th class="px-6 py-3 font-medium text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentDocuments as $doc)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $doc->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs">{{ $doc->category?->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $doc->uploader?->name ?? 'Sistem' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 text-right">{{ $doc->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fa-regular fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada dokumen yang diunggah.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
