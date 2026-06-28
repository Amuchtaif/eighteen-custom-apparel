@extends('layouts.admin')

@section('title', 'Pelanggan - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="customerManager()">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Basis Data Pelanggan</h1>
            <p class="text-sm text-[#64748B] mt-1">Kelola informasi profil pelanggan, rincian kontak, dan catatan.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Pelanggan
            </button>
        </div>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <!-- Search bar -->
        <div class="p-6 border-b border-[#E2E8F0]">
            <form action="{{ route('customers.index') }}" method="GET" class="max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan perusahaan, nama, email..." 
                        class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                    <button type="submit" class="absolute right-2.5 top-2.5 text-[#64748B] hover:text-[#0F172A]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E2E8F0] text-left">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] w-12 text-center">No</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nama Perusahaan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Narahubung</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Telepon</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Email</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Kota</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-semibold text-[#0F172A]">{{ $customer->company_name }}</td>
                            <td class="px-6 py-4 text-[#0F172A]">{{ $customer->contact_person }}</td>
                            <td class="px-6 py-4 text-[#64748B]">{{ $customer->phone }}</td>
                            <td class="px-6 py-4 text-[#64748B]">{{ $customer->email }}</td>
                            <td class="px-6 py-4 text-[#64748B]">{{ $customer->city }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button type="button" 
                                        @click="openDetail({{ $customer->id }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 transition-colors"
                                        title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                <button type="button" 
                                        @click="openEdit({{ $customer->id }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                        title="Ubah Pelanggan">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <button type="button" 
                                        @click="confirmDelete({{ $customer->id }}, '{{ addslashes($customer->company_name) }}')"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"
                                        title="Hapus Pelanggan">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                    </svg>
                                    <span>Pelanggan tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($customers->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
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

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
                <h3 class="text-lg font-bold text-[#0F172A] tracking-tight" x-text="modalTitle"></h3>
                <button @click="modalOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all duration-200">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                <!-- Loading -->
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                    <p class="mt-4 text-sm text-gray-500 font-medium">Memuat data...</p>
                </div>

                <form x-show="!loading" @submit.prevent="submitForm()" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.company_name" placeholder="PT. Contoh Jaya"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.company_name ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.company_name" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.company_name"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Narahubung <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.contact_person" placeholder="Budi Santoso"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.contact_person ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.contact_person" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.contact_person"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Telepon <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.phone" placeholder="08123456789"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.phone ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.phone" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.phone"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" x-model="formData.email" placeholder="budi@contoh.com"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.email ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.email" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.email"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Kota <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.city" placeholder="Jakarta"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.city ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.city" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.city"></div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Alamat <span class="text-red-500">*</span></label>
                            <textarea x-model="formData.address" rows="2" placeholder="Jl. Contoh No. 123"
                                      class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all resize-none"
                                      :class="errors.address ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'"></textarea>
                            <div x-show="errors.address" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.address"></div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Catatan</label>
                            <textarea x-model="formData.notes" rows="2" placeholder="Catatan tambahan..."
                                      class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] transition-all resize-none"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div x-show="!loading" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                <button @click="modalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" @click="submitForm()" :disabled="submitting"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg hover:bg-blue-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan'">Simpan</span>
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
             class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[600px] overflow-hidden transform transition-all border border-gray-100 flex flex-col"
             style="max-height: 90vh;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
                <h3 class="text-lg font-bold text-[#0F172A] tracking-tight">Detail Pelanggan</h3>
                <button @click="detailOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all duration-200">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                    <p class="mt-4 text-sm text-gray-500 font-medium">Memuat data...</p>
                </div>
                <div x-show="!loading" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Nama Perusahaan</p>
                            <p class="text-sm font-bold text-[#0F172A] mt-0.5" x-text="detailData.company_name || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Narahubung</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.contact_person || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Telepon</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.phone || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Email</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.email || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Kota</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.city || '-'"></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Alamat</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.address || '-'"></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Catatan</p>
                            <p class="text-sm font-medium text-[#0F172A] mt-0.5" x-text="detailData.notes || 'Tidak ada catatan.'"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                <button @click="detailOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
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
                <p class="text-sm text-[#64748B] leading-relaxed">
                    Apakah Anda yakin ingin menghapus pelanggan <strong class="text-[#0F172A]" x-text="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                <button @click="deleteModalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button @click="executeDelete()" :disabled="submitting" type="button"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm disabled:opacity-50">
                    <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function customerManager() {
    return {
        modalOpen: false, detailOpen: false, deleteModalOpen: false,
        modalTitle: '', editId: null, deleteName: '', deleteId: null,
        submitting: false, loading: false,
        formData: { company_name: '', contact_person: '', phone: '', email: '', address: '', city: '', notes: '' },
        detailData: {},
        errors: {},

        emptyForm() {
            return { company_name: '', contact_person: '', phone: '', email: '', address: '', city: '', notes: '' };
        },

        openCreate() {
            this.editId = null;
            this.modalTitle = 'Tambah Pelanggan Baru';
            this.formData = this.emptyForm();
            this.errors = {};
            this.loading = false;
            this.modalOpen = true;
        },

        async openEdit(id) {
            this.editId = id;
            this.modalTitle = 'Ubah Pelanggan';
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData(`/customers/${id}/edit`);
                this.formData = {
                    company_name: data.company_name || '',
                    contact_person: data.contact_person || '',
                    phone: data.phone || '',
                    email: data.email || '',
                    address: data.address || '',
                    city: data.city || '',
                    notes: data.notes || '',
                };
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat data pelanggan.', 'error');
                this.modalOpen = false;
            } finally {
                this.loading = false;
            }
        },

        async openDetail(id) {
            this.loading = true;
            this.detailOpen = true;
            try {
                this.detailData = await CrudHelpers.fetchData(`/customers/${id}`);
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat detail pelanggan.', 'error');
                this.detailOpen = false;
            } finally {
                this.loading = false;
            }
        },

        async submitForm() {
            this.submitting = true;
            this.errors = {};
            try {
                const url = this.editId ? `/customers/${this.editId}` : '/customers';
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

        async executeDelete() {
            this.submitting = true;
            try {
                const result = await CrudHelpers.deleteResource(`/customers/${this.deleteId}`);
                CrudHelpers.showToast(result.message || 'Pelanggan berhasil dihapus.', 'success');
                this.deleteModalOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                CrudHelpers.showToast(err.data?.message || 'Gagal menghapus pelanggan.', 'error');
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush
