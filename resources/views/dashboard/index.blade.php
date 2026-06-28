@extends('layouts.admin')

@section('title', 'Dasbor - Eighteen ERP')

@section('content')
<div class="space-y-8">
    <!-- Header Greeting -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#2d2e34] tracking-tight">Ahlan, {{ strtoupper(auth()->user()->name ?? 'ANDI MUCHTAIF') }}!</h1>
            <p class="text-xs text-[#8e919b] font-semibold mt-1">Pantau perkembangan produksi custom apparel dan keuangan bisnis Anda secara langsung.</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 text-[#1E90FF] px-4 py-2 rounded-xl text-xs font-bold flex items-center shadow-sm">
            <i class="fa-solid fa-clock mr-2 animate-spin-slow"></i> Diupdate Realtime
        </div>
    </div>

    <!-- Ringkasan Widget Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pesanan Aktif -->
        <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-5 hover:shadow-md transition-all duration-200 flex items-center space-x-4">
            <div class="p-3.5 rounded-xl bg-blue-100 text-[#1E90FF] shadow-sm"><i class="fa-solid fa-cart-shopping text-xl"></i></div>
            <div>
                <span class="text-[10px] font-bold text-[#8e919b] uppercase tracking-wider block">Pesanan Aktif</span>
                <span class="text-2xl font-black text-slate-800 tracking-tight block mt-0.5">{{ number_format($activeOrdersCount) }}</span>
            </div>
        </div>

        <!-- Pesanan Selesai -->
        <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-5 hover:shadow-md transition-all duration-200 flex items-center space-x-4">
            <div class="p-3.5 rounded-xl bg-emerald-100 text-emerald-600 shadow-sm"><i class="fa-solid fa-circle-check text-xl"></i></div>
            <div>
                <span class="text-[10px] font-bold text-[#8e919b] uppercase tracking-wider block">Pesanan Selesai</span>
                <span class="text-2xl font-black text-slate-800 tracking-tight block mt-0.5">{{ number_format($completedOrdersCount) }}</span>
            </div>
        </div>

        <!-- Total Omset -->
        <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-5 hover:shadow-md transition-all duration-200 flex items-center space-x-4">
            <div class="p-3.5 rounded-xl bg-indigo-100 text-indigo-600 shadow-sm"><i class="fa-solid fa-wallet text-xl"></i></div>
            <div>
                <span class="text-[10px] font-bold text-[#8e919b] uppercase tracking-wider block">Total Omset</span>
                <span class="text-xl font-black text-slate-800 tracking-tight block mt-0.5">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Total Piutang -->
        <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-5 hover:shadow-md transition-all duration-200 flex items-center space-x-4">
            <div class="p-3.5 rounded-xl bg-rose-100 text-rose-600 shadow-sm"><i class="fa-solid fa-file-invoice-dollar text-xl"></i></div>
            <div>
                <span class="text-[10px] font-bold text-[#8e919b] uppercase tracking-wider block">Total Piutang</span>
                <span class="text-xl font-black text-slate-800 tracking-tight block mt-0.5">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Widget Status Produksi -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 space-y-4">
        <div>
            <h2 class="text-sm font-bold text-[#2d2e34] uppercase tracking-wider">Widget Status Produksi</h2>
            <p class="text-[11px] text-[#8e919b] mt-0.5">Jumlah pesanan aktif yang berada di setiap tahapan produksi.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 pt-2">
            @php
                $stagesInfo = [
                    'Antrian' => ['color' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'fa-list-ul'],
                    'Print' => ['color' => 'bg-blue-50 text-blue-800 border-blue-200', 'icon' => 'fa-print'],
                    'Press' => ['color' => 'bg-purple-50 text-purple-800 border-purple-200', 'icon' => 'fa-compress'],
                    'Pola' => ['color' => 'bg-orange-50 text-orange-800 border-orange-200', 'icon' => 'fa-crop-simple'],
                    'Jahit' => ['color' => 'bg-yellow-50 text-yellow-800 border-yellow-200', 'icon' => 'fa-scissors'],
                    'Finish' => ['color' => 'bg-green-50 text-green-800 border-green-200', 'icon' => 'fa-flag-checkered'],
                    'Siap Kirim' => ['color' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'icon' => 'fa-truck-fast'],
                    'Selesai' => ['color' => 'bg-teal-50 text-teal-800 border-teal-200', 'icon' => 'fa-circle-check']
                ];
            @endphp

            @foreach ($stagesInfo as $status => $info)
                <div class="flex flex-col items-center justify-between p-4 rounded-xl border {{ $info['color'] }} shadow-sm text-center">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center bg-white/70 shadow-sm"><i class="fa-solid {{ $info['icon'] }} text-xs"></i></div>
                    <span class="text-[10px] font-bold mt-2 uppercase tracking-wide opacity-80">{{ $status }}</span>
                    <span class="text-xl font-black mt-1">{{ number_format($productionStatusCounts[$status] ?? 0) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Widget Deadline Terdekat & Recent Orders Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2/3: Widget Deadline Terdekat -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-[#2d2e34] uppercase tracking-wider">Deadline Terdekat</h2>
                    <p class="text-[11px] text-[#8e919b] mt-0.5">Maksimal 10 pesanan aktif dengan tanggal tenggat waktu paling mendesak.</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                    Kritis
                </span>
            </div>

            <div class="overflow-x-auto pt-2">
                <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[#8e919b] w-8 text-center">No</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[#8e919b]">Nomor Pesanan</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[#8e919b]">Nama Team</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[#8e919b]">Deadline</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[#8e919b]">Sisa Hari</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[#8e919b]">Tahap</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[#2d2e34]">
                        @forelse ($closestDeadlines as $order)
                            @php
                                $color = $order->deadline_color;
                                $badgeClass = match($color) {
                                    'red' => 'bg-red-50 text-red-700 border-red-200',
                                    'yellow' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-green-50 text-green-700 border-green-200'
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 text-center text-[#8e919b]">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-mono font-bold text-[#1E90FF]"><a href="{{ route('orders.show', $order->id) }}" class="hover:underline">{{ $order->kode_order }}</a></td>
                                <td class="px-4 py-3 font-semibold">{{ $order->nama_team ?? '-' }}</td>
                                <td class="px-4 py-3 text-[#8e919b]">{{ $order->deadline ? $order->deadline->format('d M Y') : '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $badgeClass }}">
                                        {{ $order->sisa_hari_text }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $prodColor = match ($order->status_produksi) {
                                            'Antrian' => 'bg-gray-100 text-gray-800',
                                            'Print' => 'bg-blue-50 text-blue-700',
                                            'Press' => 'bg-purple-50 text-purple-700',
                                            'Pola' => 'bg-orange-50 text-orange-700',
                                            'Jahit' => 'bg-yellow-50 text-yellow-800',
                                            'Finish' => 'bg-green-50 text-green-700',
                                            'Siap Kirim' => 'bg-emerald-50 text-emerald-700',
                                            'Selesai' => 'bg-teal-50 text-teal-700',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $prodColor }}">
                                        {{ $order->status_produksi }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-[#8e919b]">Tidak ada pesanan aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 1/3: Recent Orders -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-[#2d2e34] uppercase tracking-wider">Terbaru</h2>
                    <p class="text-[11px] text-[#8e919b] mt-0.5">5 pesanan terakhir yang baru masuk ke sistem.</p>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                @forelse ($recentOrders as $order)
                    <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl hover:bg-slate-100/50 transition-colors flex items-center justify-between">
                        <div class="space-y-1">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-xs font-mono font-bold text-[#1E90FF] hover:underline block">{{ $order->kode_order }}</a>
                            <span class="text-xs text-slate-800 font-semibold block leading-none">{{ $order->customer->company_name ?? '-' }}</span>
                            <span class="text-[10px] text-[#8e919b] block">{{ $order->tanggal_masuk ? $order->tanggal_masuk->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-800 block">Rp {{ number_format($order->total_tagihan, 0, ',', '.') }}</span>
                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold mt-1.5 
                                {{ $order->status_pembayaran === 'Lunas' ? 'bg-green-100 text-green-800' : ($order->status_pembayaran === 'DP' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ $order->status_pembayaran }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-400 py-8 text-xs">Belum ada data pesanan terbaru.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
