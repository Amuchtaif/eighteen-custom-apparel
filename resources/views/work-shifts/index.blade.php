@extends('layouts.admin')

@section('title', 'Shift Kerja - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="workShiftManager()">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Shift Kerja</h1>
            <p class="text-sm text-[#64748B] mt-1">Kelola jam operasional dan toleransi keterlambatan pegawai.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Shift
            </button>
        </div>
    </div>

    <!-- Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E2E8F0] text-left">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] w-12 text-center">No</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nama Shift</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Jam Masuk</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Jam Pulang</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Toleransi (Menit)</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($shifts as $shift)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($shifts->currentPage() - 1) * $shifts->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-[#0F172A]">{{ $shift->name }}</td>
                            <td class="px-6 py-4 text-center font-mono text-[#0F172A]">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                            <td class="px-6 py-4 text-center font-mono text-[#0F172A]">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-amber-600">{{ $shift->late_tolerance_minutes }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($shift->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button type="button" 
                                        @click="openEdit({{ $shift->id }}, '{{ addslashes($shift->name) }}', '{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}', '{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}', {{ $shift->late_tolerance_minutes }}, {{ $shift->is_active ? 'true' : 'false' }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                        title="Ubah Shift">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <button type="button" 
                                        @click="confirmDelete({{ $shift->id }}, '{{ addslashes($shift->name) }}')"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"
                                        title="Hapus Shift">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-clock text-2xl text-gray-400"></i>
                                    <span>Data shift kerja belum tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($shifts->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">
                {{ $shifts->links() }}
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
             x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" @click.away="closeModal()">
                <div class="px-6 py-4 border-b border-[#E2E8F0] flex justify-between items-center bg-[#F8FAFC]">
                    <h3 class="text-lg font-bold text-[#0F172A]" x-text="isEdit ? 'Ubah Shift Kerja' : 'Tambah Shift Kerja'"></h3>
                    <button @click="closeModal()" class="text-[#64748B] hover:text-[#0F172A] transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                
                <form :action="formAction" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit">
                        @method('PUT')
                    </template>
    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Shift <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" x-model="formData.name" required placeholder="Contoh: Shift Reguler"
                                class="w-full px-4 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_time" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Jam Masuk <span class="text-red-500">*</span></label>
                                <input type="time" name="start_time" id="start_time" x-model="formData.start_time" required
                                    class="w-full px-4 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Jam Pulang <span class="text-red-500">*</span></label>
                                <input type="time" name="end_time" id="end_time" x-model="formData.end_time" required
                                    class="w-full px-4 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 items-end">
                            <div>
                                <label for="late_tolerance_minutes" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Toleransi (Menit) <span class="text-red-500">*</span></label>
                                <input type="number" name="late_tolerance_minutes" id="late_tolerance_minutes" x-model="formData.late_tolerance_minutes" required min="0"
                                    class="w-full px-4 py-2 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                            </div>
                            <div class="pb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1E90FF]"></div>
                                    <span class="ms-3 text-sm font-semibold text-[#0F172A]">Status Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
    
                    <div class="mt-8 flex justify-end space-x-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-white border border-[#E2E8F0] text-[#64748B] font-semibold rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#1E90FF] text-white font-bold rounded-lg shadow-sm hover:bg-blue-600 transition-colors text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
    
    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
        <div x-show="deleteModalOpen" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6" @click.away="closeDeleteModal()">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#0F172A] mb-2">Hapus Shift Kerja?</h3>
                <p class="text-sm text-[#64748B] mb-6">Apakah Anda yakin ingin menghapus <span class="font-bold text-[#0F172A]" x-text="deleteItemName"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                
                <form :action="deleteAction" method="POST" class="flex flex-col sm:flex-row gap-3 justify-center">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="closeDeleteModal()" class="px-4 py-2.5 bg-white border border-[#E2E8F0] text-[#64748B] font-semibold rounded-lg hover:bg-gray-50 transition-colors text-sm w-full sm:w-auto">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-red-600 text-white font-bold rounded-lg shadow-sm hover:bg-red-700 transition-colors text-sm w-full sm:w-auto">
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
    function workShiftManager() {
        return {
            modalOpen: false,
            deleteModalOpen: false,
            isEdit: false,
            formAction: '',
            deleteAction: '',
            deleteItemName: '',
            formData: {
                name: '',
                start_time: '',
                end_time: '',
                late_tolerance_minutes: 15,
                is_active: true
            },
            
            openCreate() {
                this.isEdit = false;
                this.formAction = '{{ route('work-shifts.store') }}';
                this.formData = {
                    name: '',
                    start_time: '',
                    end_time: '',
                    late_tolerance_minutes: 15,
                    is_active: true
                };
                this.modalOpen = true;
            },
            
            openEdit(id, name, start, end, tol, isActive) {
                this.isEdit = true;
                this.formAction = `/work-shifts/${id}`;
                this.formData = {
                    name: name,
                    start_time: start,
                    end_time: end,
                    late_tolerance_minutes: tol,
                    is_active: isActive
                };
                this.modalOpen = true;
            },
            
            closeModal() {
                this.modalOpen = false;
            },
            
            confirmDelete(id, name) {
                this.deleteItemName = name;
                this.deleteAction = `/work-shifts/${id}`;
                this.deleteModalOpen = true;
            },
            
            closeDeleteModal() {
                this.deleteModalOpen = false;
            }
        }
    }
</script>
@endsection
