@extends('layouts.employee-mobile')

@section('title', 'Dashboard Karyawan - Eighteen ERP')

@section('content')
<div class="space-y-5">

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 1: Employee Profile Card
    ═══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl border border-[#ecf0f6] shadow-[rgba(0,0,0,0.04)_0px_2px_10px_0px] overflow-hidden animate-fade-in-up" id="employee-profile-card">
        <div class="p-4">
            <div class="flex items-start justify-between">
                {{-- Left: Avatar + Info --}}
                <div class="flex items-center space-x-3.5">
                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        <div class="h-14 w-14 rounded-full bg-gradient-to-br from-[#3da8ff] to-[#1E90FF] flex items-center justify-center shadow-md">
                            <span class="text-lg font-bold text-white uppercase tracking-tight">{{ substr($employee->user->name ?? 'NA', 0, 2) }}</span>
                        </div>
                        {{-- Online indicator --}}
                        <div class="absolute -bottom-0.5 -right-0.5 h-4 w-4 rounded-full bg-[#1E90FF] border-[2.5px] border-white animate-pulse"></div>
                    </div>
                    {{-- Name & Jabatan --}}
                    <div class="min-w-0">
                        <h1 class="text-base font-bold text-[#2d2e34] truncate leading-tight">{{ $employee->user->name ?? 'Nama Karyawan' }}</h1>
                        <p class="text-xs text-[#8e919b] mt-1 leading-tight">{{ $employee->position->name ?? 'Staff' }}</p>
                    </div>
                </div>

                {{-- Right: Last Attendance Badge --}}
                <div class="flex-shrink-0 text-right">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#8e919b] block leading-none">Absen</span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#8e919b] block leading-none mt-0.5">Terakhir</span>
                    @if($todayAttendance)
                        <p class="text-xs font-bold text-[#2d2e34] mt-1 font-mono">{{ $todayAttendance->check_in_time->format('H:i') }}</p>
                        <span class="inline-block text-[9px] font-bold uppercase px-2 py-0.5 rounded-full mt-1
                            {{ $todayAttendance->check_out_time ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-blue-50 text-[#1E90FF] border border-blue-100' }}">
                            {{ $todayAttendance->check_out_time ? 'Keluar' : 'Masuk' }}
                        </span>
                    @else
                        <p class="text-xs font-semibold text-[#8e919b] mt-1">--:--</p>
                        <span class="inline-block text-[9px] font-bold uppercase px-2 py-0.5 rounded-full bg-[#ecf0f6] text-[#8e919b] border border-[#c5c6cd]/30 mt-1">Belum</span>
                    @endif
                </div>
            </div>

            {{-- GPS Zone Status --}}
            <div class="mt-3.5 flex items-center space-x-2 bg-blue-50 rounded-xl px-3 py-2 border border-blue-100" id="employee-office-location">
                <div class="h-5 w-5 rounded-full bg-[#1E90FF]/10 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-location-dot text-[10px] text-[#1E90FF]"></i>
                </div>
                <span class="text-xs font-semibold text-[#1E90FF]">Lokasi Kerja:</span>
                <span class="text-[10px] font-bold text-white bg-[#1E90FF] px-2 py-0.5 rounded-full">{{ $employee->office->name ?? 'Belum Ditentukan' }}</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 2: Absen Masuk/Out Button
    ═══════════════════════════════════════════════════════════ --}}
    <script>
        function attendanceClock() {
            return {
                loading: false,
                lat: '',
                lng: '',
                showLocationModal: false,
                selectedOfficeId: '{{ $employee->office_id ?? "" }}',
                startAttendance() {
                    this.showLocationModal = true;
                },
                confirmLocation() {
                    this.showLocationModal = false;
                    this.openCamera();
                },
                openCamera() {
                    this.loading = true;
                    
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.lat = position.coords.latitude;
                                this.lng = position.coords.longitude;
                                
                                // Trigger camera
                                this.$refs.cameraInput.click();
                                this.loading = false;
                            },
                            (error) => {
                                this.loading = false;
                                alert("Gagal mendapatkan lokasi GPS. Pastikan izin lokasi aktif.");
                            },
                            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                        );
                    } else {
                        this.loading = false;
                        alert("Browser Anda tidak mendukung Geolocation.");
                    }
                },
                submitAttendance() {
                    if(this.$refs.cameraInput.files.length > 0) {
                        this.loading = true;
                        this.$refs.attendanceForm.submit();
                    }
                }
            }
        }
    </script>

    <div class="animate-fade-in-up animate-fade-in-up-delay-1" id="clock-action-section" x-data="attendanceClock()">
        <div class="flex flex-col items-center">
            {{-- Main Clock Button --}}
            <button type="button" @click="startAttendance()"
               class="group relative w-32 h-32 rounded-full flex flex-col items-center justify-center transition-all duration-300 ease-out
                      {{ $todayAttendance && !$todayAttendance->check_out_time
                         ? 'bg-gradient-to-br from-[#F59E0B] to-[#D97706] shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-[1.03] active:scale-[0.97]'
                         : 'bg-gradient-to-br from-[#3da8ff] to-[#1E90FF] shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-[1.03] active:scale-[0.97]' }}"
               id="btn-clock-action">
                {{-- Decorative ring --}}
                <div class="absolute inset-0 rounded-full border-2 border-white/20 pointer-events-none animate-pulse-soft"></div>
                {{-- Icon --}}
                @if($todayAttendance && !$todayAttendance->check_out_time)
                    <i class="fa-solid fa-arrow-right-from-bracket text-3xl text-white mb-1.5 transition-transform group-hover:translate-x-0.5"></i>
                    <span class="text-xs font-extrabold text-white uppercase tracking-wider">Keluar</span>
                @else
                    <i class="fa-solid fa-arrow-right-to-bracket text-3xl text-white mb-1.5 transition-transform group-hover:translate-x-0.5"></i>
                    <span class="text-xs font-extrabold text-white uppercase tracking-wider">Masuk</span>
                @endif
            </button>

            {{-- Hidden Form --}}
            <form action="{{ route('attendance.clock') }}" method="POST" enctype="multipart/form-data" x-ref="attendanceForm" class="hidden">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <input type="hidden" name="employee_code" value="{{ $employee->employee_code }}">
                <input type="hidden" name="action" value="{{ $todayAttendance && !$todayAttendance->check_out_time ? 'check_out' : 'check_in' }}">
                <input type="hidden" name="latitude" x-model="lat">
                <input type="hidden" name="longitude" x-model="lng">
                <input type="hidden" name="office_id" x-model="selectedOfficeId">
                <input type="file" accept="image/*" capture="user" name="selfie_file" x-ref="cameraInput" @change="submitAttendance" class="hidden">
            </form>

            <!-- Office Selection Modal Overlay -->
            <template x-teleport="body">
                <div x-show="showLocationModal" x-cloak
                     class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm animate-fade-in"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    
                    <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-xl w-full max-w-sm overflow-hidden p-6 transform transition-all"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="translate-y-10 sm:scale-95"
                         x-transition:enter-end="translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="translate-y-0 sm:scale-100"
                         x-transition:leave-end="translate-y-10 sm:scale-95"
                         @click.away="showLocationModal = false">
                        
                        <div class="flex items-center space-x-3 text-[#1E90FF] mb-4">
                            <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <i class="fa-solid fa-location-dot text-lg text-[#1E90FF]"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="text-base font-bold text-[#2d2e34] leading-tight">Pilih Lokasi Kantor</h3>
                                <p class="text-[11px] text-[#8e919b] font-semibold leading-none mt-1">Konfirmasi lokasi absensi Anda</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2.5 my-5">
                            @foreach($offices as $off)
                                <label class="flex items-center justify-between p-3.5 rounded-xl border hover:bg-slate-50 cursor-pointer transition-all relative overflow-hidden text-left"
                                       :class="selectedOfficeId == '{{ $off->id }}' ? 'border-[#1E90FF] bg-blue-50/40' : 'border-slate-100'">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" name="select_office" value="{{ $off->id }}" x-model="selectedOfficeId"
                                               class="h-4.5 w-4.5 text-[#1E90FF] border-gray-300 focus:ring-[#1E90FF]">
                                        <div class="text-left">
                                            <span class="text-xs font-bold text-slate-800">{{ $off->name }}</span>
                                            <span class="block text-[10px] text-slate-400 font-semibold mt-0.5 leading-snug">{{ $off->address ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#8e919b] font-mono whitespace-nowrap ml-2">{{ $off->radius_meter }}m</span>
                                </label>
                            @endforeach
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="button" @click="showLocationModal = false" class="flex-1 py-3 bg-white border border-[#E2E8F0] text-[#64748B] font-bold rounded-xl hover:bg-gray-50 transition-colors text-xs text-center">
                                Batal
                            </button>
                            <button type="button" @click="confirmLocation()" class="flex-1 py-3 bg-[#1E90FF] text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-600 transition-colors text-xs text-center">
                                Lanjutkan Absen
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Status Messages --}}
            <div x-show="loading" class="mt-3.5 text-xs font-bold text-[#1E90FF] animate-pulse">
                <i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengambil lokasi GPS & Kamera...
            </div>
            
            @if(session('error_message'))
                <div class="mt-3.5 text-xs font-bold text-red-600 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 text-center">
                    {{ session('error_message') }}
                </div>
            @endif

            @if(session('success_message'))
                <div class="mt-3.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 text-center">
                    {{ session('success_message') }}
                </div>
            @endif

            {{-- GPS Auto-validation note --}}
            <div x-show="!loading" class="flex items-center space-x-1.5 mt-3.5">
                <i class="fa-solid fa-circle-check text-[11px] text-[#1E90FF]"></i>
                <span class="text-[11px] text-[#8e919b] font-semibold">Validasi terjadi secara otomatis via GPS</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 3: Daily Statistics
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-3 animate-fade-in-up animate-fade-in-up-delay-2" id="daily-stats-section">
        {{-- Progres Harian --}}
        <div class="bg-white rounded-xl border border-[#ecf0f6] p-4 shadow-[rgba(0,0,0,0.04)_0px_2px_10px_0px]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#8e919b]">Progres Harian</span>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-extrabold text-[#2d2e34]">{{ number_format($hoursWorked, 1) }}</span>
                <span class="text-xs font-semibold text-[#8e919b]">/ 8j</span>
            </div>
            {{-- Progress bar --}}
            <div class="mt-2.5 h-1.5 w-full rounded-full bg-[#ecf0f6] overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 ease-out
                    {{ $progressPercent >= 100 ? 'bg-[#1E90FF]' : 'bg-[#1E90FF]/70' }}"
                     style="width: {{ min($progressPercent, 100) }}%"></div>
            </div>
        </div>

        {{-- Lembur --}}
        <div class="bg-white rounded-xl border border-[#ecf0f6] p-4 shadow-[rgba(0,0,0,0.04)_0px_2px_10px_0px]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#8e919b]">Lembur</span>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-extrabold text-[#2d2e34]">{{ number_format($overtimeHours, 1) }}</span>
                <span class="text-xs font-semibold text-[#8e919b]">jam</span>
            </div>
            {{-- Overtime indicator --}}
            <div class="mt-2.5 flex items-center space-x-1.5">
                @if($overtimeHours > 0)
                    <div class="h-1.5 w-1.5 rounded-full bg-[#F59E0B]"></div>
                    <span class="text-[10px] font-bold text-[#F59E0B] uppercase">Lembur Aktif</span>
                @else
                    <div class="h-1.5 w-1.5 rounded-full bg-[#8e919b]/50"></div>
                    <span class="text-[10px] font-medium text-[#8e919b]">Tidak ada lembur</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 4: Recent Activity Log
    ═══════════════════════════════════════════════════════════ --}}
    <div class="animate-fade-in-up animate-fade-in-up-delay-3" id="recent-log-section">
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-bold text-[#2d2e34]">Log Terkini</h2>
            <a href="{{ route('attendances.index') }}" class="text-[10px] font-bold uppercase tracking-wider text-[#1E90FF] hover:text-[#3da8ff] transition-colors">
                Lihat Semua Riwayat
            </a>
        </div>

        {{-- Log List --}}
        <div class="bg-white rounded-xl border border-[#ecf0f6] shadow-[rgba(0,0,0,0.04)_0px_2px_10px_0px] divide-y divide-[#ecf0f6] overflow-hidden">
            @forelse($recentLogs as $log)
                <div class="flex items-center justify-between px-4 py-3.5 hover:bg-[#ecf0f6]/30 transition-colors duration-150">
                    {{-- Left: Icon + Info --}}
                    <div class="flex items-center space-x-3 min-w-0">
                        {{-- Check-in icon (red) or Check-out icon (amber) --}}
                        @if($log->check_out_time && $log->check_in_time->isSameDay(now()) == false)
                            {{-- Show both as separate concept - this is a check_out entry --}}
                            <div class="h-9 w-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 border border-amber-100">
                                <i class="fa-solid fa-arrow-right-from-bracket text-sm text-[#F59E0B]"></i>
                            </div>
                        @else
                            <div class="h-9 w-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100">
                                <i class="fa-solid fa-arrow-right-to-bracket text-sm text-[#1E90FF]"></i>
                            </div>
                        @endif

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#2d2e34]">Masuk</p>
                            <p class="text-[11px] text-[#8e919b] font-medium mt-0.5">
                                {{ $log->check_in_time->translatedFormat('d M Y') }} · {{ $log->check_in_time->format('h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- Right: GPS badge --}}
                    @if($log->latitude && $log->longitude)
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $log->latitude }},{{ $log->longitude }}"
                           target="_blank"
                           class="flex items-center space-x-1 bg-blue-50 px-2 py-1 rounded-full border border-blue-100 hover:bg-blue-100/60 transition-colors flex-shrink-0">
                            <i class="fa-solid fa-location-dot text-[9px] text-[#1E90FF]"></i>
                            <span class="text-[10px] font-bold text-[#1E90FF] uppercase">GPS</span>
                        </a>
                    @endif
                </div>

                {{-- If has check_out, show it as a separate line --}}
                @if($log->check_out_time)
                    <div class="flex items-center justify-between px-4 py-3.5 hover:bg-[#ecf0f6]/30 transition-colors duration-150">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="h-9 w-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 border border-amber-100">
                                <i class="fa-solid fa-arrow-right-from-bracket text-sm text-[#F59E0B]"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-[#2d2e34]">Keluar</p>
                                <p class="text-[11px] text-[#8e919b] font-medium mt-0.5">
                                    {{ $log->check_out_time->translatedFormat('d M Y') }} · {{ $log->check_out_time->format('h:i A') }}
                                </p>
                            </div>
                        </div>
                        @if($log->latitude && $log->longitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $log->latitude }},{{ $log->longitude }}"
                               target="_blank"
                               class="flex items-center space-x-1 bg-blue-50 px-2 py-1 rounded-full border border-blue-100 hover:bg-blue-100/60 transition-colors flex-shrink-0">
                                <i class="fa-solid fa-location-dot text-[9px] text-[#1E90FF]"></i>
                                <span class="text-[10px] font-bold text-[#1E90FF] uppercase">GPS</span>
                            </a>
                        @endif
                    </div>
                @endif
            @empty
                <div class="px-4 py-10 text-center">
                    <div class="h-12 w-12 rounded-2xl bg-[#ecf0f6] flex items-center justify-center mx-auto mb-3 border border-[#c5c6cd]/30">
                        <i class="fa-regular fa-clock text-lg text-[#8e919b]"></i>
                    </div>
                    <p class="text-sm font-semibold text-[#2d2e34]">Belum ada aktivitas</p>
                    <p class="text-xs text-[#8e919b] mt-0.5">Log absensi akan muncul di sini</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Bottom spacer for safe area --}}
    <div class="h-4"></div>
</div>
@endsection
