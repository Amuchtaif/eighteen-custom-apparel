@extends('layouts.admin')

@section('title', 'Karyawan - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="employeeManager()">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Registri Karyawan</h1>
            <p class="text-sm text-[#64748B] mt-1">Kelola profil karyawan, jabatan, departemen, dan status kepegawaian.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Karyawan
            </button>
        </div>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#E2E8F0]">
            <form action="{{ route('employees.index') }}" method="GET" class="max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode, nama, jabatan, departemen..." 
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
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Kode</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nama</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Jabatan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Departemen</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-[#64748B]">{{ $employee->employee_code }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-[#0F172A]">{{ $employee->user->name ?? '-' }}</div>
                                <div class="text-xs text-[#64748B] mt-0.5">{{ $employee->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-[#0F172A]">{{ $employee->position?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-[#64748B]">{{ $employee->department?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($employee->status === 'active')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500">{{ ucfirst($employee->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button type="button" @click="openEdit({{ $employee->id }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Ubah Karyawan">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <button type="button" @click="confirmDelete({{ $employee->id }}, '{{ addslashes($employee->user->name ?? $employee->employee_code) }}')"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors" title="Hapus Karyawan">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-user-tie text-3xl text-gray-300"></i>
                                    <span>Karyawan tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($employees->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">{{ $employees->links() }}</div>
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
                <form x-show="!loading" @submit.prevent="submitForm()" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Karyawan <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.name" placeholder="Nama Lengkap"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.name ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.name" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.name"></div>
                        </div>
                        <!-- Email -->
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Email Akses <span class="text-red-500">*</span></label>
                            <input type="email" x-model="formData.email" placeholder="email@eighteen.com"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.email ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.email" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.email"></div>
                        </div>
                        <!-- Password -->
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">
                                Password <span x-show="!editId" class="text-red-500">*</span>
                            </label>
                            <input type="password" x-model="formData.password" :placeholder="editId ? 'Biarkan kosong jika tidak diubah' : 'Minimal 8 karakter'"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.password ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.password" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.password"></div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Peran (Role) <span class="text-red-500">*</span></label>
                            <select x-model="formData.role_id"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                    :class="errors.role_id ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <option value="">— Pilih Peran Sistem —</option>
                                <template x-for="r in rolesList" :key="r.id">
                                    <option :value="r.id" x-text="r.name"></option>
                                </template>
                            </select>
                            <div x-show="errors.role_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.role_id"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Kode Karyawan <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.employee_code" readonly placeholder="Otomatis digenerate"
                                   class="w-full px-4 py-2.5 bg-gray-50 border rounded-lg text-sm text-gray-500 focus:outline-none transition-all font-mono cursor-not-allowed border-[#E2E8F0]">
                            <div x-show="errors.employee_code" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.employee_code"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                            <select x-model="formData.position_id"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                    :class="errors.position_id ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <option value="">— Pilih Jabatan —</option>
                                <template x-for="p in positionsList" :key="p.id">
                                    <option :value="p.id" x-text="p.name"></option>
                                </template>
                            </select>
                            <div x-show="errors.position_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.position_id"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Departemen <span class="text-red-500">*</span></label>
                            <select x-model="formData.department_id"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                    :class="errors.department_id ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <option value="">— Pilih Departemen —</option>
                                <template x-for="d in departmentsList" :key="d.id">
                                    <option :value="d.id" x-text="d.name"></option>
                                </template>
                            </select>
                            <div x-show="errors.department_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.department_id"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Tanggal Bergabung <span class="text-red-500">*</span></label>
                            <input type="date" x-model="formData.join_date"
                                   class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                   :class="errors.join_date ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                            <div x-show="errors.join_date" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.join_date"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select x-model="formData.status"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                    :class="errors.status ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <option value="">— Pilih Status —</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                                <option value="resigned">Mengundurkan Diri</option>
                            </select>
                            <div x-show="errors.status" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.status"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Penempatan Kantor <span class="text-red-500">*</span></label>
                            <select x-model="formData.office_id"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                    :class="errors.office_id ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <option value="">— Pilih Lokasi Kantor —</option>
                                <template x-for="o in officesList" :key="o.id">
                                    <option :value="o.id" x-text="o.name"></option>
                                </template>
                            </select>
                            <div x-show="errors.office_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.office_id"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#0F172A] mb-1.5">Shift Kerja <span class="text-red-500">*</span></label>
                            <select x-model="formData.work_shift_id"
                                    class="w-full px-4 py-2.5 bg-white border rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 transition-all"
                                    :class="errors.work_shift_id ? 'border-red-400 focus:ring-red-400' : 'border-[#E2E8F0] focus:ring-[#1E90FF]'">
                                <option value="">— Pilih Shift Kerja —</option>
                                <template x-for="s in workShiftsList" :key="s.id">
                                    <option :value="s.id" x-text="s.name"></option>
                                </template>
                            </select>
                            <div x-show="errors.work_shift_id" class="text-xs text-red-600 mt-1 font-medium" x-text="errors.work_shift_id"></div>
                        </div>
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
                <p class="text-sm text-[#64748B] leading-relaxed">Apakah Anda yakin ingin menghapus karyawan <strong class="text-[#0F172A]" x-text="deleteName"></strong>?</p>
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
function employeeManager() {
    return {
        modalOpen: false, deleteModalOpen: false,
        modalTitle: '', editId: null, deleteName: '', deleteId: null,
        submitting: false, loading: false,
        usersList: [], rolesList: [], positionsList: [], departmentsList: [], officesList: [], workShiftsList: [],
        formData: { name: '', email: '', password: '', role_id: '', employee_code: '', position_id: '', department_id: '', join_date: '', status: 'active', office_id: '', work_shift_id: '' },
        errors: {},

        emptyForm() {
            return { name: '', email: '', password: '', role_id: '', employee_code: '', position_id: '', department_id: '', join_date: '', status: 'active', office_id: '', work_shift_id: '' };
        },

        async openCreate() {
            this.editId = null;
            this.modalTitle = 'Tambah Karyawan Baru';
            this.formData = this.emptyForm();
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData('/employees/create');
                this.rolesList = data.roles || [];
                this.positionsList = data.positions || [];
                this.departmentsList = data.departments || [];
                this.officesList = data.offices || [];
                this.workShiftsList = data.workShifts || [];
                this.formData.employee_code = data.employee_code || '';
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat daftar pengguna.', 'error');
            } finally {
                this.loading = false;
            }
        },

        async openEdit(id) {
            this.editId = id;
            this.modalTitle = 'Ubah Karyawan';
            this.errors = {};
            this.loading = true;
            this.modalOpen = true;
            try {
                const data = await CrudHelpers.fetchData(`/employees/${id}/edit`);
                const e = data.employee;
                this.rolesList = data.roles || [];
                this.positionsList = data.positions || [];
                this.departmentsList = data.departments || [];
                this.officesList = data.offices || [];
                this.workShiftsList = data.workShifts || [];
                this.formData = {
                    name: e.user?.name || '',
                    email: e.user?.email || '',
                    password: '',
                    role_id: e.user?.role_id || '',
                    employee_code: e.employee_code || '',
                    position_id: e.position_id || '',
                    department_id: e.department_id || '',
                    join_date: e.join_date ? e.join_date.split('T')[0] : '',
                    status: e.status || 'active',
                    office_id: e.office_id || '',
                    work_shift_id: e.work_shift_id || '',
                };
            } catch (err) {
                CrudHelpers.showToast('Gagal memuat data karyawan.', 'error');
                this.modalOpen = false;
            } finally {
                this.loading = false;
            }
        },

        async submitForm() {
            this.submitting = true;
            this.errors = {};
            try {
                const url = this.editId ? `/employees/${this.editId}` : '/employees';
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
                const result = await CrudHelpers.deleteResource(`/employees/${this.deleteId}`);
                CrudHelpers.showToast(result.message || 'Karyawan berhasil dihapus.', 'success');
                this.deleteModalOpen = false;
                CrudHelpers.refreshTable();
            } catch (err) {
                CrudHelpers.showToast(err.data?.message || 'Gagal menghapus karyawan.', 'error');
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush
