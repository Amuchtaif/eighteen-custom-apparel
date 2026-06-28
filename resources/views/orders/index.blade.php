@extends('layouts.admin')

@section('title', 'Pesanan - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="orderManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Pesanan Produksi Custom Apparel</h1>
            <p class="text-sm text-[#64748B] mt-1">Lacak tahapan manufaktur, file personalisasi, dan alur kerja pesanan.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Pesanan
            </button>
        </div>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#E2E8F0]">
            <form action="{{ route('orders.index') }}" method="GET" class="max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor pesanan atau nama team..." 
                        class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                    <button type="submit" class="absolute right-2.5 top-2.5 text-[#64748B] hover:text-[#0F172A]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E2E8F0] text-left">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] w-12 text-center">No</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nomor Pesanan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nama Team / Pelanggan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Jenis / Bahan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Qty</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Paket</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Total Tagihan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Status Pembayaran</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Status Produksi</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Deadline / Sisa Hari</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-mono font-semibold text-[#1E90FF] whitespace-nowrap">
                                <a href="{{ route('orders.show', $order->id) }}" class="hover:underline block">
                                    {{ $order->kode_order }}
                                </a>
                                @if($order->source === 'Landing Page')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-150 mt-1">
                                        Landing Page
                                    </span>
                                @elseif($order->source)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 mt-1">
                                        {{ $order->source }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-[#0F172A]">{{ $order->nama_team ?? '-' }}</div>
                                @if($order->customer)
                                    <div class="text-xs text-[#64748B] mt-0.5 font-semibold">
                                        {{ $order->customer->company_name }} ({{ $order->customer->contact_person ?? '-' }})
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[#0F172A] font-semibold">{{ $order->jenis_apparel ?? '-' }}</div>
                                <div class="text-xs text-[#64748B] mt-0.5">Bahan: {{ $order->bahan ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-[#0F172A]">{{ number_format($order->qty_esti) }}</td>
                            <td class="px-6 py-4 text-[#64748B] font-semibold">{{ $order->package->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-[#0F172A] text-right">Rp {{ number_format($order->total_tagihan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $paymentBadgeClass = match ($order->status_pembayaran) {
                                        'Lunas' => 'bg-green-50 text-green-700 border-green-200',
                                        'DP' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Belum DP' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $paymentBadgeClass }}">
                                    {{ $order->status_pembayaran }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $prodBadgeColor }}">
                                    {{ $order->status_produksi }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[#0F172A] font-semibold">{{ $order->deadline ? $order->deadline->format('d M Y') : '-' }}</div>
                                @if($order->deadline)
                                    @php
                                        $deadlineColor = $order->deadline_color;
                                        $deadlineBadgeClass = match($deadlineColor) {
                                            'red' => 'bg-red-50 text-red-700 border-red-200',
                                            'yellow' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-green-50 text-green-700 border-green-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border mt-1 {{ $deadlineBadgeClass }} shadow-sm transition-colors">
                                        {{ $order->sisa_hari_text }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition-colors"
                                   title="Detail Pesanan">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <button type="button" @click="openEdit({{ $order->id }})" 
                                   class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                   title="Ubah Pesanan">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                @if ($order->status_produksi !== 'Selesai')
                                    <button type="button" @click="confirmDelete({{ $order->id }}, '{{ $order->kode_order }}')"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"
                                            title="Hapus Pesanan">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-cart-shopping text-3xl text-gray-300"></i>
                                    <span>Pesanan tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">{{ $orders->links() }}</div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <template x-teleport="body">
    <div x-show="modalOpen" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalOpen = false">
        <div @click.outside="if (!$event.target.closest('.z-\[110\]')) modalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[800px] overflow-hidden transform transition-all border border-gray-100 flex flex-col"
             style="max-height: 90vh;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
                <h3 class="text-lg font-bold text-[#0F172A]" x-text="modalTitle"></h3>
                <button @click="modalOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                    <p class="mt-4 text-sm text-gray-500 font-medium">Memuat data...</p>
                </div>
                
                <form x-show="!loading" @submit.prevent="submitForm()" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Pelanggan -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Pelanggan <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <div class="flex-1 relative" x-data="{
                                    open: false,
                                    search: '',
                                    get filteredCustomers() {
                                        return customersList.filter(c => {
                                            const name = (c.company_name || '').toLowerCase();
                                            const cp = (c.contact_person || '').toLowerCase();
                                            const ph = (c.phone || '').toLowerCase();
                                            return name.includes(this.search.toLowerCase()) || cp.includes(this.search.toLowerCase()) || ph.includes(this.search.toLowerCase());
                                        });
                                    },
                                    get selectedText() {
                                        const c = customersList.find(i => i.id == formData.customer_id);
                                        if (!c) return '— Pilih Pelanggan —';
                                        return c.company_name + ' (' + (c.contact_person || '-') + ')';
                                    }
                                }" @click.outside="open = false">
                                    <button type="button" @click="open = !open"
                                            class="w-full flex items-center justify-between px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all text-left"
                                            :class="errors.customer_id ? 'border-red-400 focus:ring-2 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-2 focus:ring-[#1E90FF]'">
                                        <span x-text="selectedText" class="truncate"></span>
                                        <svg class="h-4 w-4 text-[#64748B] flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak
                                         class="absolute z-[110] left-0 right-0 mt-1 bg-white border border-[#E2E8F0] rounded-lg shadow-lg flex flex-col max-h-60 overflow-hidden"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2">
                                        <div class="p-2 border-b border-[#E2E8F0] bg-slate-50 sticky top-0">
                                            <div class="relative">
                                                <input type="text" x-model="search" placeholder="Cari nama perusahaan, kontak, atau telepon..."
                                                       class="w-full pl-8 pr-3 py-1.5 bg-white border border-[#E2E8F0] rounded-md text-xs text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#1E90FF]">
                                                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2 text-[10px] text-[#64748B]"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 overflow-y-auto py-1">
                                            <button type="button" @click="formData.customer_id = ''; open = false; search = '';"
                                                    class="w-full text-left px-4 py-2 text-xs font-semibold text-gray-500 hover:bg-slate-50 transition-colors">
                                                — Pilih Pelanggan —
                                            </button>
                                            <template x-for="cust in filteredCustomers" :key="cust.id">
                                                <button type="button"
                                                        @click="formData.customer_id = cust.id; open = false; search = '';"
                                                        :class="formData.customer_id == cust.id ? 'bg-blue-50 text-[#1E90FF] font-bold' : 'text-[#0F172A] hover:bg-slate-50'"
                                                        class="w-full text-left px-4 py-2.5 text-xs transition-colors flex flex-col space-y-0.5">
                                                    <span class="font-semibold" x-text="cust.company_name"></span>
                                                    <span class="text-[10px] text-[#64748B]" x-text="(cust.contact_person || '-') + ' • ' + (cust.phone || '-')"></span>
                                                </button>
                                            </template>
                                            <div x-show="filteredCustomers.length === 0" class="px-4 py-3 text-center text-xs text-slate-400">
                                                Pelanggan tidak ditemukan.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="openCustomerModal()"
                                        class="flex-shrink-0 inline-flex items-center justify-center px-3 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold hover:bg-emerald-100 hover:text-emerald-800 transition-colors"
                                        title="Tambah Pelanggan Baru">
                                    <i class="fa-solid fa-plus mr-1.5 text-[10px]"></i> Tambah
                                </button>
                            </div>
                            <div x-show="errors.customer_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.customer_id"></div>
                        </div>

                        <!-- Nama Team & Bahan -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Team</label>
                            <input type="text" x-model="formData.nama_team" placeholder="Contoh: FC Barcelona"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.nama_team ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.nama_team" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.nama_team"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Bahan</label>
                            <input type="text" x-model="formData.bahan" placeholder="Contoh: Dryfit Jarum"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.bahan ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.bahan" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.bahan"></div>
                        </div>

                        <!-- Tanggal Masuk & Deadline -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Tanggal Masuk <span class="text-red-500">*</span></label>
                            <input type="date" x-model="formData.tanggal_masuk"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.tanggal_masuk ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.tanggal_masuk" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.tanggal_masuk"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Tenggat Waktu (Deadline) <span class="text-red-500">*</span></label>
                            <input type="date" x-model="formData.deadline"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.deadline ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.deadline" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.deadline"></div>
                        </div>

                        <!-- Paket & Status Produksi -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Paket <span class="text-red-500">*</span></label>
                            <select x-model="formData.package_id"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                    :class="errors.package_id ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <option value="">— Pilih Paket —</option>
                                <template x-for="pkg in packagesList" :key="pkg.id">
                                    <option :value="pkg.id" x-text="pkg.name"></option>
                                </template>
                            </select>
                            <div x-show="errors.package_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.package_id"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Status Produksi</label>
                            <select x-model="formData.status_produksi"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all border-[#E2E8F0] focus:ring-[#1E90FF]"
                                    :disabled="!editId">
                                <option value="Antrian">Antrian</option>
                                <option value="Print">Print</option>
                                <option value="Press">Press</option>
                                <option value="Pola">Pola</option>
                                <option value="Jahit">Jahit</option>
                                <option value="Finish">Finish</option>
                                <option value="Siap Kirim">Siap Kirim</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                            <div x-show="errors.status_produksi" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.status_produksi"></div>
                        </div>

                        <!-- Qty -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Qty <span class="text-red-500">*</span></label>
                            <input type="number" x-model.number="formData.qty_esti" min="0"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.qty_esti ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.qty_esti" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.qty_esti"></div>
                        </div>

                        <!-- Financial Fields -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Total Tagihan <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   :value="CrudHelpers.formatInputRupiah(formData.total_tagihan)"
                                   @input="formData.total_tagihan = CrudHelpers.parseInputRupiah($event.target.value); $event.target.value = CrudHelpers.formatInputRupiah(formData.total_tagihan)"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.total_tagihan ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.total_tagihan" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.total_tagihan"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">DP / Uang Muka <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   :value="CrudHelpers.formatInputRupiah(formData.dp)"
                                   @input="formData.dp = CrudHelpers.parseInputRupiah($event.target.value); $event.target.value = CrudHelpers.formatInputRupiah(formData.dp)"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.dp ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.dp" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.dp"></div>
                        </div>

                        <!-- Reactive Readonly Financial Summary -->
                        <div class="sm:col-span-2 p-4 bg-slate-50 border border-slate-100 rounded-xl space-y-2">
                            <div class="flex items-center justify-between text-xs font-semibold text-[#64748B]">
                                <span>Sisa Tagihan (Otomatis):</span>
                                <span class="text-slate-800 font-bold" x-text="'Rp ' + Number(Math.max(0, formData.total_tagihan - formData.dp)).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="flex items-center justify-between text-xs font-semibold text-[#64748B]">
                                <span>Status Pembayaran (Otomatis):</span>
                                <div>
                                    <template x-if="formData.dp <= 0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">Belum DP</span>
                                    </template>
                                    <template x-if="formData.dp > 0 && (formData.total_tagihan - formData.dp) > 0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">DP</span>
                                    </template>
                                    <template x-if="formData.dp > 0 && (formData.total_tagihan - formData.dp) <= 0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200">Lunas</span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Catatan</label>
                            <textarea x-model="formData.catatan" rows="3" placeholder="Tambahkan catatan khusus untuk produksi..."
                                      class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] transition-all resize-none"></textarea>
                            <div x-show="errors.catatan" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.catatan"></div>
                        </div>
                    </div>
                </form>
            </div>

            <div x-show="!loading" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                <button @click="modalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="button" @click="submitForm()" :disabled="submitting"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg hover:bg-blue-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan Pesanan'">Simpan Pesanan</span>
                </button>
            </div>
        </div>
    </div>
    </template>

    <!-- Add Customer Modal -->
    <template x-teleport="body">
    <div x-show="customerModalOpen" x-cloak
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="customerModalOpen = false">
        <div @click.outside="customerModalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[560px] overflow-hidden transform transition-all border border-gray-100 flex flex-col"
             style="max-height: 85vh;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="fa-solid fa-user-plus text-emerald-600 text-xs"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Tambah Pelanggan Baru</h3>
                </div>
                <button @click="customerModalOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Perusahaan / Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" x-model="customerFormData.company_name" placeholder="Contoh: CV Jaya Abadi"
                               class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                               :class="customerErrors.company_name ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                        <div x-show="customerErrors.company_name" class="text-xs text-red-600 mt-1 font-medium" x-text="customerErrors.company_name"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Narahubung</label>
                        <input type="text" x-model="customerFormData.contact_person" placeholder="Nama kontak"
                               class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                               :class="customerErrors.contact_person ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                        <div x-show="customerErrors.contact_person" class="text-xs text-red-600 mt-1 font-medium" x-text="customerErrors.contact_person"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Telepon <span class="text-red-500">*</span></label>
                        <input type="text" x-model="customerFormData.phone" placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                               :class="customerErrors.phone ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                        <div x-show="customerErrors.phone" class="text-xs text-red-600 mt-1 font-medium" x-text="customerErrors.phone"></div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Email</label>
                        <input type="email" x-model="customerFormData.email" placeholder="email@contoh.com"
                               class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                               :class="customerErrors.email ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                        <div x-show="customerErrors.email" class="text-xs text-red-600 mt-1 font-medium" x-text="customerErrors.email"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                <button @click="customerModalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="button" @click="submitCustomer()" :disabled="customerSubmitting"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i x-show="customerSubmitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                    <span x-text="customerSubmitting ? 'Menyimpan...' : 'Simpan Pelanggan'">Simpan Pelanggan</span>
                </button>
            </div>
        </div>
    </div>
    </template>

    <!-- Delete Modal -->
    <template x-teleport="body">
    <div x-show="deleteModalOpen" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.outside="deleteModalOpen = false"
             class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden transform transition-all border border-gray-100"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">
            <div class="p-6">
                <div class="flex items-center space-x-3 text-red-600 mb-4">
                    <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center"><i class="fa-solid fa-triangle-exclamation text-lg"></i></div>
                    <h3 class="text-lg font-bold text-[#0F172A]">Konfirmasi Hapus</h3>
                </div>
                <p class="text-sm text-[#64748B] leading-relaxed">Apakah Anda yakin ingin menghapus pesanan <strong class="text-[#0F172A]" x-text="deleteName"></strong>? Aksi ini akan menghapus semua file desain, data pemain, dan invoice terkait secara permanen.</p>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                <button @click="deleteModalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button @click="executeDelete()" :disabled="submitting" type="button"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm disabled:opacity-50">
                    <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function orderManager() {
    const formatDate = (dateStr) => {
        if (!dateStr) return '';
        return dateStr.includes('T') ? dateStr.split('T')[0] : dateStr;
    };

    return {
        modalOpen: false, deleteModalOpen: false, customerModalOpen: false,
        modalTitle: '', editId: null, deleteName: '', deleteId: null,
        submitting: false, loading: false, customerSubmitting: false,
        customersList: [], packagesList: [],
        customerFormData: { company_name: '', contact_person: '', phone: '', email: '', address: '', city: '' },
        customerErrors: {},
        formData: {
            customer_id: '',
            nama_team: '',
            bahan: '',
            tanggal_masuk: '',
            deadline: '',
            package_id: '',
            qty_esti: 0,
            total_tagihan: 0,
            dp: 0,
            catatan: '',
            status_produksi: 'Antrian'
        },
        errors: {},

        async openCreate() {
            this.editId = null;
            this.modalTitle = 'Buat Pesanan Baru';
            const today = new Date();
            const next2Weeks = new Date(today);
            next2Weeks.setDate(next2Weeks.getDate() + 14);
            
            this.formData = {
                customer_id: '',
                nama_team: '',
                bahan: '',
                tanggal_masuk: today.toISOString().split('T')[0],
                deadline: next2Weeks.toISOString().split('T')[0],
                package_id: '',
                qty_esti: 0,
                total_tagihan: 0,
                dp: 0,
                catatan: '',
                status_produksi: 'Antrian'
            };
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData('/orders/create');
                this.customersList = data.customers || [];
                this.packagesList = data.packages || [];
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat data pendukung.', 'error');
                this.modalOpen = false;
            } finally {
                this.loading = false;
            }
        },

        async openEdit(id) {
            this.editId = id;
            this.modalTitle = 'Ubah Pesanan';
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData(`/orders/${id}/edit`);
                this.customersList = data.customers || [];
                this.packagesList = data.packages || [];
                
                const q = data.order;
                this.formData = {
                    customer_id: q.customer_id,
                    nama_team: q.nama_team || '',
                    bahan: q.bahan || '',
                    tanggal_masuk: formatDate(q.tanggal_masuk),
                    deadline: formatDate(q.deadline),
                    package_id: q.package_id || '',
                    qty_esti: q.qty_esti || 0,
                    total_tagihan: Number(q.total_tagihan) || 0,
                    dp: Number(q.dp) || 0,
                    catatan: q.catatan || '',
                    status_produksi: q.status_produksi || 'Antrian'
                };
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat data pesanan.', 'error');
                this.modalOpen = false;
            } finally {
                this.loading = false;
            }
        },

        async submitForm() {
            this.submitting = true;
            this.errors = {};
            try {
                const url = this.editId ? `/orders/${this.editId}` : '/orders';
                const method = this.editId ? 'PUT' : 'POST';
                const result = await CrudHelpers.submitJson(url, method, this.formData);
                CrudHelpers.showToast(result.message || 'Operasi berhasil.', 'success');
                this.modalOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                if (err.status === 422 && err.errors) {
                    Object.keys(err.errors).forEach(key => { this.errors[key] = err.errors[key][0]; });
                } else {
                    CrudHelpers.showToast(err.data?.message || 'Terjadi kesalahan.', 'error');
                }
            } finally {
                this.submitting = false;
            }
        },

        confirmDelete(id, name) { this.deleteId = id; this.deleteName = name; this.deleteModalOpen = true; },

        openCustomerModal() {
            this.customerFormData = { company_name: '', contact_person: '', phone: '', email: '', address: '', city: '' };
            this.customerErrors = {};
            this.customerModalOpen = true;
        },

        async submitCustomer() {
            this.customerSubmitting = true;
            this.customerErrors = {};
            try {
                const result = await CrudHelpers.submitJson('/customers', 'POST', this.customerFormData);
                CrudHelpers.showToast(result.message || 'Pelanggan berhasil ditambahkan.', 'success');
                // Add the new customer to the list and auto-select it
                if (result.data) {
                    this.customersList.push(result.data);
                    this.formData.customer_id = result.data.id;
                }
                this.customerModalOpen = false;
            } catch (err) {
                if (err.status === 422 && err.errors) {
                    Object.keys(err.errors).forEach(key => { this.customerErrors[key] = err.errors[key][0]; });
                } else {
                    CrudHelpers.showToast(err.data?.message || 'Gagal menambahkan pelanggan.', 'error');
                }
            } finally {
                this.customerSubmitting = false;
            }
        },

        async executeDelete() {
            this.submitting = true;
            try {
                const result = await CrudHelpers.deleteResource(`/orders/${this.deleteId}`);
                CrudHelpers.showToast(result.message || 'Pesanan berhasil dihapus.', 'success');
                this.deleteModalOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                CrudHelpers.showToast(err.data?.message || 'Gagal menghapus pesanan.', 'error');
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush
