<!DOCTYPE html>
<html lang="id" class="h-full bg-[#ecf0f6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <title>@yield('title', 'Eighteen - Portal Karyawan')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="description" content="Dasbor karyawan untuk sistem ERP Eighteen Custom Apparel.">

    <!-- Plus Jakarta Sans & JetBrains Mono Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS (Vite / CDN Fallback) -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#00880d',
                            'primary-container': '#000000',
                            secondary: '#00880d',
                            'action-blue': '#3B82F6',
                            'production-green': '#00880d',
                            'warning-amber': '#F59E0B',
                            'error-red': '#EF4444',
                            surface: '#ecf0f6',
                            'surface-card': '#FFFFFF',
                            'border-gray': '#ecf0f6',
                            'on-surface': '#2d2e34',
                            'on-surface-variant': '#8e919b',
                            'outline': '#1b2842',
                            'outline-variant': '#c5c6cd',
                            'gofood-black': '#000000',
                            'gofood-green': '#00880d',
                            'gofood-gray-bg': '#ecf0f6',
                            'gofood-white': '#ffffff',
                            'gofood-text-dark': '#2d2e34',
                            'gofood-text-muted': '#8e919b',
                            'gofood-text-navy': '#1b2842',
                        },
                        fontFamily: {
                            sans: ['Maison Neue', 'Plus Jakarta Sans', 'Inter', 'sans-serif'],
                            mono: ['JetBrains Mono', 'monospace'],
                        },
                        borderRadius: {
                            'card': '12px',
                        }
                    }
                }
            }
        </script>
    @endif

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Maison Neue', 'Plus Jakarta Sans', 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: #2d2e34;
        }
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Smooth entrance animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        .animate-fade-in-up-delay-1 { animation-delay: 0.08s; opacity: 0; }
        .animate-fade-in-up-delay-2 { animation-delay: 0.16s; opacity: 0; }
        .animate-fade-in-up-delay-3 { animation-delay: 0.24s; opacity: 0; }
        .animate-fade-in-up-delay-4 { animation-delay: 0.32s; opacity: 0; }

        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.04); }
        }
        .animate-pulse-soft {
            animation: pulse-soft 2.5s ease-in-out infinite;
        }

        /* Bottom nav safe area for notched phones */
        .pb-safe {
            padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
        }
    </style>
    @stack('styles')
</head>
<body class="h-full text-[#2d2e34] bg-[#ecf0f6]">
    <div class="min-h-full flex flex-col max-w-lg mx-auto relative bg-[#ecf0f6] shadow-xl min-h-screen">

        <!-- Top Header Bar -->
        <header class="flex items-center justify-between px-4 py-3 bg-white border-b border-[#ecf0f6] sticky top-0 z-30">
            <div class="flex items-center space-x-2.5">
                <img src="{{ asset('assets/images/android-chrome-192x192.png') }}" alt="Eighteen Logo" class="h-8 w-8 object-contain">
                <span class="text-sm font-black tracking-tight text-[#000000]">EIGHTEEN</span>
            </div>
            <div class="flex items-center space-x-2">
                <button class="relative h-9 w-9 rounded-full bg-[#ecf0f6] flex items-center justify-center text-[#2d2e34] hover:bg-gray-200 transition-colors">
                    <i class="fa-regular fa-bell text-sm"></i>
                    <span class="absolute -top-0.5 -right-0.5 h-2.5 w-2.5 rounded-full bg-[#EF4444] border-2 border-white"></span>
                </button>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="h-9 w-9 rounded-full bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600 transition-colors" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto hide-scrollbar px-4 pt-4 pb-24">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-[#00880d] border border-green-200 flex items-center">
                    <i class="fa-solid fa-circle-check mr-2 text-[#00880d]"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-800 border border-red-200 flex items-center">
                    <i class="fa-solid fa-circle-xmark mr-2 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Bottom Navigation Bar -->
        <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-[#ecf0f6] shadow-[0_-2px_10px_rgba(0,0,0,0.06)] max-w-lg mx-auto">
            <div class="flex items-center justify-center px-2 pt-2 pb-safe">
                <a href="{{ route('employee.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-1 group -mt-4" id="nav-absensi">
                    <div class="h-12 w-12 flex items-center justify-center rounded-2xl shadow-lg transition-all {{ request()->routeIs('employee.dashboard') ? 'bg-[#0070e0] scale-105 shadow-blue-500/30' : 'bg-[#1E90FF] shadow-blue-950/20 hover:bg-[#0070e0]' }}">
                        <i class="fa-solid fa-fingerprint text-lg text-white"></i>
                    </div>
                    <span class="text-[10px] mt-1 transition-colors {{ request()->routeIs('employee.dashboard') ? 'font-bold text-[#1E90FF]' : 'font-semibold text-[#8e919b] group-hover:text-[#1E90FF]' }}">Absensi</span>
                </a>
            </div>
        </nav>

    </div>
    @stack('scripts')
</body>
</html>
