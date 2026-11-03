'use client';

import React, { Suspense } from 'react';
import { useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { CheckCircle2, MessageCircle, Search, ArrowRight, Shirt } from 'lucide-react';

function OrderSuccessContent() {
  const searchParams = useSearchParams();
  const orderNumber = searchParams.get('order_number') || 'ORD-2026-XXXX';

  const adminPhone = '6281234567890';
  const waMessage = encodeURIComponent(
    `Halo Admin Eighteen, saya telah mengisi formulir custom order dengan Nomor Pesanan: ${orderNumber}. Mohon konfirmasinya. Terima kasih.`
  );
  const waUrl = `https://wa.me/${adminPhone}?text=${waMessage}`;

  return (
    <div className="min-h-screen bg-slate-950 flex flex-col items-center justify-center px-4 py-12 text-white">
      <div className="max-w-md w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 sm:p-10 text-center shadow-2xl relative overflow-hidden">
        <div className="w-16 h-16 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 mx-auto mb-6">
          <CheckCircle2 className="w-8 h-8" />
        </div>

        <h1 className="text-2xl font-black text-white mb-2">Pesanan Berhasil Dibuat!</h1>
        <p className="text-xs text-slate-400 mb-6 leading-relaxed">
          Formulir Anda telah terdaftar di antrian produksi Eighteen. Simpan nomor pesanan berikut untuk melacak progress produksi Anda.
        </p>

        {/* Order Number Box */}
        <div className="p-4 rounded-2xl bg-slate-800/80 border border-slate-700 mb-8">
          <span className="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
            Nomor Pesanan Anda
          </span>
          <span className="text-xl font-black font-mono text-blue-400 tracking-wider">
            {orderNumber}
          </span>
        </div>

        <div className="space-y-3">
          {/* WhatsApp CTA */}
          <a
            href={waUrl}
            target="_blank"
            rel="noopener noreferrer"
            className="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2"
          >
            <MessageCircle className="w-4 h-4 fill-white" />
            <span>Konfirmasi ke WhatsApp Admin</span>
          </a>

          {/* Track Order CTA */}
          <Link
            href={`/track-order?code=${orderNumber}`}
            className="w-full py-3.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition flex items-center justify-center gap-2 border border-slate-700"
          >
            <Search className="w-4 h-4" />
            <span>Lacak Status Pesanan</span>
          </Link>

          {/* Back Home */}
          <Link
            href="/"
            className="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-400 pt-2 transition font-medium"
          >
            <span>Kembali ke Beranda</span>
            <ArrowRight className="w-3.5 h-3.5" />
          </Link>
        </div>
      </div>
    </div>
  );
}

export default function OrderSuccessPage() {
  return (
    <Suspense fallback={<div className="min-h-screen bg-slate-950 flex items-center justify-center text-white">Memuat...</div>}>
      <OrderSuccessContent />
    </Suspense>
  );
}
