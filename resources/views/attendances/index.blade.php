@extends('layouts.admin')

@section('title', 'Log Absensi - Eighteen ERP')

@section('content')
<div class="space-y-8" x-data="attendanceManager()">
    <!-- Header Page -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Log Absensi</h1>
        <p class="text-sm text-[#64748B] mt-1">Pantau kehadiran karyawan, lokasi GPS, foto selfie, dan status keterlambatan.</p>
    </div>

    <!-- Filter & Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <!-- Search bar -->
        <div class="p-6 border-b border-[#E2E8F0] flex flex-col sm:flex-row gap-4 items-center justify-between">
            <form action="{{ route('attendances.index') }}" method="GET" class="w-full max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama atau kode karyawan..." 
                        class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                    <button type="submit" class="absolute right-2.5 top-2.5 text-[#64748B] hover:text-[#0F172A]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                </div>
            </form>
            
            <div class="text-sm text-[#64748B] font-semibold">
                Total Record: {{ $attendances->total() }}
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E2E8F0] text-left">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] w-12 text-center">No</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Karyawan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Absen Masuk</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Absen Keluar</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Kantor & Shift</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($attendances as $att)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-[#0F172A]">{{ $att->employee->user->name ?? 'Pengguna Dihapus' }}</div>
                                <div class="text-xs text-[#64748B] font-mono">{{ $att->employee->employee_code ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($att->attendance_status === 'Hadir')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">Hadir</span>
                                @elseif($att->attendance_status === 'Terlambat')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">Terlambat</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">{{ $att->attendance_status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[#0F172A]">
                                {{ $att->check_in_time ? $att->check_in_time->format('d M Y H:i:s') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-[#0F172A]">
                                {{ $att->check_out_time ? $att->check_out_time->format('d M Y H:i:s') : 'Belum absen keluar' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-[#64748B]">
                                <div>{{ $att->employee->office->name ?? '-' }}</div>
                                <div>{{ $att->employee->workShift->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $detailData = [
                                        'employee_name' => $att->employee->user->name ?? '-',
                                        'status' => $att->attendance_status,
                                        'office' => $att->employee->office->name ?? '-',
                                        'shift' => $att->employee->workShift->name ?? '-',
                                        'check_in_time' => $att->check_in_time ? $att->check_in_time->format('d M Y H:i:s') : '-',
                                        'check_in_distance' => $att->check_in_distance,
                                        'check_in_photo' => $att->check_in_photo ? asset('storage/' . $att->check_in_photo) : null,
                                        'check_out_time' => $att->check_out_time ? $att->check_out_time->format('d M Y H:i:s') : '-',
                                        'check_out_distance' => $att->check_out_distance,
                                        'check_out_photo' => $att->check_out_photo ? asset('storage/' . $att->check_out_photo) : null,
                                        'notes' => $att->notes
                                    ];
                                @endphp
                                <button type="button" @click='openDetail(@json($detailData))'
                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors text-xs font-bold">
                                    <i class="fa-solid fa-eye mr-1.5"></i> Lihat
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-user-clock text-3xl text-gray-300"></i>
                                    <span>Log absensi tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($attendances->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

    <!-- Detail Modal -->
    <template x-teleport="body">
    <div x-show="modalOpen" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalOpen = false">
        
        <div @click.outside="modalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-[95%] max-w-2xl overflow-hidden transform transition-all border border-gray-100 flex flex-col"
             style="max-height: 90vh;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 translate-y-4 opacity-0" x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="scale-100 translate-y-0 opacity-100" x-transition:leave-end="scale-95 translate-y-4 opacity-0">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-[#F8FAFC] flex-shrink-0">
                <h3 class="text-lg font-bold text-[#0F172A]">Detail Absensi Karyawan</h3>
                <button @click="modalOpen = false" type="button" class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-[#0F172A] hover:bg-gray-200 transition-all"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs font-semibold text-[#64748B] uppercase tracking-wider mb-1">Nama Karyawan</div>
                        <div class="text-sm font-bold text-[#0F172A]" x-text="detail.employee_name"></div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-[#64748B] uppercase tracking-wider mb-1">Status Kehadiran</div>
                        <div class="text-sm font-bold" :class="detail.status === 'Hadir' ? 'text-emerald-600' : 'text-amber-600'" x-text="detail.status"></div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-[#64748B] uppercase tracking-wider mb-1">Lokasi Kantor</div>
                        <div class="text-sm font-medium text-[#0F172A]" x-text="detail.office"></div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-[#64748B] uppercase tracking-wider mb-1">Shift Kerja</div>
                        <div class="text-sm font-medium text-[#0F172A]" x-text="detail.shift"></div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Photos and Validation -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Check In Box -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h4 class="text-sm font-bold text-[#0F172A] mb-3 border-b border-gray-200 pb-2">Data Masuk</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[#64748B]">Waktu</span>
                                <span class="font-medium text-[#0F172A]" x-text="detail.check_in_time"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#64748B]">Jarak (Geofence)</span>
                                <span class="font-mono font-medium text-[#0F172A]" x-text="detail.check_in_distance != null ? detail.check_in_distance + ' m' : '-'"></span>
                            </div>
                            <div>
                                <span class="text-[#64748B] block mb-2">Foto Selfie</span>
                                <template x-if="detail.check_in_photo">
                                    <img :src="detail.check_in_photo" alt="Selfie Masuk" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                                </template>
                                <template x-if="!detail.check_in_photo">
                                    <div class="w-full h-40 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">Tidak ada foto</div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Check Out Box -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h4 class="text-sm font-bold text-[#0F172A] mb-3 border-b border-gray-200 pb-2">Data Pulang</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[#64748B]">Waktu</span>
                                <span class="font-medium text-[#0F172A]" x-text="detail.check_out_time"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#64748B]">Jarak (Geofence)</span>
                                <span class="font-mono font-medium text-[#0F172A]" x-text="detail.check_out_distance != null ? detail.check_out_distance + ' m' : '-'"></span>
                            </div>
                            <div>
                                <span class="text-[#64748B] block mb-2">Foto Selfie</span>
                                <template x-if="detail.check_out_photo">
                                    <img :src="detail.check_out_photo" alt="Selfie Pulang" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                                </template>
                                <template x-if="!detail.check_out_photo">
                                    <div class="w-full h-40 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">Tidak ada foto</div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div x-show="detail.notes">
                    <div class="text-xs font-semibold text-[#64748B] uppercase tracking-wider mb-1">Catatan Tambahan</div>
                    <div class="text-sm text-[#0F172A] bg-yellow-50 p-3 rounded-lg border border-yellow-100" x-text="detail.notes"></div>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-[#F8FAFC] flex justify-end flex-shrink-0">
                <button @click="modalOpen = false" type="button" class="px-5 py-2.5 bg-white border border-[#E2E8F0] text-[#0F172A] font-bold rounded-lg shadow-sm hover:bg-gray-50 transition-colors text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    </template>
</div>

<script>
    function attendanceManager() {
        return {
            modalOpen: false,
            detail: {},
            openDetail(data) {
                this.detail = data;
                this.modalOpen = true;
            }
        }
    }
</script>
@endsection
