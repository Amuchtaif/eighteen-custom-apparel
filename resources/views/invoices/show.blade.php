@extends('layouts.admin')

@section('title', "Faktur {$invoice->invoice_number} - Eighteen ERP")

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('invoices.index') }}" class="text-[#64748B] hover:text-[#0F172A] transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Detail Faktur</h1>
                <p class="text-sm text-[#64748B] mt-1">Nomor faktur: <span class="font-mono font-bold text-[#1E90FF]">{{ $invoice->invoice_number }}</span>.</p>
            </div>
        </div>
        <div>
            @if ($invoice->status !== 'paid')
                <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors">
                    Catat Pembayaran
                </a>
            @endif
        </div>
    </div>

    <!-- Details Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Billing Details -->
        <div class="md:col-span-2 bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-6">
            <h2 class="text-lg font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-3">Faktur Penagihan</h2>
            
            <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <span class="text-[#64748B] block">Tanggal Faktur</span>
                    <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Tenggat Waktu</span>
                    <span class="font-semibold text-slate-800">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Pesanan Terkait</span>
                    @if($invoice->order)
                        <a href="{{ route('orders.show', $invoice->order_id) }}" class="font-mono font-bold text-[#1E90FF] hover:underline">{{ $invoice->order->order_number }}</a>
                    @else
                        -
                    @endif
                </div>
                <div>
                    <span class="text-[#64748B] block">Status Penagihan</span>
                    @php
                        $statusColor = match ($invoice->status) {
                            'unpaid' => 'bg-red-50 text-red-800 border-red-200',
                            'partially_paid' => 'bg-amber-50 text-amber-800 border-amber-200',
                            'paid' => 'bg-green-50 text-green-800 border-green-200',
                            default => 'bg-gray-100 text-gray-800 border-gray-200'
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border mt-1 {{ $statusColor }}">
                        {{ $invoice->status === 'paid' ? 'Lunas' : ($invoice->status === 'unpaid' ? 'Belum Lunas' : ($invoice->status === 'partially_paid' ? 'Dibayar Sebagian' : 'Dibatalkan')) }}
                    </span>
                </div>
            </div>

            <!-- Ledger Breakdown -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100 grid grid-cols-3 gap-4 text-xs pt-4">
                <div>
                    <span class="text-[#64748B] block font-semibold uppercase tracking-wider">Total Jumlah</span>
                    <span class="font-bold text-slate-800 text-sm">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold uppercase tracking-wider">Total Diterima</span>
                    <span class="font-bold text-emerald-600 text-sm">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold uppercase tracking-wider">Sisa Tagihan</span>
                    <span class="font-bold text-rose-600 text-sm">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Client Profile -->
        <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-4">
            <h2 class="text-lg font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-3">Profil Klien</h2>
            @if ($invoice->order && $invoice->order->customer)
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Perusahaan</span>
                        <span class="font-bold text-[#0F172A]">{{ $invoice->order->customer->company_name }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Narahubung</span>
                        <span class="font-medium text-slate-800">{{ $invoice->order->customer->contact_person }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Telepon</span>
                        <span class="font-medium text-slate-800">{{ $invoice->order->customer->phone }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Alamat</span>
                        <span class="font-medium text-slate-800 leading-snug">{{ $invoice->order->customer->address }}, {{ $invoice->order->customer->city }}</span>
                    </div>
                </div>
            @else
                <span class="text-slate-400">Detail pelanggan tidak tersedia.</span>
            @endif
        </div>
    </div>

    <!-- Payments List -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm p-6 space-y-4">
        <h2 class="text-lg font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-3">Buku Besar Pembayaran</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E2E8F0] text-left text-sm">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-6 py-3 font-semibold text-[#64748B]">Tanggal</th>
                        <th class="px-6 py-3 font-semibold text-[#64748B]">Nomor Referensi</th>
                        <th class="px-6 py-3 font-semibold text-[#64748B]">Metode Pembayaran</th>
                        <th class="px-6 py-3 font-semibold text-[#64748B]">Catatan</th>
                        <th class="px-6 py-3 font-semibold text-[#64748B]">Bukti Pembayaran</th>
                        <th class="px-6 py-3 font-semibold text-[#64748B] text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse ($invoice->payments as $payment)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-mono font-semibold text-[#1E90FF]">{{ $payment->reference_number ?: 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-700 capitalize">
                                {{ $payment->payment_method === 'cash' ? 'Tunai' : ($payment->payment_method === 'bank_transfer' ? 'Transfer Bank' : ($payment->payment_method === 'ewallet' ? 'Dompet Digital' : str_replace('_', ' ', $payment->payment_method))) }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $payment->notes }}">{{ $payment->notes ?: '-' }}</td>
                            <td class="px-6 py-4">
                                @if ($payment->proof_file)
                                    <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank" class="inline-flex items-center text-xs font-semibold text-[#1E90FF] hover:underline">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Tidak Ada Berkas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada pembayaran yang tercatat untuk faktur ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
