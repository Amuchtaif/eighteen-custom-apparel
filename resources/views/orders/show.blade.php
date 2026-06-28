@extends('layouts.admin')

@section('title', "Ruang Kerja Pesanan {$order->kode_order} - Eighteen ERP")

@section('content')
<div class="space-y-8" x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'overview', lightboxOpen: false, lightboxImg: '', setTab(tab) { this.activeTab = tab; window.location.hash = tab; } }" x-init="window.addEventListener('hashchange', () => activeTab = window.location.hash ? window.location.hash.substring(1) : 'overview')">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('orders.index') }}" class="text-[#64748B] hover:text-[#0F172A] transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Ruang Kerja Pesanan</h1>
                <p class="text-sm text-[#64748B] mt-1">Ruang kerja pesanan aktif untuk <span class="font-mono font-bold text-[#1E90FF]">{{ $order->kode_order }}</span>.</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="window.location.href = '{{ route('orders.edit', $order->id) }}'" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-[#E2E8F0] rounded-lg shadow-sm hover:bg-[#F8FAFC] transition-colors">
                Ubah Detail Umum
            </button>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-[#E2E8F0]">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="setTab('overview')" 
                :class="activeTab === 'overview' ? 'border-[#1E90FF] text-[#1E90FF]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm cursor-pointer transition-colors focus:outline-none">
                Ringkasan
            </button>
            <button @click="setTab('players')" 
                :class="activeTab === 'players' ? 'border-[#1E90FF] text-[#1E90FF]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm cursor-pointer transition-colors focus:outline-none">
                Daftar Pemain
            </button>
            <button @click="setTab('designs')" 
                :class="activeTab === 'designs' ? 'border-[#1E90FF] text-[#1E90FF]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm cursor-pointer transition-colors focus:outline-none">
                Berkas Desain
            </button>
            <button @click="setTab('production')" 
                :class="activeTab === 'production' ? 'border-[#1E90FF] text-[#1E90FF]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm cursor-pointer transition-colors focus:outline-none">
                Progres Produksi
            </button>
            <button @click="setTab('financials')" 
                :class="activeTab === 'financials' ? 'border-[#1E90FF] text-[#1E90FF]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm cursor-pointer transition-colors focus:outline-none">
                Faktur & Pembayaran
            </button>
        </nav>
    </div>

    <!-- Tabs Content -->
    <div>
        <!-- TAB: OVERVIEW -->
        <div x-show="activeTab === 'overview'" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Metadata details -->
                <div class="md:col-span-2 bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-6">
                    <h2 class="text-lg font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-3">Detail Pesanan</h2>
                    
                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div>
                            <span class="text-[#64748B] block">Tanggal Pesanan</span>
                            <span class="font-semibold text-slate-800">{{ $order->tanggal_masuk->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-[#64748B] block">Tenggat Waktu Produksi</span>
                            <span class="font-semibold text-slate-800">{{ $order->deadline->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-[#64748B] block">Status Produksi Saat Ini</span>
                            @php
                                $prodBadgeColor = match ($order->status_produksi) {
                                    'Antrian' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    'Print' => 'bg-blue-50 text-blue-800 border-blue-200',
                                    'Press' => 'bg-purple-50 text-purple-800 border-purple-200',
                                    'Pola' => 'bg-orange-50 text-orange-800 border-orange-200',
                                    'Jahit' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
                                    'Finish' => 'bg-green-50 text-green-800 border-green-200',
                                    'Siap Kirim' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'Selesai' => 'bg-teal-50 text-teal-800 border-teal-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border mt-1 {{ $prodBadgeColor }}">
                                {{ $order->status_produksi }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[#64748B] block">Status Pembayaran</span>
                            @php
                                $paymentBadgeClass = match ($order->status_pembayaran) {
                                    'Lunas' => 'bg-green-50 text-green-700 border-green-200',
                                    'DP' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Belum DP' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border mt-1 {{ $paymentBadgeClass }}">
                                {{ $order->status_pembayaran }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm pt-4 border-t border-[#E2E8F0]">
                        <span class="text-[#64748B] block">Catatan Umum Pesanan</span>
                        <p class="text-slate-700 bg-slate-50 p-4 rounded-lg border border-slate-100 italic">
                            {{ $order->catatan ?? 'Tidak ada catatan kustom yang diberikan untuk pesanan ini.' }}
                        </p>
                    </div>
                </div>

                <!-- Customer info card -->
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-3">Profil Klien</h2>
                    @if ($order->customer)
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Perusahaan</span>
                                <span class="font-bold text-[#0F172A]">{{ $order->customer->company_name }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Narahubung</span>
                                <span class="font-medium text-slate-800">{{ $order->customer->contact_person }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Telepon</span>
                                <span class="font-medium text-slate-800">{{ $order->customer->phone }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Email</span>
                                <span class="font-medium text-slate-800">{{ $order->customer->email }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Alamat</span>
                                <span class="font-medium text-slate-800 block leading-snug">{{ $order->customer->address }}, {{ $order->customer->city }}</span>
                            </div>
                        </div>
                    @else
                        <span class="text-slate-400">Profil pelanggan hilang.</span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-3">Detail Produksi & Biaya</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <span class="text-[#64748B] block font-bold uppercase tracking-wider text-xs">Paket Prioritas</span>
                        <span class="font-bold text-slate-800 text-base mt-1 block">{{ $order->package->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[#64748B] block font-bold uppercase tracking-wider text-xs">Bahan Kain</span>
                        <span class="font-semibold text-slate-800 text-base mt-1 block">{{ $order->bahan ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[#64748B] block font-bold uppercase tracking-wider text-xs">Qty</span>
                        <span class="font-semibold text-slate-800 text-base mt-1 block">{{ number_format($order->qty_esti) }} unit</span>
                    </div>
                </div>

                <!-- Financial totals -->
                <div class="flex justify-end pt-6 border-t border-[#E2E8F0]">
                    <div class="w-80 space-y-3 text-sm">
                        <div class="flex items-center justify-between text-[#64748B]">
                            <span>Total Tagihan</span>
                            <span class="font-mono font-medium text-slate-800">Rp {{ number_format($order->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[#64748B]">
                            <span>DP / Uang Muka</span>
                            <span class="font-mono font-medium text-slate-800">Rp {{ number_format($order->dp, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-[#E2E8F0] pt-3 text-base">
                            <span class="font-bold text-[#0F172A]">Sisa Tagihan</span>
                            <span class="font-mono font-black text-rose-600">Rp {{ number_format($order->sisa_tagihan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: PLAYER LIST -->
        <div x-show="activeTab === 'players'" class="space-y-8" style="display: none;">
            <div>
                <h2 class="text-xl font-bold text-[#0F172A]">Log Personalisasi Jersey</h2>
                <p class="text-sm text-[#64748B] mt-1">Konfigurasikan nama, nomor, dan spesifikasi ukuran pemain untuk jalur produksi jersey.</p>
            </div>

            <div class="bg-white rounded-xl border border-[#E2E8F0] p-6 space-y-4 shadow-sm" x-data="playerRepeater({{ json_encode($order->players) }})">
                <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Daftar Pemain ({{ $order->nama_team ?? '-' }})</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Jumlah dikonfigurasi: <span x-text="players.length"></span> dari {{ $order->qty_esti }} unit estimasi</p>
                    </div>
                    <button type="button" @click="addPlayerRow()" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-[#1E90FF] bg-[#1E90FF]/10 rounded-lg hover:bg-[#1E90FF]/20 transition-colors">
                        + Tambah Baris Pemain
                    </button>
                </div>

                <form action="{{ route('orders.update-players', $order->id) }}#players" method="POST" class="space-y-4">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#E2E8F0] text-left text-xs">
                            <thead class="bg-[#F8FAFC]">
                                <tr class="text-[#64748B] uppercase tracking-wider font-semibold">
                                    <th class="px-4 py-2.5">Nama Pemain</th>
                                    <th class="px-4 py-2.5 w-32">Nomor</th>
                                    <th class="px-4 py-2.5 w-40">Ukuran</th>
                                    <th class="px-4 py-2.5">Catatan Baris</th>
                                    <th class="px-4 py-2.5 text-right w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E2E8F0]">
                                <template x-for="(p, index) in players" :key="index">
                                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                                        <td class="px-4 py-2">
                                            <input type="text" :name="`players[${index}][player_name]`" x-model="p.player_name" required placeholder="e.g. A. GIBRAN"
                                                class="w-full px-3 py-1.5 bg-white border border-[#E2E8F0] rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-[#1E90FF]">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" :name="`players[${index}][player_number]`" x-model="p.player_number" placeholder="e.g. 10"
                                                class="w-full px-3 py-1.5 bg-white border border-[#E2E8F0] rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-[#1E90FF]">
                                        </td>
                                        <td class="px-4 py-2">
                                            <select :name="`players[${index}][size]`" x-model="p.size" required
                                                class="w-full px-3 py-1.5 bg-white border border-[#E2E8F0] rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-[#1E90FF]">
                                                <option value="S">S</option>
                                                <option value="M">M</option>
                                                <option value="L">L</option>
                                                <option value="XL">XL</option>
                                                <option value="XXL">XXL</option>
                                                <option value="XXXL">XXXL</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" :name="`players[${index}][notes]`" x-model="p.notes" placeholder="e.g. Long sleeves..."
                                                class="w-full px-3 py-1.5 bg-white border border-[#E2E8F0] rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-[#1E90FF]">
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <button type="button" @click="removePlayerRow(index)" class="text-xs font-semibold text-red-600 hover:text-red-800 transition-colors">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="players.length === 0">
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                        Belum ada spesifikasi pemain yang dikonfigurasi.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-[#E2E8F0]">
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[#1E90FF] hover:bg-blue-600 rounded-lg shadow-sm transition-colors cursor-pointer">
                            Perbarui Spesifikasi Pemain
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: DESIGN FILES -->
        <div x-show="activeTab === 'designs'" class="space-y-8" style="display: none;">
            <div>
                <h2 class="text-xl font-bold text-[#0F172A]">Versi Desain & Cetak Biru</h2>
                <p class="text-sm text-[#64748B] mt-1">Kelola maket, templat pencetakan, dan pelacakan versi.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Upload form -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-[#E2E8F0] rounded-xl p-6 shadow-sm space-y-4">
                        <h3 class="text-base font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-2">Unggah Iterasi Maket</h3>
                        <form action="{{ route('orders.upload-design', $order->id) }}#designs" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label for="design_file" class="block text-sm font-semibold text-[#0F172A]">Berkas (Gambar/PDF, maks 10MB)</label>
                                <input type="file" name="design_file" id="design_file" required 
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1E90FF] hover:file:bg-blue-100">
                            </div>
                            <div class="space-y-2">
                                <label for="file_name" class="block text-sm font-semibold text-[#0F172A]">Label / Judul Cetak Biru</label>
                                <input type="text" name="file_name" id="file_name" required placeholder="e.g. Front Mockup V1" 
                                    class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent">
                            </div>
                            <div class="space-y-2">
                                <label for="design_notes" class="block text-sm font-semibold text-[#0F172A]">Catatan Unggah</label>
                                <textarea name="notes" id="design_notes" rows="3" placeholder="Komentar opsional mengenai peningkatan desain..." 
                                    class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 text-xs font-bold text-white bg-[#1E90FF] hover:bg-blue-600 rounded-lg shadow-sm transition-colors cursor-pointer">
                                Unggah Berkas
                            </button>
                        </form>
                    </div>
                </div>

                <!-- File listing -->
                <div class="lg:col-span-2 space-y-4">
                    <h3 class="text-base font-bold text-[#0F172A]">Riwayat Versi</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($order->designFiles as $file)
                            <div class="bg-white border border-[#E2E8F0] rounded-xl p-4 shadow-sm flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-blue-50 text-[#1E90FF] border border-blue-200">V{{ $file->version }}</span>
                                        <span class="text-[11px] text-[#64748B] font-mono">{{ $file->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-[#0F172A] truncate" title="{{ $file->file_name }}">{{ $file->file_name }}</h4>
                                    <p class="text-xs text-[#64748B] line-clamp-2">{{ $file->notes ?? 'Tidak ada catatan.' }}</p>
                                    <p class="text-[10px] text-slate-400 pt-1">Oleh: {{ $file->uploader->name ?? 'Sistem' }}</p>
                                </div>
                                
                                @php
                                    $extension = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                                    $isImg = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                <div class="flex gap-2">
                                    @if($isImg)
                                        <button type="button" @click="lightboxImg = '{{ asset('storage/' . $file->file_path) }}'; lightboxOpen = true"
                                            class="flex-1 inline-flex items-center justify-center py-2 text-xs font-bold text-slate-650 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors cursor-pointer">
                                            Pratinjau
                                        </button>
                                    @endif
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" download 
                                        class="{{ $isImg ? 'flex-1' : 'w-full' }} inline-flex items-center justify-center py-2 text-xs font-bold text-[#1E90FF] bg-[#1E90FF]/10 rounded-lg hover:bg-[#1E90FF]/20 transition-colors">
                                        <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="sm:col-span-2 text-center text-slate-400 py-12 bg-white rounded-xl border border-[#E2E8F0]">
                                Belum ada berkas maket yang diunggah.
                              </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Client Attachments Section -->
            <div class="border-t border-[#E2E8F0] pt-8 space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Berkas Lampiran Pemesanan Klien</h3>
                    <p class="text-xs text-[#64748B] mt-0.5">Berkas yang diunggah oleh klien saat melakukan pemesanan melalui landing page.</p>
                </div>

                @if($order->attachments && $order->attachments->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($order->attachments as $attachment)
                            <div class="bg-white border border-[#E2E8F0] rounded-xl p-4 shadow-sm flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 border border-indigo-200 uppercase">
                                            {{ str_replace('_', ' ', $attachment->file_type) }}
                                        </span>
                                        <span class="text-[10px] text-[#64748B] font-mono">{{ $attachment->created_at->format('d M Y') }}</span>
                                    </div>
                                    
                                    @php
                                        $extension = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                                    @endphp

                                    @if($isImage)
                                        <div @click="lightboxImg = '{{ asset('storage/' . $attachment->file_path) }}'; lightboxOpen = true"
                                             class="aspect-video w-full rounded-lg overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center cursor-pointer">
                                            <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="{{ $attachment->file_name }}" class="object-cover w-full h-full hover:scale-105 transition-transform duration-300">
                                        </div>
                                    @else
                                        <div class="aspect-video w-full rounded-lg border border-slate-100 bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                                            <i class="fa-solid fa-file text-2xl mb-1 text-red-500"></i>
                                            <span class="text-[9px] font-bold uppercase">{{ $extension }}</span>
                                        </div>
                                    @endif

                                    <h4 class="text-xs font-bold text-[#0F172A] truncate" title="{{ $attachment->file_name }}">{{ $attachment->file_name }}</h4>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400">
                                        <span>Ukuran: {{ number_format($attachment->file_size / 1024, 1) }} KB</span>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    @if($isImage)
                                        <button type="button" @click="lightboxImg = '{{ asset('storage/' . $attachment->file_path) }}'; lightboxOpen = true"
                                                class="flex-1 inline-flex items-center justify-center py-2 text-xs font-bold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors cursor-pointer">
                                            Pratinjau
                                        </button>
                                    @else
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                           class="flex-1 inline-flex items-center justify-center py-2 text-xs font-bold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                                            Pratinjau
                                        </a>
                                    @endif
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" download
                                       class="flex-1 inline-flex items-center justify-center py-2 text-xs font-bold text-[#1E90FF] bg-[#1E90FF]/10 rounded-lg hover:bg-[#1E90FF]/20 transition-colors">
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-slate-400 py-8 bg-slate-50 rounded-xl border border-[#E2E8F0] text-xs">
                        Tidak ada berkas lampiran yang diunggah oleh klien untuk pesanan ini.
                    </div>
                @endif
            </div>
        </div>

        <!-- TAB: PRODUCTION PROGRESS -->
        <div x-show="activeTab === 'production'" class="space-y-8" style="display: none;">
            <div>
                <h2 class="text-xl font-bold text-[#0F172A]">Progres Pembuatan & Log Tindakan</h2>
                <p class="text-sm text-[#64748B] mt-1">Transisikan tahap dan lihat pelacakan riwayat pencapaian.</p>
            </div>

            <div class="bg-white border border-[#E2E8F0] rounded-xl p-6 shadow-sm space-y-6">
                <!-- Stepper tracker -->
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-3">
                    @foreach ($productionStages as $stage)
                        @php
                            $isSelesai = false;
                            $isActive = false;
                            
                            $stagesList = ['Antrian', 'Print', 'Press', 'Pola', 'Jahit', 'Finish', 'Siap Kirim', 'Selesai'];
                            $currentStageIndex = array_search($order->status_produksi, $stagesList);
                            $thisStageIndex = array_search($stage->name, $stagesList);
                            
                            if ($thisStageIndex !== false && $currentStageIndex !== false) {
                                if ($thisStageIndex < $currentStageIndex) {
                                    $isSelesai = true;
                                } elseif ($thisStageIndex === $currentStageIndex) {
                                    $isActive = true;
                                }
                            }
                        @endphp
                        <div class="flex flex-col items-center text-center p-2 rounded-lg border {{ $isActive ? 'border-[#1E90FF] bg-blue-50/50' : ($isSelesai ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-100') }}">
                            <div class="h-6 w-6 rounded-full flex items-center justify-center text-xs font-bold font-mono {{ $isActive ? 'bg-[#1E90FF] text-white' : ($isSelesai ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600') }}">
                                {{ $loop->iteration }}
                            </div>
                            <span class="text-[10px] font-semibold mt-1.5 {{ $isActive ? 'text-[#1E90FF]' : ($isSelesai ? 'text-emerald-700' : 'text-slate-500') }}">{{ $stage->name }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 border-t border-[#E2E8F0] pt-6">
                    <!-- Transition Form -->
                    <div class="lg:col-span-1 space-y-4">
                        <h4 class="text-sm font-bold text-[#0F172A]">Catat Transisi Tahap</h4>
                        <form action="{{ route('orders.transition-stage', $order->id) }}#production" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label for="stage_id" class="block text-sm font-semibold text-[#0F172A]">Tahap Target</label>
                                <select name="stage_id" id="stage_id" required class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent">
                                    <option value="">Pilih Tahap Target</option>
                                    @foreach ($productionStages as $stage)
                                        <option value="{{ $stage->id }}" {{ ($order->status_produksi == $stage->name) ? 'selected disabled' : '' }}>
                                            {{ $loop->iteration }}. {{ $stage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="transition_notes" class="block text-sm font-semibold text-[#0F172A]">Komentar Tahap</label>
                                <textarea name="notes" id="transition_notes" rows="3" placeholder="Tentukan rincian progress, kendala, atau komentar..." class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 text-xs font-bold text-white bg-[#1E90FF] hover:bg-blue-600 rounded-lg shadow-sm transition-colors cursor-pointer">
                                Transisikan Pencapaian
                            </button>
                        </form>
                    </div>

                    <!-- Log History -->
                    <div class="lg:col-span-2 space-y-4">
                        <h4 class="text-sm font-bold text-[#0F172A]">Riwayat Transisi Pencapaian</h4>
                        <div class="relative border-l-2 border-[#E2E8F0] pl-6 ml-3 space-y-6">
                            @forelse ($order->productionLogs as $log)
                                <div class="relative">
                                    <span class="absolute -left-[31px] top-1 h-3 w-3 rounded-full bg-[#1E90FF] ring-4 ring-white"></span>
                                    <div class="flex items-center justify-between text-xs text-[#64748B] font-mono">
                                        <span class="font-bold text-[#0F172A]">{{ $log->stage->name ?? '-' }}</span>
                                        <span>{{ $log->started_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <p class="text-xs text-[#64748B] mt-1">{{ $log->notes ?? 'Pencapaian dimulai.' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1">Dicatat oleh: {{ $log->updater->name ?? 'System' }}</p>
                                </div>
                            @empty
                                <div class="text-slate-400 text-xs py-2">Riwayat log tidak ditemukan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: INVOICES & PAYMENTS -->
        <div x-show="activeTab === 'financials'" class="space-y-8" style="display: none;">
            <div>
                <h2 class="text-xl font-bold text-[#0F172A]">Buku Besar Faktur & Pembayaran Masuk</h2>
                <p class="text-sm text-[#64748B] mt-1">Lacak pembayaran yang diterima, saldo utang yang tertunda, dan catatan penagihan.</p>
            </div>

            <div class="space-y-6">
                @forelse ($order->invoices as $invoice)
                    <div class="bg-white border border-[#E2E8F0] rounded-xl p-6 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-[#E2E8F0] pb-3">
                            <div>
                                <h3 class="text-base font-bold text-[#0F172A]">Faktur #{{ $invoice->invoice_number }}</h3>
                                <p class="text-xs text-[#64748B] mt-0.5">Dibuat pada: {{ $invoice->created_at->format('d M Y') }}</p>
                            </div>
                            <div>
                                @php
                                    $invStatusColor = match ($invoice->status) {
                                        'unpaid' => 'bg-red-50 text-red-800 border-red-200',
                                        'partially_paid' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'paid' => 'bg-green-50 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $invStatusColor }}">
                                    {{ $invoice->status === 'paid' ? 'Lunas' : ($invoice->status === 'unpaid' ? 'Belum Lunas' : ($invoice->status === 'partially_paid' ? 'Dibayar Sebagian' : 'Dibatalkan')) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm bg-slate-50 p-4 rounded-lg border border-slate-100">
                            <div>
                                <span class="text-xs text-[#64748B] block font-bold uppercase tracking-wider text-[10px]">Total Penagihan</span>
                                <span class="font-bold text-slate-800 text-base mt-1 block">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-[#64748B] block font-bold uppercase tracking-wider text-[10px]">Jumlah Dibayar</span>
                                <span class="font-bold text-emerald-600 text-base mt-1 block">Rp {{ number_format($invoice->payments->sum('amount'), 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-[#64748B] block font-bold uppercase tracking-wider text-[10px]">Utang Belum Lunas</span>
                                <span class="font-bold text-rose-600 text-base mt-1 block">Rp {{ number_format($invoice->total_amount - $invoice->payments->sum('amount'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Riwayat Log Penagihan</h4>
                            <div class="overflow-x-auto border border-[#E2E8F0] rounded-lg">
                                <table class="min-w-full divide-y divide-[#E2E8F0] text-left text-xs">
                                    <thead class="bg-[#F8FAFC]">
                                        <tr>
                                            <th class="px-4 py-2.5 font-semibold text-[#64748B]">Tanggal</th>
                                            <th class="px-4 py-2.5 font-semibold text-[#64748B]">Nomor Referensi</th>
                                            <th class="px-4 py-2.5 font-semibold text-[#64748B]">Metode Pembayaran</th>
                                            <th class="px-4 py-2.5 font-semibold text-[#64748B]">Jumlah</th>
                                            <th class="px-4 py-2.5 font-semibold text-[#64748B]">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E2E8F0]">
                                        @forelse ($invoice->payments as $payment)
                                            <tr>
                                                <td class="px-4 py-2.5 text-slate-600">{{ $payment->payment_date?->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td>
                                                <td class="px-4 py-2.5 font-mono font-semibold text-[#1E90FF]">{{ $payment->reference_number }}</td>
                                                <td class="px-4 py-2.5 text-slate-700 capitalize">
                                                    {{ $payment->payment_method === 'cash' ? 'Tunai' : ($payment->payment_method === 'bank_transfer' ? 'Transfer Bank' : ($payment->payment_method === 'ewallet' ? 'Dompet Digital' : str_replace('_', ' ', $payment->payment_method))) }}
                                                </td>
                                                <td class="px-4 py-2.5 font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                <td class="px-4 py-2.5">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border border-green-200 bg-green-50 text-green-700">Terverifikasi</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-4 text-center text-slate-400">
                                                    Tidak ada pembayaran yang terdaftar untuk faktur ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-400 py-12 bg-white rounded-xl border border-[#E2E8F0]">
                        Belum ada faktur aktif yang dibuat untuk pesanan ini.
                    </div>
                @endforelse
            </div>
        </div>
    <!-- Lightbox Modal -->
    <template x-teleport="body">
        <div x-show="lightboxOpen" x-cloak
             class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @keydown.escape.window="lightboxOpen = false">
            <div @click.outside="lightboxOpen = false"
                 class="relative max-w-4xl max-h-[85vh] overflow-hidden bg-white rounded-2xl border border-gray-150 shadow-2xl flex flex-col p-2 animate-fade-in-up"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">
                
                <button @click="lightboxOpen = false" type="button" 
                        class="absolute right-3 top-3 bg-white/85 hover:bg-white text-slate-700 hover:text-slate-900 h-8 w-8 rounded-full flex items-center justify-center shadow-md transition-all z-10 cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

                <img :src="lightboxImg" class="max-w-full max-h-[80vh] object-contain rounded-xl" alt="Pratinjau Lampiran">
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
    function playerRepeater(initialPlayers) {
        return {
            players: initialPlayers || [],
            addPlayerRow() {
                this.players.push({
                    player_name: '',
                    player_number: '',
                    size: 'M',
                    notes: ''
                });
            },
            removePlayerRow(index) {
                this.players.splice(index, 1);
            }
        }
    }
</script>
@endpush
