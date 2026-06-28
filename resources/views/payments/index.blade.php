@extends('layouts.admin')

@section('title', 'Pembayaran - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="paymentManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Pembayaran</h1>
            <p class="text-sm text-[#64748B] mt-1">Catat dan kelola pembayaran dari pelanggan terhadap faktur.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Catat Pembayaran
            </button>
        </div>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#E2E8F0]">
            <form action="{{ route('payments.index') }}" method="GET" class="max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari referensi, faktur, atau perusahaan..." 
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
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Referensi</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Faktur</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Pelanggan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Tanggal</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Metode</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Bukti</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-[#64748B]">{{ $payment->reference_number ?: '-' }}</td>
                            <td class="px-6 py-4 font-mono font-semibold text-[#1E90FF]">{{ $payment->invoice->invoice_number ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium text-[#0F172A]">{{ $payment->invoice->order->customer->company_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-[#64748B]">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($payment->proof_file)
                                    <button @click="lightboxImg = '{{ asset('storage/' . $payment->proof_file) }}'; lightboxOpen = true"
                                            type="button"
                                            class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold text-[#1E90FF] bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-receipt mr-1.5 text-[10px]"></i>
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-xs font-medium text-slate-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-emerald-700 text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-credit-card text-3xl text-gray-300"></i>
                                    <span>Pembayaran tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payments->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">{{ $payments->links() }}</div>
        @endif
    </div>

    <!-- Create Payment Modal -->
    <template x-teleport="body">
        <div x-show="modalOpen" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @keydown.escape.window="modalOpen = false">
            <div @click.outside="modalOpen = false"
                 class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[800px] overflow-hidden transform transition-all border border-gray-100 flex flex-col"
                 style="max-height: 90vh;"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
                    <h3 class="text-lg font-bold text-[#0F172A]">Catat Pembayaran Baru</h3>
                    <button @click="modalOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all"><i class="fa-solid fa-xmark text-sm"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                    <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                        <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                    </div>
                    <form x-show="!loading" @submit.prevent="submitCreate()" class="space-y-4" id="paymentCreateForm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Faktur <span class="text-red-500">*</span></label>
                                <div class="relative" x-data="{ 
                                    open: false, 
                                    search: '', 
                                    get filteredInvoices() {
                                        return invoicesList.filter(inv => {
                                            const num = (inv.invoice_number || '').toLowerCase();
                                            const comp = (inv.order?.customer?.company_name || '').toLowerCase();
                                            return num.includes(this.search.toLowerCase()) || comp.includes(this.search.toLowerCase());
                                        });
                                    },
                                    get selectedText() {
                                        const inv = invoicesList.find(i => i.id == formData.invoice_id);
                                        if (!inv) return '— Pilih Faktur —';
                                        return inv.invoice_number + ' — ' + (inv.order?.customer?.company_name || 'N/A') + ' (Rp ' + Number(inv.total_amount).toLocaleString('id-ID') + ')';
                                    }
                                }" @click.outside="open = false">
                                    <button type="button" @click="open = !open"
                                            class="w-full flex items-center justify-between px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all text-left"
                                            :class="errors.invoice_id ? 'border-red-400 focus:ring-2 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-2 focus:ring-[#1E90FF]'">
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
                                                <input type="text" x-model="search" placeholder="Cari nomor faktur atau pelanggan..."
                                                       class="w-full pl-8 pr-3 py-1.5 bg-white border border-[#E2E8F0] rounded-md text-xs text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#1E90FF]">
                                                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2 text-[10px] text-[#64748B]"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 overflow-y-auto py-1">
                                            <button type="button" @click="formData.invoice_id = ''; onInvoiceChange(); open = false; search = '';"
                                                    class="w-full text-left px-4 py-2 text-xs font-semibold text-gray-500 hover:bg-slate-50 transition-colors">
                                                — Pilih Faktur —
                                            </button>
                                            <template x-for="inv in filteredInvoices" :key="inv.id">
                                                <button type="button" 
                                                        @click="formData.invoice_id = inv.id; onInvoiceChange(); open = false; search = '';"
                                                        :class="formData.invoice_id == inv.id ? 'bg-blue-50 text-[#1E90FF] font-bold' : 'text-[#0F172A] hover:bg-slate-50'"
                                                        class="w-full text-left px-4 py-2.5 text-xs transition-colors flex flex-col space-y-0.5">
                                                    <span class="font-semibold" x-text="inv.invoice_number"></span>
                                                    <span class="text-[10px] text-[#64748B]" x-text="(inv.order?.customer?.company_name || 'N/A') + ' • Rp ' + Number(inv.total_amount).toLocaleString('id-ID')"></span>
                                                </button>
                                            </template>
                                            <div x-show="filteredInvoices.length === 0" class="px-4 py-3 text-center text-xs text-slate-400">
                                                Faktur tidak ditemukan.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="errors.invoice_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.invoice_id"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                                <input type="date" x-model="formData.payment_date"
                                       class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                       :class="errors.payment_date ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <div x-show="errors.payment_date" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.payment_date"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Jumlah <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       :value="CrudHelpers.formatInputRupiah(formData.amount)"
                                       @input="formData.amount = CrudHelpers.parseInputRupiah($event.target.value); $event.target.value = CrudHelpers.formatInputRupiah(formData.amount)"
                                       class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                       :class="errors.amount ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <div x-show="errors.amount" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.amount"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select x-model="formData.payment_method"
                                        class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                        :class="errors.payment_method ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                    <option value="">— Pilih —</option>
                                    <option value="cash">Tunai</option>
                                    <option value="bank_transfer">Transfer Bank</option>
                                    <option value="ewallet">E-Wallet</option>
                                </select>
                                <div x-show="errors.payment_method" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.payment_method"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nomor Referensi</label>
                                <input type="text" x-model="formData.reference_number" placeholder="TRF-xxxxx"
                                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] transition-all">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Bukti Pembayaran</label>
                                <input type="file" accept="image/*" @change="formData.proof_file = $event.target.files[0]"
                                       class="w-full px-4 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1E90FF] hover:file:bg-blue-100">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Catatan</label>
                                <textarea x-model="formData.notes" rows="2" placeholder="Catatan pembayaran..."
                                          class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div x-show="!loading" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                    <button @click="modalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="button" @click="submitCreate()" :disabled="submitting"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg hover:bg-blue-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                        <span x-text="submitting ? 'Menyimpan...' : 'Catat Pembayaran'">Catat Pembayaran</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Lightbox Modal -->
    <template x-teleport="body">
        <div x-show="lightboxOpen" x-cloak
             class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @keydown.escape.window="lightboxOpen = false">
            <div @click.outside="lightboxOpen = false"
                 class="relative max-w-4xl max-h-[85vh] overflow-hidden bg-white rounded-2xl border border-gray-150 shadow-2xl flex flex-col p-2"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0">
                <button @click="lightboxOpen = false" type="button" 
                        class="absolute top-4 right-4 z-10 flex items-center justify-center h-10 w-10 rounded-full bg-slate-900/60 hover:bg-slate-900/80 text-white transition-all shadow-md focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <img :src="lightboxImg" alt="Bukti Pembayaran" class="max-w-full max-h-[80vh] object-contain rounded-xl">
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function paymentManager() {
    return {
        modalOpen: false, submitting: false, loading: false,
        lightboxOpen: false, lightboxImg: '',
        invoicesList: [],
        formData: { invoice_id: '', payment_date: new Date().toISOString().split('T')[0], amount: '', payment_method: '', reference_number: '', proof_file: null, notes: '' },
        errors: {},

        async openCreate() {
            this.formData = { invoice_id: '', payment_date: new Date().toISOString().split('T')[0], amount: '', payment_method: '', reference_number: '', proof_file: null, notes: '' };
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData('/payments/create');
                this.invoicesList = data.invoices || [];
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat daftar faktur.', 'error');
            } finally {
                this.loading = false;
            }
        },

        onInvoiceChange() {
            const inv = this.invoicesList.find(i => i.id == this.formData.invoice_id);
            if (inv) {
                const totalPaid = (inv.payments || []).reduce((sum, p) => sum + Number(p.amount), 0);
                this.formData.amount = Math.max(0, inv.total_amount - totalPaid);
            }
        },

        async submitCreate() {
            this.submitting = true;
            this.errors = {};
            try {
                // Use FormData for file upload support
                const fd = new FormData();
                fd.append('invoice_id', this.formData.invoice_id);
                fd.append('payment_date', this.formData.payment_date);
                fd.append('amount', this.formData.amount);
                fd.append('payment_method', this.formData.payment_method);
                if (this.formData.reference_number) fd.append('reference_number', this.formData.reference_number);
                if (this.formData.proof_file) fd.append('proof_file', this.formData.proof_file);
                if (this.formData.notes) fd.append('notes', this.formData.notes);

                const result = await CrudHelpers.submitFormData('/payments', 'POST', fd);
                CrudHelpers.showToast(result.message || 'Pembayaran berhasil dicatat.', 'success');
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
        }
    };
}
</script>
@endpush
