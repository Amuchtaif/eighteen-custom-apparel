<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Custom Apparel | EIGHTEEN</title>
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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#2d2e34] font-sans antialiased min-h-screen flex flex-col justify-between" x-data="{
    jenisApparel: '{{ old('jenis_apparel') }}',
    budgetFormat: '',
    formatRupiah(value) {
        if (!value) return '';
        let clean = String(value).replace(/[^0-9]/g, '');
        if (!clean) return '';
        return 'Rp ' + Number(clean).toLocaleString('id-ID');
    },
    updateBudget(val) {
        this.budgetFormat = this.formatRupiah(val);
    },
    init() {
        this.budgetFormat = this.formatRupiah('{{ old('budget_perkiraan') }}');
    }
}">

    <!-- Top Header -->
    <header class="w-full bg-white border-b border-gray-200 py-4 shadow-sm">
        <div class="max-w-4xl mx-auto px-6 flex items-center justify-between">
            <a href="/" class="text-2xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-[#1E90FF] to-[#0070e0]">
                EIGHTEEN
            </a>
            <a href="/" class="text-xs font-bold text-slate-500 hover:text-[#1E90FF] transition-colors flex items-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Beranda
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-3xl w-full mx-auto px-6 py-12 flex-1">
        <div class="space-y-4 text-center mb-10">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold tracking-wider text-[#1E90FF] bg-blue-50 border border-blue-100 uppercase">
                Formulir Pemesanan
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 leading-tight">Buat Pesanan Custom Apparel</h1>
            <p class="text-sm text-slate-500 max-w-lg mx-auto">Wujudkan pakaian impian Anda. Isi detail pemesanan di bawah ini untuk kami review dan tindak lanjuti.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-xs flex items-center mb-6 font-medium" role="alert">
                <i class="fa-solid fa-circle-exclamation mr-2 text-red-500"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-xs space-y-1 mb-6" role="alert">
                <div class="font-bold flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2 text-red-500"></i>
                    Mohon perbaiki kesalahan berikut:
                </div>
                <ul class="list-disc pl-5 space-y-0.5 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('custom-order.submit') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-150 rounded-3xl p-6 md:p-10 shadow-xl space-y-8">
            @csrf
            
            <!-- Section 1: Data Pemesan -->
            <div class="space-y-5">
                <h3 class="text-base font-bold text-[#0F172A] border-b border-gray-100 pb-3 flex items-center">
                    <i class="fa-regular fa-user mr-2 text-[#1E90FF]"></i> Data Pemesan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="nama_pemesan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input id="nama_pemesan" name="nama_pemesan" type="text" required value="{{ old('nama_pemesan') }}" placeholder="Contoh: Andi Permana"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="instansi_komunitas" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Instansi / Komunitas</label>
                        <input id="instansi_komunitas" name="instansi_komunitas" type="text" value="{{ old('instansi_komunitas') }}" placeholder="Contoh: FC Barcelona Indonesia (Opsional)"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div>
                        <label for="no_wa" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input id="no_wa" name="no_wa" type="text" required value="{{ old('no_wa') }}" placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Contoh: andi@perusahaan.com (Opsional)"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="kota_kabupaten" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kota / Kabupaten</label>
                        <input id="kota_kabupaten" name="kota_kabupaten" type="text" value="{{ old('kota_kabupaten') }}" placeholder="Contoh: Bandung"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2: Informasi Pesanan -->
            <div class="space-y-5">
                <h3 class="text-base font-bold text-[#0F172A] border-b border-gray-100 pb-3 flex items-center">
                    <i class="fa-solid fa-shirt mr-2 text-[#1E90FF]"></i> Informasi Pesanan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="nama_pesanan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Pesanan <span class="text-red-500">*</span></label>
                        <input id="nama_pesanan" name="nama_pesanan" type="text" required value="{{ old('nama_pesanan') }}" placeholder="Contoh: Jersey Futsal Keren V2"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div>
                        <label for="jenis_apparel" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Apparel <span class="text-red-500">*</span></label>
                        <select id="jenis_apparel" name="jenis_apparel" required x-model="jenisApparel"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                            <option value="">— Pilih Jenis Apparel —</option>
                            <option value="Kaos">Kaos</option>
                            <option value="Polo Shirt">Polo Shirt</option>
                            <option value="Kemeja">Kemeja</option>
                            <option value="Jersey">Jersey</option>
                            <option value="Hoodie">Hoodie</option>
                            <option value="Jaket">Jaket</option>
                            <option value="Rompi">Rompi</option>
                            <option value="Topi">Topi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div x-show="jenisApparel === 'Lainnya'" x-cloak class="sm:col-span-1">
                        <label for="jenis_apparel_lainnya" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Apparel Lainnya <span class="text-red-500">*</span></label>
                        <input id="jenis_apparel_lainnya" name="jenis_apparel_lainnya" type="text" :required="jenisApparel === 'Lainnya'" value="{{ old('jenis_apparel_lainnya') }}" placeholder="Sebutkan jenis apparel"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div>
                        <label for="jumlah_pesanan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jumlah Pesanan (Pcs) <span class="text-red-500">*</span></label>
                        <input id="jumlah_pesanan" name="jumlah_pesanan" type="number" required min="1" value="{{ old('jumlah_pesanan') ?: 12 }}"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div>
                        <label for="target_penggunaan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Target Penggunaan</label>
                        <input id="target_penggunaan" name="target_penggunaan" type="date" value="{{ old('target_penggunaan') }}"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div>
                        <label for="budget_perkiraan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Budget Perkiraan per Pcs</label>
                        <input id="budget_perkiraan" name="budget_perkiraan" type="text" x-model="budgetFormat" @input="updateBudget($event.target.value)" placeholder="Contoh: Rp 150.000"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Catatan Kebutuhan</label>
                        <textarea name="catatan_kebutuhan" rows="4" placeholder="Jelaskan kebutuhan spesifik pakaian (misal: bahan dryfit, kerah V-neck, kantong samping, dll)..."
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-2xl text-xs font-semibold text-[#2d2e34] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF]/30 focus:border-[#1E90FF] transition-all resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Upload Referensi -->
            <div class="space-y-5">
                <h3 class="text-base font-bold text-[#0F172A] border-b border-gray-100 pb-3 flex items-center">
                    <i class="fa-regular fa-folder-open mr-2 text-[#1E90FF]"></i> Berkas Referensi
                </h3>
                <p class="text-[11px] text-slate-400 leading-snug">Format yang didukung: <strong>JPG, JPEG, PNG, PDF, AI, CDR</strong>. Maksimum ukuran file: <strong>10 MB per file</strong>.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Logo Tim / Sponsor</label>
                        <input type="file" name="logo_files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.ai,.cdr"
                               class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-[#1E90FF] hover:file:bg-blue-100">
                    </div>

                    <!-- Design Reference Upload -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Referensi Gambar Desain</label>
                        <input type="file" name="design_files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.ai,.cdr"
                               class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-[#1E90FF] hover:file:bg-blue-100">
                    </div>

                    <!-- Color Reference Upload -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Referensi Warna</label>
                        <input type="file" name="color_files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.ai,.cdr"
                               class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-[#1E90FF] hover:file:bg-blue-100">
                    </div>

                    <!-- Supporting Upload -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">File Pendukung Lainnya</label>
                        <input type="file" name="supporting_files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.ai,.cdr"
                               class="w-full px-4 py-3 bg-[#F8FAFC] border border-gray-200 rounded-xl text-xs font-semibold text-[#2d2e34] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-[#1E90FF] hover:file:bg-blue-100">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" 
                    class="w-full py-4 bg-[#1E90FF] hover:bg-[#0070e0] text-white text-xs font-bold rounded-2xl shadow-xl shadow-blue-500/10 transition-all hover:scale-[1.005] active:scale-[0.995] flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-paper-plane text-xs mr-2"></i>
                    <span>Kirim Formulir Pemesanan</span>
                </button>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-6 border-t border-gray-200 bg-white text-[10px] text-slate-400">
        &copy; 2026 EIGHTEEN Custom Apparel. Semua hak dilindungi.
    </footer>

</body>
</html>
