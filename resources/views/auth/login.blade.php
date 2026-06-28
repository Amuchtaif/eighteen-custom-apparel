<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F3F4F6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Staf & Admin | EIGHTEEN</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Plus Jakarta Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E90FF',
                        primaryDark: '#0070e0',
                        brandBlue: '#1E90FF',
                        textDark: '#0F172A',
                        textMuted: '#64748B',
                        bgLight: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen text-[#0F172A] font-sans antialiased bg-[#F3F4F6] flex items-center justify-center p-4">

    <!-- Centered Login Container Card -->
    <div class="max-w-4xl w-full bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.06)] overflow-hidden grid grid-cols-1 md:grid-cols-2 min-h-[580px]">
        
        <!-- Left Side: Login Form -->
        <div class="flex flex-col justify-between bg-white p-8 sm:p-12">

            <!-- Main Form Wrapper -->
            <div class="w-full py-6">
                <div class="space-y-2 mb-6">
                    <h2 class="text-xl font-extrabold text-[#0F172A] tracking-tight">
                        Portal Staf & Admin
                    </h2>
                    <p class="text-[11px] text-[#64748B] font-medium leading-relaxed">
                        Masuk ke panel operasional untuk mengelola data produksi dan kepegawaian.
                    </p>
                </div>

                <!-- Session Alert & Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-50/70 border border-red-100 text-red-700 p-3.5 rounded-xl text-[10px] space-y-1 mb-4" role="alert">
                        <div class="font-bold flex items-center">
                            <i class="fa-solid fa-circle-exclamation mr-1.5 text-red-500"></i>
                            Gagal Masuk:
                        </div>
                        <ul class="list-disc pl-4 space-y-0.5 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50/70 border border-red-100 text-red-700 p-3.5 rounded-xl text-[10px] flex items-center font-medium mb-4" role="alert">
                        <i class="fa-solid fa-circle-exclamation mr-2 text-red-500 text-sm flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                    @csrf
                    
                    <div class="space-y-3.5">
                        <!-- Email Input -->
                        <div class="space-y-1">
                            <label for="email" class="block text-[10px] font-bold text-[#64748B] uppercase tracking-wider">Alamat Email</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 group-focus-within:text-[#1E90FF] transition-colors">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                </span>
                                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                    placeholder="nama@perusahaan.com"
                                    class="w-full pl-9 pr-4 py-2.5 bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/15 focus:border-[#1E90FF] transition-all hover:border-slate-300">
                            </div>
                        </div>
                        
                        <!-- Password Input -->
                        <div class="space-y-1">
                            <label for="password" class="block text-[10px] font-bold text-[#64748B] uppercase tracking-wider">Kata Sandi</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 group-focus-within:text-[#1E90FF] transition-colors">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </span>
                                <input id="password" name="password" :type="showPass ? 'text' : 'password'" autocomplete="current-password" required
                                    placeholder="••••••••"
                                    class="w-full pl-9 pr-10 py-2.5 bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/15 focus:border-[#1E90FF] transition-all hover:border-slate-300">
                                <button type="button" @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#1E90FF] transition-colors focus:outline-none">
                                    <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center pt-0.5">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-[#1E90FF] focus:ring-[#1E90FF]/20 focus:ring-offset-white">
                            <label for="remember" class="ml-2 block text-xs font-semibold text-[#64748B] cursor-pointer select-none">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full py-2.5 bg-[#1E90FF] hover:bg-[#0070e0] text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/10 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center space-x-2">
                        <span>Masuk ke Panel</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </form>
            </div>

            <!-- Alternative Portal Link and Footer -->
            <div class="space-y-3 pt-4 border-t border-slate-100 text-center">
                <div class="text-[9px] text-[#64748B] font-semibold tracking-wider">
                    &copy; 2026 EIGHTEEN Custom Apparel.
                </div>
            </div>
        </div>
        
        <!-- Right Side: Branded Visual Panel -->
        <div class="bg-[#050C1B] relative flex flex-col items-center justify-center p-12 overflow-hidden select-none">
            <!-- Glow decorative graphics -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-[#1E90FF]/10 rounded-full blur-3xl -z-10 animate-pulse"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#1E90FF_0.5px,transparent_0.5px)] [background-size:28px_28px] opacity-10"></div>

            <div class="flex flex-col items-center space-y-6 relative z-10 w-full">
                <!-- Glowing Logo Container -->
                <div class="h-32 w-32 rounded-[2rem] bg-white/[0.03] border border-white/[0.08] p-6 flex items-center justify-center shadow-2xl backdrop-blur-md">
                    <img src="{{ asset('assets/images/android-chrome-192x192.png') }}" alt="Logo Eighteen" class="h-full w-full object-contain">
                </div>
                <!-- Brand Info -->
                <div class="text-center space-y-1.5">
                    <h1 class="text-2xl font-black tracking-[0.2em] text-white">EIGHTEEN</h1>
                    <p class="text-[10px] uppercase font-extrabold tracking-[0.3em] text-[#1E90FF]">Custom Apparel</p>
                </div>
                
                <!-- Pill tags matching requested image layout layout features -->
                <div class="flex flex-wrap gap-2 justify-center pt-4">
                    <span class="px-3 py-1 rounded-full text-[9px] font-bold text-slate-300 bg-white/5 border border-white/10">Manajemen Produksi</span>
                    <span class="px-3 py-1 rounded-full text-[9px] font-bold text-slate-300 bg-white/5 border border-white/10">Absensi GPS</span>
                    <span class="px-3 py-1 rounded-full text-[9px] font-bold text-slate-300 bg-white/5 border border-white/10">Analisis Penjualan</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
