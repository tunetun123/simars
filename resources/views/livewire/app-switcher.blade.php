<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Portal SIMARS</h1>
        <p class="text-gray-500">Pilih aplikasi yang ingin Anda akses.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @php
            $user = auth()->user();
            $hasSigapAccess = $user->can('access sigap');
            $hasAdminAccess = $user->can('access admin');

            $hasSiparsiAccess = $user->can('access siparsi') || $user->can('view siparsi documents');
            if (!$hasSiparsiAccess) {
                foreach ($user->getAllPermissions() as $perm) {
                    if (str_starts_with($perm->name, 'upload siparsi ')) {
                        $hasSiparsiAccess = true;
                        break;
                    }
                }
            }
        @endphp

        <!-- Card Aplikasi Sigap -->
        <div class="relative">
            <a href="{{ $hasSigapAccess ? '/sigap' : '#' }}"
                class="group block bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center {{ $hasSigapAccess ? 'hover:shadow-lg hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-1' : 'opacity-60 cursor-not-allowed' }}">
                <div
                    class="w-16 h-16 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 {{ $hasSigapAccess ? 'group-hover:scale-110 transition-transform duration-300' : '' }} overflow-hidden">
                    @if (file_exists(public_path('logos/sigap_logo.png')))
                        <img src="{{ asset('logos/sigap_logo.png') }}?{{ time() }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-box-archive text-2xl"></i>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Aplikasi SIGAP</h3>
                <p class="text-sm text-gray-500">Sistem Informasi Pengelolaan Arsip Dokumen Digital.</p>
            </a>
            @if (!$hasSigapAccess)
                <div
                    class="absolute inset-0 bg-white/40 rounded-xl flex items-center justify-center z-10 backdrop-blur-[2px]">
                    <div class="bg-gray-800/80 px-4 py-2 rounded-full flex items-center text-white shadow-lg">
                        <i class="fa-solid fa-lock mr-2 text-sm"></i>
                        <span class="text-xs font-bold tracking-wider uppercase">Terkunci</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Card Aplikasi Administrator -->
        <div class="relative">
            <a href="{{ $hasAdminAccess ? '/admin/users' : '#' }}"
                class="group block bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center {{ $hasAdminAccess ? 'hover:shadow-lg hover:border-purple-200 transition-all duration-300 transform hover:-translate-y-1' : 'opacity-60 cursor-not-allowed' }}">
                <div
                    class="w-16 h-16 mx-auto bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-4 {{ $hasAdminAccess ? 'group-hover:scale-110 transition-transform duration-300' : '' }} overflow-hidden">
                    @if (file_exists(public_path('logos/admin_logo.png')))
                        <img src="{{ asset('logos/admin_logo.png') }}?{{ time() }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-users-gear text-2xl"></i>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Administrator</h3>
                <p class="text-sm text-gray-500">Manajemen pengguna, peran, dan pengaturan sistem.</p>
            </a>
            @if (!$hasAdminAccess)
                <div
                    class="absolute inset-0 bg-white/40 rounded-xl flex items-center justify-center z-10 backdrop-blur-[2px]">
                    <div class="bg-gray-800/80 px-4 py-2 rounded-full flex items-center text-white shadow-lg">
                        <i class="fa-solid fa-lock mr-2 text-sm"></i>
                        <span class="text-xs font-bold tracking-wider uppercase">Terkunci</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Card Aplikasi SIPARSI -->
        <div class="relative">
            <a href="{{ $hasSiparsiAccess ? '/siparsi' : '#' }}"
                class="group block bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center {{ $hasSiparsiAccess ? 'hover:shadow-lg hover:border-emerald-200 transition-all duration-300 transform hover:-translate-y-1' : 'opacity-60 cursor-not-allowed' }}">
                <div
                    class="w-16 h-16 mx-auto bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-4 {{ $hasSiparsiAccess ? 'group-hover:scale-110 transition-transform duration-300' : '' }} overflow-hidden">
                    @if (file_exists(public_path('logos/siparsi_logo.png')))
                        <img src="{{ asset('logos/siparsi_logo.png') }}?{{ time() }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-hospital-user text-2xl"></i>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Aplikasi SIPARSI</h3>
                <p class="text-sm text-gray-500">Sistem Pengelolaan Arsip Akreditasi Rumah Sakit.</p>
            </a>
            @if (!$hasSiparsiAccess)
                <div
                    class="absolute inset-0 bg-white/40 rounded-xl flex items-center justify-center z-10 backdrop-blur-[2px]">
                    <div class="bg-gray-800/80 px-4 py-2 rounded-full flex items-center text-white shadow-lg">
                        <i class="fa-solid fa-lock mr-2 text-sm"></i>
                        <span class="text-xs font-bold tracking-wider uppercase">Terkunci</span>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
