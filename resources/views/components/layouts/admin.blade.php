<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Admin' }} - SIMARS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar Admin (Tema Ungu/Purple) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-gray-300 shadow-xl h-full flex flex-col transition-transform duration-300 md:relative md:translate-x-0">
        <!-- Close button on mobile -->
        <button @click="sidebarOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-white md:hidden">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <div class="p-6 border-b border-gray-800 bg-gray-950 text-center">
            <div
                class="w-12 h-12 mx-auto bg-purple-900/50 text-purple-500 rounded-full flex items-center justify-center mb-3 overflow-hidden border border-purple-800">
                @if (file_exists(public_path('logos/admin_logo.png')))
                    <img src="{{ asset('logos/admin_logo.png') }}?{{ time() }}"
                        class="w-full h-full object-cover">
                @else
                    <i class="fa-solid fa-users-gear text-2xl"></i>
                @endif
            </div>
            <h1 class="text-xl font-bold text-white uppercase tracking-wider">Administrator</h1>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="/apps"
                class="flex items-center px-4 py-2 mb-4 text-gray-400 hover:bg-gray-800 hover:text-white rounded-md transition font-medium border border-gray-700 bg-gray-800/50">
                <i class="fa-solid fa-arrow-left w-6"></i> Kembali ke SIMARS
            </a>

            <a href="/admin/users"
                class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('admin/users') ? 'bg-purple-600 text-white font-medium' : 'hover:bg-gray-800 hover:text-purple-400' }}">
                <i class="fa-solid fa-users w-6"></i> Manajemen User
            </a>

            <a href="/admin/roles"
                class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('admin/roles') ? 'bg-purple-600 text-white font-medium' : 'hover:bg-gray-800 hover:text-purple-400' }}">
                <i class="fa-solid fa-user-shield w-6"></i> Roles & Akses
            </a>

            <a href="/admin/settings"
                class="flex items-center px-4 py-2 rounded-md transition {{ request()->is('admin/settings') ? 'bg-purple-600 text-white font-medium' : 'hover:bg-gray-800 hover:text-purple-400' }}">
                <i class="fa-solid fa-gear w-6"></i> Pengaturan
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50 w-full">
        <!-- Topbar -->
        <header
            class="bg-white shadow-sm px-4 sm:px-6 py-4 flex items-center justify-between border-b border-gray-200 z-10 relative">
            <div class="flex items-center">
                <button @click="sidebarOpen = true"
                    class="text-gray-500 hover:text-gray-800 mr-4 focus:outline-none md:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 truncate">{{ $title ?? 'Administrator' }}</h2>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center text-gray-600">
                    <span class="mr-2 text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <i class="fa-solid fa-circle-user text-2xl text-purple-500"></i>
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
                    confirmButtonColor: '#9333ea' // Purple
                });
            });
        </script>
    @endif
</body>

</html>
