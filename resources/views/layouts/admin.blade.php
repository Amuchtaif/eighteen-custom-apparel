@if (auth()->check() && !in_array(auth()->user()->role->name ?? '', ['Super Admin', 'Owner']))
    @include('layouts.employee-mobile')
@else
<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ERP Eighteen')</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Plus Jakarta Sans Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                            primary: '#E12015',
                            primaryDark: '#c91a10',
                            bgDark: '#F3F4F6',
                            bgLight: '#ffffff',
                            borderGray: '#E2E8F0',
                            'gofood-black': '#F3F4F6',
                            'gofood-green': '#00880d',
                            'gofood-red': '#E12015',
                            'gofood-gray-bg': '#ffffff',
                            'gofood-white': '#ffffff',
                            'gofood-text-dark': '#2d2e34',
                            'gofood-text-muted': '#8e919b',
                            'gofood-text-navy': '#1b2842',
                        },
                        fontFamily: {
                            sans: ['Maison Neue', 'Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        },
                        boxShadow: {
                            'gofood-shadow': 'rgba(0, 0, 0, 0.05) 0px 1px 3px 0px, rgba(0, 0, 0, 0.05) 0px 1px 2px 0px',
                        }
                    }
                }
            }
        </script>
    @endif

    <!-- CRUD Helpers JS -->
    <script src="{{ asset('js/crud-helpers.js') }}"></script>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminLayout', () => ({
                sidebarOpen: false,
                deleteModalOpen: false,
                deleteActionUrl: '',
                confirmDelete(url) {
                    this.deleteActionUrl = url;
                    this.deleteModalOpen = true;
                },
                toasts: [],
                addToast(message, type = 'success') {
                    const id = Date.now() + Math.random().toString(36).substr(2, 9);
                    this.toasts.push({ id, message, type, show: true });
                    setTimeout(() => {
                        this.removeToast(id);
                    }, 5000);
                },
                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].show = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                },
                init() {
                    try {
                        const pending = JSON.parse(sessionStorage.getItem('pending_toasts') || '[]');
                        const now = Date.now();
                        pending.forEach(toast => {
                            if (now - toast.timestamp < 5000) {
                                this.addToast(toast.message, toast.type);
                            }
                        });
                        sessionStorage.removeItem('pending_toasts');
                    } catch (e) {
                        console.error('Error loading pending toasts:', e);
                    }
                }
            }));
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Maison Neue', 'Plus Jakarta Sans', 'Inter', sans-serif;
            color: #2d2e34;
        }
        /* Custom transitions for interactive elements */
        .gofood-transition {
            transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
    @stack('styles')
</head>
<body class="h-full text-[#2d2e34] bg-white" x-data="adminLayout" x-init="
    @if(session('success'))
        addToast('{{ addslashes(session('success')) }}', 'success');
    @endif
    @if(session('error'))
        addToast('{{ addslashes(session('error')) }}', 'error');
    @endif
    @if($errors->any())
        @foreach($errors->all() as $error)
            addToast('{{ addslashes($error) }}', 'error');
        @endforeach
    @endif
">
    <div class="h-screen flex flex-col md:flex-row overflow-hidden">
        
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/80 md:hidden" 
             @click="sidebarOpen = false"
             style="display: none;"></div>

         @include('layouts.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-white">
            <!-- Header Bar (Shared Desktop & Mobile) -->
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white/80 backdrop-blur-md px-6 shadow-sm flex-shrink-0">
                <div class="flex items-center">
                    <!-- Hamburger button for mobile to open sidebar -->
                    <button @click="sidebarOpen = true" class="text-[#2d2e34] hover:text-[#000000] md:hidden mr-4">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <!-- Brand name (mobile only) -->
                    <span class="text-lg font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-[#1E90FF] to-[#0070e0] md:hidden">EIGHTEEN</span>
                </div>
                
                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none group">
                        <span class="text-xs font-bold text-[#2d2e34] hidden sm:block group-hover:text-[#1E90FF] transition-colors">
                            {{ auth()->user()->name ?? 'Pengguna Tamu' }}
                        </span>
                        <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-[#1E90FF] to-[#0070e0] flex items-center justify-center font-bold text-white uppercase shadow-sm border border-white/20 transition-transform group-hover:scale-105">
                            {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
                        </div>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2.5 w-48 origin-top-right rounded-xl bg-white border border-gray-200 py-1.5 shadow-lg ring-1 ring-black/5 focus:outline-none z-50"
                         x-cloak>
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-bold text-[#2d2e34] truncate">{{ auth()->user()->name ?? 'Pengguna Tamu' }}</p>
                            <p class="text-[10px] text-[#8e919b] font-medium truncate mt-0.5">{{ auth()->user()->email ?? 'admin@eighteen.com' }}</p>
                        </div>
                        <a href="/" class="flex items-center px-4 py-2 text-xs font-semibold text-[#8e919b] hover:text-[#2d2e34] hover:bg-gray-50 transition-colors">
                            <i class="fa-solid fa-house mr-2.5 text-[11px]"></i>
                            Halaman Utama
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors text-left">
                                <i class="fa-solid fa-right-from-bracket mr-2.5 text-[11px]"></i>
                                Keluar Panel
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Dashboard Body -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-white">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Page-specific Modals -->
    @stack('modals')

    <!-- Global Delete Confirmation Modal -->
    <div x-show="deleteModalOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <div @click.outside="deleteModalOpen = false"
             class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden transform transition-all border border-gray-100"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="scale-95 translate-y-4 opacity-0"
             x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="scale-100 translate-y-0 opacity-100"
             x-transition:leave-end="scale-95 translate-y-4 opacity-0">
            
            <div class="p-6">
                <div class="flex items-center space-x-3 text-red-600 mb-4">
                    <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Konfirmasi Hapus</h3>
                </div>
                <p class="text-sm text-[#64748B] leading-relaxed">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                <button @click="deleteModalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form :action="deleteActionUrl" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="fixed top-5 right-5 z-[200] flex flex-col space-y-3 w-[90%] max-w-[380px] pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-2 md:translate-y-0 md:translate-x-4 opacity-0"
                 x-transition:enter-end="translate-y-0 md:translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 translate-y-2 md:translate-y-0 md:translate-x-4"
                 :class="{
                     'bg-[#F0FDF4] border-[#DCFCE7] text-[#166534]': toast.type === 'success',
                     'bg-[#FEF2F2] border-[#FEE2E2] text-[#991B1B]': toast.type === 'error',
                     'bg-[#FFFBEB] border-[#FEF3C7] text-[#92400E]': toast.type === 'warning'
                 }"
                 class="pointer-events-auto relative flex items-center pl-4 pr-12 py-3 rounded-full border shadow-[0_4px_16px_rgba(0,0,0,0.06)] transition-all"
                 role="alert">
                
                <!-- Status Icon Circle -->
                <div class="flex-shrink-0 mr-3 flex items-center justify-center w-6 h-6 rounded-full text-white"
                     :class="{
                         'bg-[#10B981]': toast.type === 'success',
                         'bg-[#EF4444]': toast.type === 'error',
                         'bg-[#F59E0B]': toast.type === 'warning'
                     }">
                    <template x-if="toast.type === 'success'">
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <i class="fa-solid fa-exclamation text-[11px] font-extrabold"></i>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <i class="fa-solid fa-exclamation text-[11px] font-extrabold"></i>
                    </template>
                </div>
                
                <!-- Text Message -->
                <div class="flex-1 text-xs font-semibold leading-snug pr-1"
                     :class="{
                         'text-[#15803D]': toast.type === 'success',
                         'text-[#B91C1C]': toast.type === 'error',
                         'text-[#B45309]': toast.type === 'warning'
                     }"
                     x-text="toast.message"></div>
                
                <!-- Close Button -->
                <button @click="removeToast(toast.id)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </template>
    </div>
    @stack('scripts')
</body>
</html>
@endif
