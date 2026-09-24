<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Portal Aplikasi SIMARS' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100 text-gray-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Topbar Portal -->
    <header class="bg-white shadow-sm border-b border-gray-200 py-4 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/apps" class="text-2xl font-bold text-gray-800 flex items-center">
                @if (file_exists(public_path('logos/simars_logo.png')))
                    <img src="{{ asset('logos/simars_logo.png') }}?{{ time() }}"
                        class="h-8 w-8 object-cover rounded-md mr-3 border border-gray-200">
                @else
                    <i class="fa-solid fa-hospital text-blue-600 mr-3"></i>
                @endif
                SIMARS
            </a>

            <div class="flex items-center space-x-4">
                <div class="flex items-center text-gray-600">
                    <span class="mr-2 text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</span>
                    <i class="fa-solid fa-circle-user text-2xl text-gray-400"></i>
                </div>
                
                <a href="{{ route('change-password') }}" class="flex items-center text-gray-500 hover:text-blue-600 transition font-medium text-sm" title="Ubah Password">
                    <i class="fa-solid fa-key mr-2"></i> Ubah Password
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex items-center text-gray-500 hover:text-red-600 transition font-medium text-sm"
                        title="Logout">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto p-6">
        {{ $slot }}
    </main>

    <!-- Footer Portal -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Modular App System. All rights reserved.
        </div>
    </footer>

    @livewireScripts

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Akses Ditolak!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#3b82f6'
                });
            });
        </script>
    @endif
</body>

</html>
