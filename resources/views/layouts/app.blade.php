<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Kuitansi BOS</title>
    <link rel="icon" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIiBmaWxsPSJub25lIj4KICA8bWFzayBpZD0icmVjZWlwdC1tYXNrIj4KICAgIDxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJ3aGl0ZSIgLz4KICAgIDxjaXJjbGUgY3g9Ijc4IiBjeT0iNzUiIHI9IjI0IiBmaWxsPSJibGFjayIgLz4KICA8L21hc2s+CgogIDxnIG1hc2s9InVybCgjcmVjZWlwdC1tYXNrKSI+CiAgICA8cGF0aCBkPSJNNjAgMTAgSDIwIEMxNCAxMCAxMCAxNCAxMCAyMCBWOTAgTDIyLjUgNzggTDM1IDkwIEw0Ny41IDc4IEw2MCA5MCBMNzIuNSA3OCBMODUgOTAgVjM1IiBzdHJva2U9IiMyNTYzZWIiIHN0cm9rZS13aWR0aD0iOCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiAvPgogICAgPHBhdGggZD0iTTYwIDEwIFYyNyBDNjAgMzIgNjMgMzUgNjggMzUgSDg1IiBzdHJva2U9IiMyNTYzZWIiIHN0cm9rZS13aWR0aD0iOCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiAvPgogICAgPHBhdGggZD0iTTYwIDEwIEw4NSAzNSBINjggQzYzIDM1IDYwIDMyIDYwIDI3IFoiIGZpbGw9IiMyNTYzZWIiIC8+CiAgICAKICAgIDxwYXRoIGQ9Ik0gMjUgMjggSCAzNSBDIDQwIDI4IDQzIDMwIDQzIDM0IEMgNDMgMzggNDAgNDAgMzUgNDEgTCA0MiA1MSBIIDM2IEwgMzEgNDEgSCAyOCBWIDUxIEggMjMgViAyOCBaIE0gMjggMzIgViAzNyBIIDMzIEMgMzUgMzcgMzcgMzYgMzcgMzQgQyAzNyAzMiAzNSAzMiAzMyAzMiBIIDI4IFoiIGZpbGw9IiMyNTYzZWIiIC8+CiAgICA8cGF0aCBkPSJNIDQ1IDM2IEggNTAgViAzOCBDIDUxIDM3IDUzIDM2IDU2IDM2IEMgNjEgMzYgNjQgMzkgNjQgNDQgQyA2NCA0OSA2MSA1MiA1NiA1MiBDIDUzIDUyIDUxIDUxIDUwIDUwIFYgNTggSCA0NSBWIDM2IFogTSA1MCA0MyBDIDUwIDQ2IDUyIDQ4IDU1IDQ4IEMgNTcgNDggNTkgNDYgNTkgNDMgQyA1OSA0MCA1NyAzOCA1NSAzOCBDIDUyIDM4IDUwIDQwIDUwIDQzIFoiIGZpbGw9IiMyNTYzZWIiIC8+CgogICAgPGxpbmUgeDE9IjIzIiB5MT0iNjMiIHgyPSI1MyIgeTI9IjYzIiBzdHJva2U9IiMyNTYzZWIiIHN0cm9rZS13aWR0aD0iNiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiAvPgogICAgPGxpbmUgeDE9IjIzIiB5MT0iNzUiIHgyPSI0MCIgeTI9Ijc1IiBzdHJva2U9IiMyNTYzZWIiIHN0cm9rZS13aWR0aD0iNiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiAvPgogIDwvZz4KCiAgPG1hc2sgaWQ9ImNoZWNrLW1hc2siPgogICAgPGNpcmNsZSBjeD0iNzgiIGN5PSI3NSIgcj0iMjAiIGZpbGw9IndoaXRlIiAvPgogICAgPHBhdGggZD0iTTY4LDc2IEw3NSw4MyBMODgsNjUiIHN0cm9rZT0iYmxhY2siIHN0cm9rZS13aWR0aD0iNiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBmaWxsPSJub25lIiAvPgogIDwvbWFzaz4KICA8Y2lyY2xlIGN4PSI3OCIgY3k9Ijc1IiByPSIyMCIgZmlsbD0iIzI1NjNlYiIgbWFzaz0idXJsKCNjaGVjay1tYXNrKSIgLz4KPC9zdmc+Cg==" type="image/svg+xml">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 antialiased font-sans">
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
        <header class="mb-4 border-b border-gray-700 pb-4 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Kuitansi BOS</h1>
                <p class="text-sm text-gray-400">Sistem Pembuatan Kuitansi otomatis</p>
            </div>
            @auth
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-red-400 hover:text-red-300 hover:underline">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </header>

        <main>
            @yield('content')
        </main>
        <footer class="w-full text-center py-4 text-xs text-gray-400">
            <p>© {{ date('Y') }} Kuitansi BOS | Iphul Lamaau 🍵</p>
        </footer>
    </div>
</body>

</html>