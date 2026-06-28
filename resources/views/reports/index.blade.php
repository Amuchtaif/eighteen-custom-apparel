@extends('layouts.admin')

@section('title', 'Laporan & Analisis - Eighteen ERP')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Laporan & Analisis</h1>
        <p class="text-sm text-[#64748B] mt-1">Pantau metrik pendapatan, detail absen masuk karyawan, dan ringkasan tahunan.</p>
    </div>

    <!-- Reports Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Sales Performance Card -->
        <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3 mb-4">
                    <h2 class="text-lg font-bold text-[#0F172A]">Kinerja Penjualan ({{ now()->year }})</h2>
                    <span class="text-xs text-[#64748B] font-mono">Tahun Ini</span>
                </div>
                <p class="text-xs text-[#64748B] mb-6">Arahkan kursor ke grafik batang untuk melihat jumlah persis pendapatan bulanan.</p>

                <!-- Pure CSS Grid Bar Chart -->
                <div class="flex items-end justify-between h-56 pt-4 pb-2 border-b border-[#E2E8F0] px-4 bg-slate-50 rounded-lg border border-slate-100">
                    @foreach ($salesLabels as $index => $label)
                        @php
                            $value = $salesValues[$index];
                            $maxVal = count($salesValues) > 0 ? max($salesValues) : 0;
                            $maxVal = $maxVal > 0 ? $maxVal : 1; // avoid division by zero
                            $heightPct = ($value / $maxVal) * 100;
                        @endphp
                        <div class="flex flex-col items-center flex-1 h-full justify-end group">
                            <!-- Tooltip container -->
                            <div class="relative w-full flex justify-center">
                                <span class="absolute bottom-full mb-1 scale-0 transition-all rounded bg-gray-900 px-2 py-1 text-[10px] text-white group-hover:scale-100 font-mono font-bold whitespace-nowrap z-20 shadow-md">
                                    Rp {{ number_format($value, 0, ',', '.') }}
                                </span>
                            </div>
                            <!-- Bar -->
                            <div class="w-6 sm:w-8 bg-[#1E90FF]/25 group-hover:bg-[#1E90FF] rounded-t transition-all duration-300" 
                                 style="height: {{ max(4, $heightPct * 0.9) }}%;">
                            </div>
                            <!-- Label -->
                            <span class="text-[10px] font-bold text-slate-500 mt-2">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Table breakdown -->
            <div class="mt-6 pt-4 border-t border-[#E2E8F0] max-h-48 overflow-y-auto pr-1">
                <table class="min-w-full divide-y divide-[#E2E8F0] text-left text-xs">
                    <thead>
                        <tr class="text-[#64748B] uppercase tracking-wider font-semibold">
                            <th class="py-2 w-10 text-center">No</th>
                            <th class="py-2">Bulan</th>
                            <th class="py-2 text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @foreach ($salesLabels as $index => $label)
                            <tr>
                                <td class="py-2 text-center text-sm font-medium text-[#64748B]">{{ $loop->iteration }}</td>
                                <td class="py-2 text-slate-700 font-semibold">{{ $label }}</td>
                                <td class="py-2 text-right text-slate-900 font-mono font-bold">Rp {{ number_format($salesValues[$index], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Attendance Recap Card -->
        <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3 mb-4">
                    <h2 class="text-lg font-bold text-[#0F172A]">Rekap Kehadiran Karyawan</h2>
                    <span class="text-xs text-[#64748B] font-mono">{{ now()->format('F Y') }}</span>
                </div>
                <p class="text-xs text-[#64748B] mb-6">Total absen masuk yang selesai untuk bulan berjalan ini.</p>

                <!-- Attendance Table -->
                <div class="overflow-x-auto border border-[#E2E8F0] rounded-lg">
                    <table class="min-w-full divide-y divide-[#E2E8F0] text-left text-xs">
                        <thead class="bg-[#F8FAFC]">
                            <tr class="text-[#64748B] uppercase tracking-wider font-semibold">
                                <th class="px-4 py-2.5 w-10 text-center">No</th>
                                <th class="px-4 py-2.5">Kode</th>
                                <th class="px-4 py-2.5">Nama</th>
                                <th class="px-4 py-2.5">Departemen</th>
                                <th class="px-4 py-2.5 text-center">Absensi Bulan Ini</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0] text-slate-700">
                            @forelse ($attendanceRecap as $employee)
                                <tr class="hover:bg-[#F8FAFC] transition-colors">
                                    <td class="px-4 py-3 text-center text-sm font-medium text-[#64748B]">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-mono font-semibold text-slate-900">{{ $employee->employee_code }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-900">{{ $employee->user->name ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $employee->position?->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $employee->department?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-[#1E90FF]/10 text-[#1E90FF] border border-[#1E90FF]/20">
                                            {{ $employee->attendances_count }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data kehadiran bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer summary -->
            <div class="pt-4 border-t border-[#E2E8F0] text-xs text-[#64748B]">
                Data kehadiran diperbarui secara otomatis melalui absensi yang dicatat di Stasiun Absensi.
            </div>
        </div>

    </div>
</div>
@endsection
