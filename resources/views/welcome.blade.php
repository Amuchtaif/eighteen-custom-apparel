<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EIGHTEEN - Pakaian Kustom & Portal ERP</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Plus Jakarta Sans & JetBrains Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E90FF',
                        primaryDark: '#0070e0',
                        brandBlue: '#1E90FF',
                        brandGreen: '#00880d',
                        textDark: '#2d2e34',
                        textMuted: '#8e919b',
                        bgLight: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        html {
            scroll-behavior: smooth;
        }
        section {
            scroll-margin-top: 85px;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#2d2e34] font-sans overflow-x-hidden selection:bg-[#1E90FF]/10 selection:text-[#1E90FF]">

    <!-- Header / Navbar -->
    <header id="main-header" class="sticky top-0 z-40 w-full transition-all duration-300 bg-transparent">
        <nav id="nav-container" class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between bg-[#F8FAFC]/60 backdrop-blur-xl transition-all duration-300">
            <div class="flex items-center space-x-3">
                <span class="text-2xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-[#1E90FF] to-[#0070e0]">EIGHTEEN</span>
            </div>
            <div class="hidden md:flex items-center space-x-6 text-xs font-bold text-[#8e919b]" id="nav-links">
                <a href="#" class="hover:text-[#2d2e34] transition-colors text-[#1E90FF]">Beranda</a>
                <a href="#features" class="hover:text-[#2d2e34] transition-colors">Keunggulan</a>
                <a href="#tracker" class="hover:text-[#2d2e34] transition-colors">Lacak Pesanan</a>
                <a href="#pricing" class="hover:text-[#2d2e34] transition-colors">Harga</a>
                <a href="#testimonials" class="hover:text-[#2d2e34] transition-colors">Testimoni</a>
                <a href="#faq" class="hover:text-[#2d2e34] transition-colors">F.A.Q</a>
            </div>
            <div class="flex space-x-2.5">
                <a href="/login" class="bg-[#1E90FF] hover:bg-[#0070e0] text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/10 transition-all">
                    Masuk Staf
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="max-w-7xl mx-auto px-6 pt-16 pb-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Left: Copy & Aksi -->
        <div class="lg:col-span-6 space-y-6">
            <div class="inline-flex items-center space-x-2 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full text-[10px] font-bold text-[#1E90FF]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#1E90FF] animate-pulse"></span>
                <span>Produksi Pakaian Generasi Baru</span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-none text-[#2d2e34]">
                CUSTOM APPAREL<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1E90FF] to-[#0070e0]">TANPA BATAS KREATIVITAS</span>
            </h1>
            <p class="text-[#8e919b] text-sm leading-relaxed max-w-xl">
                Wujudkan desain jersey impian dengan full custom printing, pilihan bahan premium, dan hasil produksi yang detail hingga setiap jahitan.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="/custom-order" class="bg-[#1E90FF] hover:bg-[#0070e0] text-white text-center font-bold px-6 py-3 rounded-xl text-xs shadow-xl shadow-blue-500/15 transition-all transform hover:-translate-y-0.5">
                    Pesan Custom Apparel
                </a>
                <a href="#tracker" class="bg-white hover:bg-gray-50 border border-gray-200 text-[#2d2e34] text-center font-bold px-6 py-3 rounded-xl text-xs shadow-sm transition-all">
                    Lacak Pesanan
                </a>
            </div>
        </div>

        <!-- Right: Graphics (Large Hero Image) -->
        <div class="lg:col-span-6 relative flex justify-center items-center">
            <div class="absolute -inset-10 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 rounded-full blur-3xl -z-10 animate-pulse"></div>
            <img src="{{ asset('assets/images/main-logo.png') }}" alt="EIGHTEEN Logo" class="w-full max-w-lg lg:max-w-xl object-contain drop-shadow-2xl transition-all duration-500 hover:scale-[1.03]">
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="features" class="bg-white border-y border-gray-100 py-16 relative">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2.5">
                <span class="text-[10px] font-bold text-[#1E90FF] uppercase tracking-wider">Fitur Utama</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2d2e34]">Mengapa Memilih EIGHTEEN?</h2>
                <p class="text-[#8e919b] text-xs max-w-xs mx-auto">Kami mengintegrasikan teknologi dan keahlian manufaktur konveksi terbaik untuk memberikan pelayanan maksimal.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-[#F8FAFC] border border-gray-150 hover:border-[#1E90FF]/30 transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#1E90FF] mb-6 group-hover:bg-[#1E90FF] group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-industry text-base"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#2d2e34] mb-2.5">Produksi Presisi & Cepat</h3>
                    <p class="text-[11px] text-[#8e919b] leading-relaxed">Dilengkapi dengan mesin sublimasi modern dan penjahit berpengalaman guna menjamin hasil potongan serta jahitan yang rapi dan awet.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-[#F8FAFC] border border-gray-150 hover:border-[#1E90FF]/30 transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#1E90FF] mb-6 group-hover:bg-[#1E90FF] group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-route text-base"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#2d2e34] mb-2.5">Pelacakan Tahap Real-Time</h3>
                    <p class="text-[11px] text-[#8e919b] leading-relaxed">Melalui fitur Lacak Pesanan, Anda dapat memantau setiap langkah produksi dari tahap desain, pemotongan, penjahitan, hingga pengiriman secara transparan.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-[#F8FAFC] border border-gray-150 hover:border-[#1E90FF]/30 transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#1E90FF] mb-6 group-hover:bg-[#1E90FF] group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-shirt text-base"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#2d2e34] mb-2.5">Kustomisasi Tanpa Batas</h3>
                    <p class="text-[11px] text-[#8e919b] leading-relaxed">Bebas menentukan desain jersey, ukuran custom per pemain, penulisan nama, nomor punggung, serta penambahan logo sponsor.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="cta" class="py-20 relative overflow-hidden">
        <!-- Background Pattern & Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#1E90FF] to-[#0070e0] z-0"></div>
        <div class="absolute inset-0 z-0 opacity-10" style="background-image: radial-gradient(#ffffff 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
        <!-- Decorative Glow -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-white/20 blur-[100px] rounded-full z-0 pointer-events-none"></div>
        
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center space-y-8">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight tracking-tight">
                Siap Mewujudkan<br>Jersey Impian Tim Anda?
            </h2>
            <p class="text-blue-100 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                Bergabung dengan ratusan komunitas dan tim olahraga lainnya. Pesan sekarang dan rasakan kualitas bahan serta sablon sublimasi premium kami.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-4">
                <a href="/custom-order" class="w-full sm:w-auto bg-white hover:bg-blue-50 text-[#1E90FF] text-center font-bold px-8 py-3.5 rounded-xl text-sm shadow-xl shadow-black/10 transition-all transform hover:-translate-y-0.5">
                    Pesan Sekarang
                </a>
                <a href="/portal/login" class="w-full sm:w-auto bg-transparent border border-white/30 hover:bg-white/10 text-white text-center font-bold px-8 py-3.5 rounded-xl text-sm transition-all">
                    Masuk Portal
                </a>
            </div>
        </div>
    </section>

    <!-- Tracking Section -->
    <section id="tracker" class="bg-[#F3F4F6] border-b border-gray-200 py-20 relative">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2.5">
                <span class="text-[10px] font-bold text-[#1E90FF] uppercase tracking-wider">Sistem Pelacakan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2d2e34]">Lacak Produksi Instan</h2>
                <p class="text-[#8e919b] text-xs max-w-xs mx-auto">Masukkan nomor pesanan Anda untuk memantau proses produksi secara real-time dari konveksi kami.</p>
            </div>
            
            <!-- Alpine.js Order Tracker -->
            <div x-data="{
                orderCode: '',
                loading: false,
                errorMsg: '',
                orderData: null,
                stages: [],
                translateStatus(status) {
                    if (!status) return '-';
                    const s = status.toLowerCase();
                    if (s === 'draft') return 'Draf';
                    if (s === 'confirmed') return 'Dikonfirmasi';
                    if (s === 'in production' || s === 'in_production') return 'Dalam Produksi';
                    if (s === 'ready to ship' || s === 'ready_to_ship') return 'Siap Dikirim';
                    if (s === 'completed') return 'Selesai';
                    if (s === 'cancelled') return 'Dibatalkan';
                    return status;
                },
                trackOrder() {
                    if (!this.orderCode.trim()) {
                        this.errorMsg = 'Silakan masukkan nomor pesanan.';
                        this.orderData = null;
                        return;
                    }
                    this.loading = true;
                    this.errorMsg = '';
                    this.orderData = null;
                    fetch(`/track-order?code=${encodeURIComponent(this.orderCode.trim())}`)
                        .then(res => {
                            if (!res.ok) {
                                return res.json().then(data => { throw new Error(data.message || 'Pesanan tidak ditemukan.'); });
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                this.orderData = data.order;
                                this.stages = data.stages;
                            } else {
                                this.errorMsg = data.message || 'Gagal melacak pesanan.';
                            }
                        })
                        .catch(err => {
                            this.errorMsg = err.message || 'Terjadi kesalahan saat melacak pesanan.';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }" class="max-w-4xl mx-auto bg-white border border-[#E2E8F0] rounded-3xl p-6 md:p-10 shadow-xl relative">
                <!-- Search Form -->
                <form @submit.prevent="trackOrder()" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" x-model="orderCode" placeholder="Masukkan Nomor Pesanan (mis. ORD-2026-0001)" 
                               class="w-full bg-[#F8FAFC] border border-gray-300 focus:border-[#1E90FF] focus:ring-1 focus:ring-[#1E90FF] rounded-2xl py-3.5 pl-11 pr-4 text-[#2d2e34] placeholder-slate-400 text-xs font-semibold transition-all">
                    </div>
                    <button type="submit" :disabled="loading" 
                            class="bg-[#1E90FF] hover:bg-[#0070e0] disabled:bg-blue-800 text-white font-bold px-6 py-3.5 rounded-2xl shadow-lg shadow-blue-500/10 transition-all flex items-center justify-center space-x-2 text-xs">
                        <template x-if="loading">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Mencari...' : 'Lacak Pesanan'"></span>
                    </button>
                </form>

                <!-- Error State -->
                <div x-show="errorMsg" x-cloak class="mt-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-xs flex items-center space-x-2">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span x-text="errorMsg"></span>
                </div>

                <!-- Order Results -->
                <div x-show="orderData" x-cloak class="mt-10 space-y-8 animate-fade-in-up">
                    <!-- Summary Card -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-6 bg-[#F8FAFC] border border-gray-200 rounded-2xl">
                        <div>
                            <div class="text-[9px] text-[#8e919b] uppercase tracking-wider font-extrabold">Nomor Pesanan</div>
                            <div class="text-xs font-black text-[#2d2e34] mt-1" x-text="orderData?.order_number"></div>
                        </div>
                        <div>
                            <div class="text-[9px] text-[#8e919b] uppercase tracking-wider font-extrabold">Status Saat Ini</div>
                            <div class="text-xs font-black text-[#1E90FF] mt-1 flex items-center space-x-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#1E90FF] animate-pulse"></span>
                                <span x-text="translateStatus(orderData?.status)"></span>
                            </div>
                        </div>
                        <div>
                            <div class="text-[9px] text-[#8e919b] uppercase tracking-wider font-extrabold">Tanggal Pesanan</div>
                            <div class="text-xs font-black text-[#2d2e34] mt-1" x-text="orderData?.date"></div>
                        </div>
                        <div>
                            <div class="text-[9px] text-[#8e919b] uppercase tracking-wider font-extrabold">Estimasi Tenggat Waktu</div>
                            <div class="text-xs font-black text-[#2d2e34] mt-1" x-text="orderData?.deadline"></div>
                        </div>
                    </div>

                    <!-- Horizontal Timeline -->
                    <div class="space-y-6 pt-4">
                        <h3 class="text-[10px] font-extrabold text-[#8e919b] uppercase tracking-wider">Garis Waktu Produksi</h3>
                        <div class="relative">
                            <!-- Track line -->
                            <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-200 -z-10"></div>
                            
                            <!-- Stages Row -->
                            <div class="grid grid-cols-2 sm:grid-cols-5 md:grid-cols-10 gap-y-8 gap-x-2">
                                <template x-for="(stage, index) in stages" :key="index">
                                    <div class="flex flex-col items-center text-center px-1">
                                        <!-- Node -->
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 transition-all duration-300"
                                             :class="{
                                                 'bg-[#00880d] border-[#00660a] text-white shadow-md shadow-emerald-500/10': stage.status === 'completed',
                                                 'bg-[#1E90FF] border-[#0070e0] text-white shadow-md shadow-blue-500/10 animate-pulse': stage.status === 'active',
                                                 'bg-[#F8FAFC] border-gray-300 text-[#8e919b]': stage.status === 'pending'
                                             }">
                                            <!-- Icon / Number -->
                                            <template x-if="stage.status === 'completed'">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </template>
                                            <template x-if="stage.status !== 'completed'">
                                                <span class="text-[10px] font-black" x-text="index + 1"></span>
                                            </template>
                                        </div>
                                        <!-- Title -->
                                        <span class="text-[10px] font-bold mt-2 truncate w-full"
                                              :class="{
                                                  'text-[#2d2e34]': stage.status === 'completed' || stage.status === 'active',
                                                  'text-[#8e919b]': stage.status === 'pending'
                                              }"
                                              x-text="stage.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="bg-white border-y border-gray-100 py-16 relative">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2.5">
                <span class="text-[10px] font-bold text-[#1E90FF] uppercase tracking-wider">Paket Layanan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2d2e34]">Harga Transparan & Fleksibel</h2>
                <p class="text-[#8e919b] text-xs max-w-xs mx-auto">Pilih paket pemesanan yang sesuai dengan skala tim atau komunitas Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <!-- Starter Tier -->
                <div class="p-8 rounded-2xl bg-[#F8FAFC] border border-gray-200 hover:border-[#1E90FF]/30 transition-all duration-300 flex flex-col h-full">
                    <h3 class="text-lg font-bold text-[#2d2e34]">Starter</h3>
                    <p class="text-[11px] text-[#8e919b] mt-2 mb-6">Cocok untuk tim kecil dan uji coba.</p>
                    <div class="text-3xl font-extrabold text-[#2d2e34] mb-6 flex items-end">
                        <span class="text-sm text-[#8e919b] mb-1 mr-1">Rp</span> 150k
                        <span class="text-[10px] font-normal text-[#8e919b] ml-1 mb-1.5">/ pcs</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-[11px] font-medium text-[#2d2e34]">
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Minimal pesanan 12 pcs</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Desain template standar</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Bahan Dryfit Milano</li>
                        <li class="flex items-start opacity-50"><i class="fa-solid fa-xmark mt-0.5 mr-3 text-red-400"></i> <span class="line-through">Custom nama per pemain</span></li>
                    </ul>
                    <a href="/custom-order" class="block w-full text-center bg-white border border-gray-200 hover:bg-gray-50 text-[#2d2e34] font-bold py-3 rounded-xl text-xs transition-colors">Pilih Starter</a>
                </div>

                <!-- Pro Tier (Highlighted) -->
                <div class="p-8 rounded-2xl bg-white border-2 border-[#1E90FF] shadow-2xl shadow-blue-500/10 relative transform md:-translate-y-4 transition-all duration-300 flex flex-col h-full z-10">
                    <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 bg-[#1E90FF] text-white text-[9px] font-black uppercase tracking-widest py-1 px-3 rounded-full">Rekomendasi</div>
                    <h3 class="text-lg font-bold text-[#2d2e34]">Pro</h3>
                    <p class="text-[11px] text-[#8e919b] mt-2 mb-6">Pilihan paling populer untuk tim profesional.</p>
                    <div class="text-3xl font-extrabold text-[#2d2e34] mb-6 flex items-end">
                        <span class="text-sm text-[#8e919b] mb-1 mr-1">Rp</span> 185k
                        <span class="text-[10px] font-normal text-[#8e919b] ml-1 mb-1.5">/ pcs</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-[11px] font-medium text-[#2d2e34]">
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Minimal pesanan 24 pcs</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Gratis desain custom 1 opsi</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Bahan Premium Anti-UV</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Custom nama & nomor per pemain</li>
                    </ul>
                    <a href="/custom-order" class="block w-full text-center bg-[#1E90FF] hover:bg-[#0070e0] text-white font-bold py-3 rounded-xl text-xs shadow-lg shadow-blue-500/10 transition-colors">Pilih Pro</a>
                </div>

                <!-- Business Tier -->
                <div class="p-8 rounded-2xl bg-[#F8FAFC] border border-gray-200 hover:border-[#1E90FF]/30 transition-all duration-300 flex flex-col h-full">
                    <h3 class="text-lg font-bold text-[#2d2e34]">Business</h3>
                    <p class="text-[11px] text-[#8e919b] mt-2 mb-6">Kapasitas besar untuk akademi dan korporasi.</p>
                    <div class="text-3xl font-extrabold text-[#2d2e34] mb-6 flex items-end">
                        <span class="text-sm text-[#8e919b] mb-1 mr-1">Rp</span> 140k
                        <span class="text-[10px] font-normal text-[#8e919b] ml-1 mb-1.5">/ pcs</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-[11px] font-medium text-[#2d2e34]">
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Minimal pesanan 100 pcs</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Custom desain tanpa batas revisi</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Akses prioritas produksi</li>
                        <li class="flex items-start"><i class="fa-solid fa-check text-[#1E90FF] mt-0.5 mr-3"></i> Gratis ongkos kirim se-Indonesia</li>
                    </ul>
                    <a href="https://wa.me/6281234567890" target="_blank" class="block w-full text-center bg-white border border-gray-200 hover:bg-gray-50 text-[#2d2e34] font-bold py-3 rounded-xl text-xs transition-colors">Hubungi Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section id="testimonials" class="bg-white py-16 relative">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2.5">
                <span class="text-[10px] font-bold text-[#1E90FF] uppercase tracking-wider">Testimoni Klien</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2d2e34]">Apa Kata Pelanggan Kami?</h2>
                <p class="text-[#8e919b] text-xs max-w-xs mx-auto">Telah dipercaya oleh berbagai komunitas olahraga, tim e-sports, dan korporasi di seluruh Indonesia.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="p-6 rounded-2xl bg-[#F8FAFC] border border-gray-150 flex flex-col justify-between space-y-6">
                    <div class="space-y-3">
                        <div class="flex text-amber-400">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                        </div>
                        <p class="text-[11px] text-[#2d2e34] italic leading-relaxed">"Sangat puas dengan kualitas cetakan warna jersey-nya, persis seperti mockup desain 3D yang kami unggah. Fitur pelacakan produksinya sangat membantu kami untuk memantau progres pengerjaan konveksi."</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-[#1E90FF] text-[10px] uppercase flex-shrink-0">
                            AP
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-[#2d2e34]">Andi Permana</div>
                            <div class="text-[9px] text-[#8e919b] font-medium">Kapten FC Garuda</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="p-6 rounded-2xl bg-[#F8FAFC] border border-gray-150 flex flex-col justify-between space-y-6">
                    <div class="space-y-3">
                        <div class="flex text-amber-400">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                        </div>
                        <p class="text-[11px] text-[#2d2e34] italic leading-relaxed">"Proses pembuatan roster seragam tim e-sports kami berjalan tanpa kendala. Data nama, nomor, dan ukuran per pemain langsung kami isi di portal, dan hasilnya 100% akurat tanpa ada kesalahan cetak."</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-[#1E90FF] text-[10px] uppercase flex-shrink-0">
                            RD
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-[#2d2e34]">Rian Dewantara</div>
                            <div class="text-[9px] text-[#8e919b] font-medium">Manajer Apex Esports</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="p-6 rounded-2xl bg-[#F8FAFC] border border-gray-150 flex flex-col justify-between space-y-6">
                    <div class="space-y-3">
                        <div class="flex text-amber-400">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                            <i class="fa-solid fa-star text-[10px] ml-0.5"></i>
                        </div>
                        <p class="text-[11px] text-[#2d2e34] italic leading-relaxed">"ERP portalnya sangat profesional. Faktur tagihan langsung terbit dan konfirmasi unggah bukti transfer berjalan otomatis. Sangat direkomendasikan bagi korporasi yang membutuhkan akuntabilitas administrasi."</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-[#1E90FF] text-[10px] uppercase flex-shrink-0">
                            SH
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-[#2d2e34]">Siti Halimah</div>
                            <div class="text-[9px] text-[#8e919b] font-medium">Procurement Bank Central</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="bg-[#F3F4F6] border-y border-gray-150 py-16 relative">
        <div class="max-w-4xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2.5">
                <span class="text-[10px] font-bold text-[#1E90FF] uppercase tracking-wider">Pertanyaan Umum</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2d2e34]">F.A.Q</h2>
                <p class="text-[#8e919b] text-xs max-w-xs mx-auto">Beberapa jawaban untuk pertanyaan yang paling sering diajukan mengenai sistem produksi kami.</p>
            </div>
            
            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="p-6 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2">
                    <h4 class="font-bold text-xs text-[#2d2e34]">Bagaimana cara melacak pesanan pakaian saya?</h4>
                    <p class="text-[11px] text-[#8e919b] leading-relaxed">Cukup masukkan nomor pesanan Anda (misalnya ORD-2026-0001) ke dalam kolom Pelacakan Instan di atas untuk melihat status rincian produksi secara langsung.</p>
                </div>

                <!-- FAQ 2 -->
                <div class="p-6 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2">
                    <h4 class="font-bold text-xs text-[#2d2e34]">Berapa lama estimasi waktu produksi jersey custom?</h4>
                    <p class="text-[11px] text-[#8e919b] leading-relaxed">Rata-rata waktu pengerjaan produksi jersey berkisar antara 7 hingga 14 hari kerja setelah desain disetujui dan DP (Down Payment) dikonfirmasi.</p>
                </div>

                <!-- FAQ 3 -->
                <div class="p-6 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2">
                    <h4 class="font-bold text-xs text-[#2d2e34]">Bagaimana saya mengirim bukti pembayaran DP / Pelunasan?</h4>
                    <p class="text-[11px] text-[#8e919b] leading-relaxed">Klien dapat mengirimkan bukti pembayaran via WhatsApp atau Email dengan menyertakan Nomor Pesanan.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-6 py-12 flex flex-col sm:flex-row justify-between items-center text-[11px] text-[#8e919b] gap-4">
        <div>&copy; 2026 EIGHTEEN Custom Apparel. Hak Cipta Dilindungi Undang-Undang.</div>
        <div class="flex space-x-6 font-bold">
            <a href="#" class="hover:text-[#2d2e34] transition-colors">Kebijakan Privasi</a>
            <a href="#" class="hover:text-[#2d2e34] transition-colors">Syarat & Ketentuan</a>
        </div>
    </footer>

    <!-- Header Scroll Script for Full Width Transition & Scrollspy -->
    <script>
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            const nav = document.getElementById('nav-container');
            if (window.scrollY > 10) {
                // Scrolled layout: w-full background
                header.classList.remove('bg-transparent');
                header.classList.add('bg-[#F8FAFC]/85', 'backdrop-blur-xl', 'border-b', 'border-gray-200/50', 'shadow-sm');
                
                nav.classList.remove('max-w-7xl', 'py-5', 'bg-[#F8FAFC]/60', 'backdrop-blur-xl');
                nav.classList.add('w-full', 'py-3.5', 'max-w-none', 'bg-transparent');
            } else {
                // Top layout: boxed centered
                header.classList.add('bg-transparent');
                header.classList.remove('bg-[#F8FAFC]/85', 'backdrop-blur-xl', 'border-b', 'border-gray-200/50', 'shadow-sm');
                
                nav.classList.add('max-w-7xl', 'py-5', 'bg-[#F8FAFC]/60', 'backdrop-blur-xl');
                nav.classList.remove('w-full', 'py-3.5', 'max-w-none', 'bg-transparent');
            }
        });

        // Scrollspy Functionality
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('#nav-links a');

        window.addEventListener('scroll', () => {
            let current = 'hero';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= (sectionTop - 120)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-[#1E90FF]');
                link.classList.add('text-[#8e919b]');
                
                const href = link.getAttribute('href');
                if (href === '#' && current === 'hero') {
                    link.classList.remove('text-[#8e919b]');
                    link.classList.add('text-[#1E90FF]');
                } else if (href === '#' + current) {
                    link.classList.remove('text-[#8e919b]');
                    link.classList.add('text-[#1E90FF]');
                }
            });
        });
    </script>
</body>
</html>
