@extends('layouts.admin')

@section('title', 'Master Jabatan - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="positionManager()">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Master Jabatan (Position)</h1>
            <p class="text-sm text-[#64748B] mt-1">Kelola jabatan karyawan, deskripsi tugas, dan status keaktifan jabatan.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Jabatan
            </button>
        </div>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#E2E8F0]">
            <form action="{{ route('settings.positions.index') }}" method="GET" class="max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama jabatan, deskripsi..." 
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
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nama Jabatan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Deskripsi</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center w-32">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($positions as $position)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($positions->currentPage() - 1) * $positions->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-semibold text-[#0F172A]">{{ $position->name }}</td>
                            <td class="px-6 py-4 text-[#64748B]">{{ $position->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($position->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button type="button" @click="openEdit({{ $position->id }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Ubah Jabatan">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <button type="button" @click="confirmDelete({{ $position->id }}, '{{ addslashes($position->name) }}')"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors" title="Hapus Jabatan">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-briefcase text-3xl text-gray-300"></i>
                                    <span>Jabatan tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($positions->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">{{ $positions->links() }}</div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <template x-teleport="body">
    <div x-show="modalOpen" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalOpen = false">
        <div @click.outside="modalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-[500px] overflow-hidden transform transition-all border border-gray-100 flex flex-col"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
                <h3 class="text-lg font-bold text-[#0F172A]" x-text="modalTitle"></h3>
                <button @click="modalOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <div class="flex-1 px-6 py-5 overflow-y-auto">
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                    <p class="mt-4 text-sm text-gray-500 font-medium">Memuat data...</p>
                </div>
                <form x-show="!loading" @submit.prevent="submitForm()" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" x-model="formData.name" placeholder="Contoh: Supervisor Produksi"
                               class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                               :class="errors.name ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                        <div x-show="errors.name" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.name"></div>
                    </div>
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Deskripsi</label>
                        <textarea x-model="formData.description" placeholder="Penjelasan singkat mengenai jabatan ini" rows="3"
                                  class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                  :class="errors.description ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'"></textarea>
                        <div x-show="errors.description" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.description"></div>
                    </div>
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select x-model="formData.is_active"
                                class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                :class="errors.is_active ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        <div x-show="errors.is_active" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.is_active"></div>
                    </div>
                </form>
            </div>

            <div x-show="!loading" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
                <button @click="modalOpen = false" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                <button type="button" @click="submitForm()" :disabled="submitting"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg hover:bg-blue-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i x-show="submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan'">Simpan</span>
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
                <p class="text-sm text-[#64748B] leading-relaxed">Apakah Anda yakin ingin menghapus jabatan <strong class="text-[#0F172A]" x-text="deleteName"></strong>?</p>
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
function positionManager() {
    return {
        modalOpen: false, deleteModalOpen: false,
        modalTitle: '', editId: null, deleteName: '', deleteId: null,
        submitting: false, loading: false,
        formData: { name: '', description: '', is_active: '1' },
        errors: {},

        emptyForm() {
            return { name: '', description: '', is_active: '1' };
        },

        openCreate() {
            this.editId = null;
            this.modalTitle = 'Tambah Jabatan Baru';
            this.formData = this.emptyForm();
            this.errors = {};
            this.modalOpen = true;
        },

        async openEdit(id) {
            this.editId = id;
            this.modalTitle = 'Ubah Jabatan';
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData(`/settings/positions/${id}/edit`);
                this.formData = {
                    name: data.name,
                    description: data.description || '',
                    is_active: data.is_active ? '1' : '0'
                };
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat data jabatan.', 'error');
                this.modalOpen = false;
            } finally {
                this.loading = false;
            }
        },

        async submitForm() {
            this.submitting = true;
            this.errors = {};
            try {
                const url = this.editId ? `/settings/positions/${this.editId}` : '/settings/positions';
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
                const result = await CrudHelpers.deleteResource(`/settings/positions/${this.deleteId}`);
                CrudHelpers.showToast(result.message || 'Jabatan berhasil dihapus.', 'success');
                this.deleteModalOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                CrudHelpers.showToast(err.data?.message || 'Gagal menghapus jabatan.', 'error');
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush
