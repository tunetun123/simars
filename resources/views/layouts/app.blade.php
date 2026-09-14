<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-full flex flex-col">
            <div class="p-6 border-b">
                <h1 class="text-2xl font-bold text-blue-600">
                    <i class="fa-solid fa-layer-group mr-2"></i> Modular App
                </h1>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Sigap Module</div>
                <a href="/sigap" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md transition">
                    <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
                </a>
                <a href="/sigap/categories" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md transition">
                    <i class="fa-solid fa-tags w-6"></i> Kategori
                </a>
                <a href="/sigap/documents" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md transition">
                    <i class="fa-solid fa-folder-open w-6"></i> Arsip Dokumen
                </a>

                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Admin Module</div>
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md transition">
                    <i class="fa-solid fa-users w-6"></i> Users
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md transition">
                    <i class="fa-solid fa-user-shield w-6"></i> Roles
                </a>
            </nav>
            <div class="p-4 border-t">
                <!-- Nanti isi dengan Profil / Logout SSO -->
                <div class="flex items-center text-gray-600">
                    <i class="fa-solid fa-circle-user text-2xl mr-3"></i>
                    <span class="text-sm font-medium">User SSO</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Topbar (Opsional, sekadar spasi/title mobile) -->
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Page' }}</h2>
            </header>
            
            <!-- Page Content -->
            <div class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </div>
        </main>

        @livewireScripts
    </body>
</html>
