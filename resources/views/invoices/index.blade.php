@extends('layouts.admin')

@section('title', 'Faktur - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="invoiceManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Faktur Penagihan</h1>
            <p class="text-sm text-[#64748B] mt-1">Kelola faktur penagihan, status pembayaran, dan saldo terutang.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Faktur
            </button>
        </div>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#E2E8F0]">
            <form action="{{ route('invoices.index') }}" method="GET" class="max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor faktur atau perusahaan..." 
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
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nomor Faktur</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Pelanggan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Tanggal</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Total</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-mono font-semibold text-[#1E90FF]">
                                <button @click="openDetail({{ $invoice->id }})" class="hover:underline cursor-pointer">{{ $invoice->invoice_number }}</button>
                            </td>
                            <td class="px-6 py-4 font-medium text-[#0F172A]">{{ $invoice->order->customer->company_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-[#64748B]">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-semibold text-[#0F172A] text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColor = match ($invoice->status) {
                                        'unpaid' => 'bg-red-50 text-red-800 border-red-200',
                                        'partially_paid' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'paid' => 'bg-green-50 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button type="button" @click="openDetail({{ $invoice->id }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition-colors" title="Detail Faktur">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                @if ($invoice->status !== 'paid')
                                    <button type="button" @click="confirmDelete({{ $invoice->id }}, '{{ addslashes($invoice->invoice_number) }}')"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors" title="Hapus Faktur">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-file-invoice text-3xl text-gray-300"></i>
                                    <span>Faktur tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">{{ $invoices->links() }}</div>
        @endif
    </div>

    <!-- Create Modal -->
    <div x-show="modalOpen" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalOpen = false">
        <div @click.outside="modalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[600px] overflow-hidden transform transition-all border border-gray-100 flex flex-col"
             style="max-height: 90vh;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
                <h3 class="text-lg font-bold text-[#0F172A]">Buat Faktur Baru</h3>
                <button @click="modalOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                    <p class="mt-4 text-sm text-gray-500 font-medium">Memuat data...</p>
                </div>
                <form x-show="!loading" @submit.prevent="submitCreate()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Pesanan <span class="text-red-500">*</span></label>
                        <select x-model="formData.order_id"
                                class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                :class="errors.order_id ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <option value="">— Pilih Pesanan —</option>
                            <template x-for="order in ordersList" :key="order.id">
                                <option :value="order.id" x-text="order.order_number + ' — ' + (order.customer?.company_name || 'N/A')"></option>
                            </template>
                        </select>
                        <div x-show="errors.order_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.order_id"></div>
                        <p x-show="ordersList.length === 0 && !loading" class="text-xs text-amber-600 mt-2 font-medium">
                            <i class="fa-solid fa-info-circle mr-1"></i> Tidak ada pesanan yang memenuhi syarat untuk dibuatkan faktur.
                        </p>
                    </div>
                </form>
            </div>
            <div x-show="!loading" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                <button @click="modalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="button" @click="submitCreate()" :disabled="submitting || !formData.order_id"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg hover:bg-blue-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                    <span x-text="submitting ? 'Membuat...' : 'Buat Faktur'">Buat Faktur</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="detailOpen" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="detailOpen = false">
        <div @click.outside="detailOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[800px] overflow-hidden transform transition-all border border-gray-100 flex flex-col"
             style="max-height: 90vh;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
                <h3 class="text-lg font-bold text-[#0F172A]">Detail Faktur</h3>
                <button @click="detailOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                </div>
                <div x-show="!loading" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Nomor Faktur</p>
                            <p class="text-sm font-bold text-[#0F172A] mt-0.5" x-text="detailData.invoice?.invoice_number || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Status</p>
                            <p class="text-sm font-bold mt-0.5" :class="{
                                'text-red-600': detailData.invoice?.status === 'unpaid',
                                'text-amber-600': detailData.invoice?.status === 'partially_paid',
                                'text-green-600': detailData.invoice?.status === 'paid',
                            }" x-text="detailData.invoice?.status ? detailData.invoice.status.replace('_', ' ').toUpperCase() : '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Pelanggan</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.invoice?.order?.customer?.company_name || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Nomor Pesanan</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.invoice?.order?.order_number || '-'"></p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-xs font-semibold text-[#64748B] uppercase">Total Faktur</p>
                                <p class="text-lg font-bold text-[#0F172A] mt-1" x-text="'Rp ' + Number(detailData.invoice?.total_amount || 0).toLocaleString('id-ID')"></p>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                                <p class="text-xs font-semibold text-emerald-600 uppercase">Total Dibayar</p>
                                <p class="text-lg font-bold text-emerald-700 mt-1" x-text="'Rp ' + Number(detailData.total_paid || 0).toLocaleString('id-ID')"></p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4 text-center">
                                <p class="text-xs font-semibold text-red-600 uppercase">Sisa Saldo</p>
                                <p class="text-lg font-bold text-red-700 mt-1" x-text="'Rp ' + Number(detailData.remaining_balance || 0).toLocaleString('id-ID')"></p>
                            </div>
                        </div>
                    </div>
                    <!-- Payment History -->
                    <div x-show="detailData.invoice?.payments?.length > 0" class="border-t border-gray-100 pt-4">
                        <h4 class="text-sm font-bold text-[#0F172A] mb-3">Riwayat Pembayaran</h4>
                        <div class="space-y-2">
                            <template x-for="(payment, i) in detailData.invoice?.payments || []" :key="i">
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2.5">
                                    <div>
                                        <p class="text-sm font-medium text-[#0F172A]" x-text="payment.reference_number || 'Pembayaran #' + (i+1)"></p>
                                        <p class="text-xs text-[#64748B]" x-text="payment.payment_method?.replace('_', ' ').toUpperCase()"></p>
                                    </div>
                                    <p class="text-sm font-bold text-emerald-700" x-text="'Rp ' + Number(payment.amount || 0).toLocaleString('id-ID')"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                <button @click="detailOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
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
                <p class="text-sm text-[#64748B] leading-relaxed">Apakah Anda yakin ingin menghapus faktur <strong class="text-[#0F172A]" x-text="deleteName"></strong>?</p>
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
</div>
@endsection

@push('scripts')
<script>
function invoiceManager() {
    return {
        modalOpen: false, detailOpen: false, deleteModalOpen: false,
        deleteName: '', deleteId: null,
        submitting: false, loading: false,
        ordersList: [],
        formData: { order_id: '' },
        detailData: {},
        errors: {},

        async openCreate() {
            this.formData = { order_id: '' };
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData('/invoices/create');
                this.ordersList = data.orders || [];
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat daftar pesanan.', 'error');
            } finally {
                this.loading = false;
            }
        },

        async submitCreate() {
            this.submitting = true;
            this.errors = {};
            try {
                const result = await CrudHelpers.submitJson('/invoices', 'POST', this.formData);
                CrudHelpers.showToast(result.message || 'Faktur berhasil dibuat.', 'success');
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

        async openDetail(id) {
            this.loading = true;
            this.detailOpen = true;
            try {
                this.detailData = await CrudHelpers.fetchData(`/invoices/${id}`);
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat detail faktur.', 'error');
                this.detailOpen = false;
            } finally {
                this.loading = false;
            }
        },

        confirmDelete(id, name) { this.deleteId = id; this.deleteName = name; this.deleteModalOpen = true; },

        async executeDelete() {
            this.submitting = true;
            try {
                const result = await CrudHelpers.deleteResource(`/invoices/${this.deleteId}`);
                CrudHelpers.showToast(result.message || 'Faktur berhasil dihapus.', 'success');
                this.deleteModalOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                CrudHelpers.showToast(err.data?.message || 'Gagal menghapus faktur.', 'error');
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush
