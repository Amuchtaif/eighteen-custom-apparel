@extends('layouts.admin')

@section('title', 'Master Paket - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="packageManager()">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Master Paket</h1>
            <p class="text-sm text-[#64748B] mt-1">Kelola jenis paket prioritas pesanan produksi apparel custom.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Paket Baru
            </button>
        </div>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <!-- Search bar -->
        <div class="p-6 border-b border-[#E2E8F0]">
            <form action="{{ route('packages.index') }}" method="GET" class="max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama paket atau deskripsi..." 
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
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nama Paket</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Deskripsi</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Total Pesanan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($packages as $pkg)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($packages->currentPage() - 1) * $packages->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-[#0F172A]">{{ $pkg->name }}</td>
                            <td class="px-6 py-4 text-[#64748B] whitespace-normal min-w-[200px]">{{ $pkg->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-[#64748B] text-center font-semibold">{{ number_format($pkg->orders_count) }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($pkg->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button type="button" 
                                        @click="openEdit({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', '{{ addslashes($pkg->description ?? '') }}', {{ $pkg->is_active ? 'true' : 'false' }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                        title="Ubah Paket">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <button type="button" 
                                        @click="confirmDelete({{ $pkg->id }}, '{{ addslashes($pkg->name) }}')"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"
                                        title="Hapus Paket">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                    </svg>
                                    <span>Paket tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($packages->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">
                {{ $packages->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <template x-teleport="body">
        <div x-show="modalOpen" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="modalOpen = false">
    
            <div @click.outside="modalOpen = false"
                 class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[450px] overflow-hidden transform transition-all border border-gray-100"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 translate-y-4 opacity-0"
                 x-transition:enter-end="scale-100 translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 translate-y-0 opacity-100"
                 x-transition:leave-end="scale-95 translate-y-4 opacity-0">
    
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-bold text-[#0F172A] tracking-tight" x-text="modalTitle"></h3>
                    <button @click="modalOpen = false" type="button"
                            class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all duration-200">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
    
                <!-- Body -->
                <div class="px-6 py-5 space-y-4">
                    <form id="packageForm" @submit.prevent="submitForm()">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Paket <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="formData.name" x-ref="nameInput"
                                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all"
                                       placeholder="Contoh: Premium"
                                       :class="errors.name ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <div x-show="errors.name" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.name"></div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Deskripsi</label>
                                <textarea name="description" x-model="formData.description" rows="3"
                                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all resize-none"
                                       placeholder="Tulis deskripsi paket..."></textarea>
                                <div x-show="errors.description" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.description"></div>
                            </div>

                            <div class="flex items-center space-x-3 pt-2">
                                <input type="checkbox" id="is_active" x-model="formData.is_active"
                                       class="h-4.5 w-4.5 rounded border-gray-300 text-[#1E90FF] focus:ring-[#1E90FF] cursor-pointer">
                                <label for="is_active" class="text-sm font-semibold text-[#0F172A] cursor-pointer select-none">Aktifkan Paket</label>
                            </div>
                        </div>
                    </form>
                </div>
    
                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80">
                    <button @click="modalOpen = false" type="button"
                            class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
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
    </template>
    
    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
        <div x-show="deleteOpen" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
    
            <div @click.outside="deleteOpen = false"
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
                    <p class="text-sm text-[#64748B] leading-relaxed">
                        Apakah Anda yakin ingin menghapus paket <strong class="text-[#0F172A]" x-text="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
    
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                    <button @click="deleteOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button @click="executeDelete()" :disabled="submitting" type="button"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm disabled:opacity-50">
                        <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function packageManager() {
    return {
        modalOpen: false,
        deleteOpen: false,
        modalTitle: '',
        editId: null,
        deleteName: '',
        deleteId: null,
        submitting: false,
        formData: { name: '', description: '', is_active: true },
        errors: {},

        openCreate() {
            this.editId = null;
            this.modalTitle = 'Buat Paket Baru';
            this.formData = { name: '', description: '', is_active: true };
            this.errors = {};
            this.modalOpen = true;
            this.$nextTick(() => this.$refs.nameInput?.focus());
        },

        openEdit(id, name, description, isActive) {
            this.editId = id;
            this.modalTitle = 'Ubah Paket';
            this.formData = { name: name, description: description, is_active: isActive };
            this.errors = {};
            this.modalOpen = true;
            this.$nextTick(() => this.$refs.nameInput?.focus());
        },

        async submitForm() {
            this.submitting = true;
            this.errors = {};

            try {
                const url = this.editId 
                    ? `/packages/${this.editId}`
                    : '/packages';
                const method = this.editId ? 'PUT' : 'POST';

                const result = await CrudHelpers.submitJson(url, method, this.formData);
                
                CrudHelpers.showToast(result.message || 'Operasi berhasil.', 'success');
                this.modalOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                if (err.status === 422 && err.errors) {
                    Object.keys(err.errors).forEach(key => {
                        this.errors[key] = err.errors[key][0];
                    });
                } else {
                    CrudHelpers.showToast(err.data?.message || 'Terjadi kesalahan.', 'error');
                }
            } finally {
                this.submitting = false;
            }
        },

        confirmDelete(id, name) {
            this.deleteId = id;
            this.deleteName = name;
            this.deleteOpen = true;
        },

        async executeDelete() {
            this.submitting = true;
            try {
                const result = await CrudHelpers.deleteResource(`/packages/${this.deleteId}`);
                CrudHelpers.showToast(result.message || 'Paket berhasil dihapus.', 'success');
                this.deleteOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                CrudHelpers.showToast(err.data?.message || 'Gagal menghapus paket.', 'error');
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush
