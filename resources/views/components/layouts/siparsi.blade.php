<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'SIPARSI' }} - SIMARS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar Siparsi (Tema Emerald) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-md h-full flex flex-col border-r border-gray-100 transition-transform duration-300 md:relative md:translate-x-0">
        <!-- Close button on mobile -->
        <button @click="sidebarOpen = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 md:hidden">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <!-- Brand / Logo SIPARSI -->
        <div class="p-6 border-b border-gray-100 text-center flex flex-col items-center justify-center">
            <div
                class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-3 overflow-hidden border border-emerald-200">
                @if (file_exists(public_path('logos/siparsi_logo.png')))
                    <img src="{{ asset('logos/siparsi_logo.png') }}?{{ time() }}"
                        class="w-full h-full object-cover">
                @else
                    <i class="fa-solid fa-hospital-user text-2xl"></i>
                @endif
            </div>
            <h1 class="text-2xl font-bold text-emerald-700 leading-tight">SIPARSI</h1>
            <p class="text-[10px] text-gray-500 mt-1 leading-tight font-medium uppercase text-center">
                Sistem Pengelolaan<br />Arsip Akreditasi
            </p>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="/apps"
                class="flex items-center px-4 py-2 mb-4 text-gray-500 hover:bg-gray-100 hover:text-gray-800 rounded-md transition font-medium border border-gray-200 bg-gray-50">
                <i class="fa-solid fa-arrow-left w-6"></i> Kembali ke SIMARS
            </a>

            @php
                $user = auth()->user();
                $hasSiparsiAccess = $user->can('access siparsi');
                $hasSiparsiView = $user->can('view siparsi documents');

                if (!$hasSiparsiAccess || !$hasSiparsiView) {
                    foreach ($user->getAllPermissions() as $perm) {
                        if (str_starts_with($perm->name, 'upload siparsi ')) {
                            $hasSiparsiAccess = true;
                            $hasSiparsiView = true;
                            break;
                        }
                    }
                }
            @endphp

            @if ($hasSiparsiAccess)
                <a href="/siparsi"
                    class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('siparsi') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
                </a>
            @endif

            @can('manage siparsi groups')
                <a href="/siparsi/groups"
                    class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('siparsi/groups') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    <i class="fa-solid fa-layer-group w-6"></i> Bab (Groups)
                </a>
            @endcan

            @can('manage siparsi categories')
                <a href="/siparsi/categories"
                    class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('siparsi/categories') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    <i class="fa-solid fa-tags w-6"></i> Standar (Categories)
                </a>
            @endcan

            @can('manage siparsi ep')
                <a href="/siparsi/assessment-elements"
                    class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('siparsi/assessment-elements') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    <i class="fa-solid fa-list-check w-6"></i> Elemen Penilaian
                </a>
            @endcan

            @if ($hasSiparsiView)
                <a href="/siparsi/documents"
                    class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('siparsi/documents') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    <i class="fa-solid fa-folder-open w-6"></i> Arsip Akreditasi
                </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 w-full">
        <!-- Topbar -->
        <header class="bg-white shadow-sm px-4 sm:px-6 py-4 flex items-center justify-between z-10 relative">
            <div class="flex items-center">
                <button @click="sidebarOpen = true"
                    class="text-gray-500 hover:text-gray-800 mr-4 focus:outline-none md:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 truncate">{{ $title ?? 'Page' }}</h2>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center text-gray-600">
                    <span class="mr-2 text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</span>
                    <i class="fa-solid fa-circle-user text-2xl text-emerald-500"></i>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex items-center text-gray-500 hover:text-red-600 transition font-medium text-sm bg-gray-50 hover:bg-red-50 px-3 py-1.5 rounded-md border border-gray-200 hover:border-red-200"
                        title="Logout">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 p-4 sm:p-6 overflow-y-auto w-full">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Akses Ditolak!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#10b981' // Emerald
                });
            });
        </script>
    @endif
</body>

</html>
