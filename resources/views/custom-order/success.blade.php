<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil Dikirim - EIGHTEEN</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Plus Jakarta Sans Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                        textDark: '#2d2e34',
                        textMuted: '#8e919b',
                        bgLight: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#2d2e34] font-sans min-h-screen flex items-center justify-center p-4 selection:bg-[#1E90FF]/10 selection:text-[#1E90FF]"
      x-data="{
          copied: false,
          copyToClipboard() {
              navigator.clipboard.writeText('{{ $orderCode }}');
              this.copied = true;
              setTimeout(() => this.copied = false, 2000);
          }
      }">

    <div class="max-w-md w-full bg-white border border-[#E2E8F0] rounded-3xl p-8 md:p-10 shadow-xl text-center space-y-6 relative overflow-hidden">
        <!-- Glow decoration -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#1E90FF]/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Success Icon -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-500 mx-auto">
            <svg class="h-8 w-8 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <!-- Copy -->
        <div class="space-y-2">
            <h1 class="text-2xl font-black text-slate-800">Terima Kasih!</h1>
            <p class="text-sm text-slate-500 leading-relaxed">
                Formulir pemesanan custom apparel Anda telah kami terima dan sedang menunggu review oleh tim admin kami.
            </p>
        </div>

        <!-- Order Code Card -->
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Nomor Pesanan Anda</span>
            <div class="flex items-center justify-center space-x-2">
                <span class="text-xl font-black text-[#1E90FF] font-mono tracking-wide" id="order-code">{{ $orderCode }}</span>
                <button @click="copyToClipboard()" type="button" class="text-slate-400 hover:text-[#1E90FF] transition-colors p-1" title="Salin nomor pesanan">
                    <template x-if="!copied">
                        <i class="fa-regular fa-copy text-sm"></i>
                    </template>
                    <template x-if="copied">
                        <i class="fa-solid fa-check text-sm text-emerald-500"></i>
                    </template>
                </button>
            </div>
            <span x-show="copied" x-cloak class="text-[10px] font-bold text-emerald-600 block animate-pulse">Berhasil disalin!</span>
            
            <p class="text-[11px] text-amber-600 font-medium bg-amber-50 border border-amber-100 rounded-xl p-2.5 mt-2 leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1"></i>
                PENTING: Silakan salin, catat, atau ambil tangkapan layar (screenshot) nomor pesanan ini untuk melakukan pelacakan secara real-time.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3 pt-2">
            <a href="{{ $waUrl }}" target="_blank"
               class="w-full inline-flex items-center justify-center gap-2.5 bg-[#25D366] hover:bg-[#20ba59] text-white font-bold py-3.5 px-6 rounded-2xl text-sm shadow-lg shadow-emerald-500/10 transition-all transform hover:-translate-y-0.5">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                Hubungi Admin WhatsApp
            </a>
            
            <a href="/"
               class="w-full inline-flex items-center justify-center bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold py-3.5 px-6 rounded-2xl text-sm transition-all shadow-sm">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
