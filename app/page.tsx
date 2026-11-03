import React from 'react';
import Link from 'next/link';
import { 
  Shirt, 
  Sparkles, 
  ArrowRight, 
  Search, 
  Clock, 
  ShieldCheck, 
  Layers, 
  Scissors, 
  Printer, 
  CheckCircle2, 
  Lock 
} from 'lucide-react';

export default function HomePage() {
  return (
    <div className="min-h-screen bg-slate-900 text-white flex flex-col selection:bg-blue-600 selection:text-white">
      {/* Top Navigation */}
      <header className="border-b border-slate-800 bg-slate-950/60 backdrop-blur-md sticky top-0 z-40">
        <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center font-black text-white shadow-lg shadow-blue-500/30">
              <Shirt className="w-6 h-6" />
            </div>
            <div>
              <span className="font-extrabold text-xl tracking-tight text-white">EIGHTEEN</span>
              <span className="block text-[11px] font-semibold text-blue-400 tracking-wider uppercase">Custom Apparel</span>
            </div>
          </div>

          <div className="flex items-center gap-4">
            <Link
              href="/track-order"
              className="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-800/80 transition border border-slate-800"
            >
              <Search className="w-4 h-4 text-blue-400" />
              <span>Lacak Pesanan</span>
            </Link>

            <Link
              href="/custom-order"
              className="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white transition shadow-lg shadow-blue-600/30"
            >
              <span>Pesan Sekarang</span>
              <ArrowRight className="w-4 h-4" />
            </Link>

            <div className="h-6 w-px bg-slate-800 hidden sm:block" />

            <Link
              href="/login"
              className="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-slate-200 transition"
              title="Akses Internal Staff"
            >
              <Lock className="w-3.5 h-3.5" />
              <span>Portal Staff</span>
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <main className="flex-1">
        <section className="relative pt-24 pb-20 overflow-hidden">
          <div className="absolute inset-0 bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-blue-900/30 via-slate-900 to-slate-950 -z-10" />

          <div className="max-w-5xl mx-auto px-6 text-center">
            <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold mb-6">
              <Sparkles className="w-4 h-4" />
              <span>Konveksi Jersey & Sportswear Premium Standar Ekspor</span>
            </div>

            <h1 className="text-4xl sm:text-6xl font-black tracking-tight text-white mb-6 leading-tight">
              Bikin Apparel Tim & Komunitas <br />
              <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-sky-200">
                Cepat, Presisi, & Terpantau
              </span>
            </h1>

            <p className="text-base sm:text-lg text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
              Solusi pembuatan jersey printing, kemeja komunitas, dan jaket custom dengan alur produksi terintegrasi: Print, Press, Pola, Jahit, hingga Finishing.
            </p>

            {/* Quick Actions */}
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Link
                href="/custom-order"
                className="w-full sm:w-auto px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm transition shadow-xl shadow-blue-600/40 flex items-center justify-center gap-3"
              >
                <span>Mulai Buat Pesanan Baru</span>
                <ArrowRight className="w-4 h-4" />
              </Link>
              <Link
                href="/track-order"
                className="w-full sm:w-auto px-8 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-sm transition border border-slate-700 flex items-center justify-center gap-3"
              >
                <Search className="w-4 h-4 text-blue-400" />
                <span>Cek Status Pesanan Anda</span>
              </Link>
            </div>
          </div>
        </section>

        {/* 5-Stage Production Flow Banner */}
        <section className="py-16 border-y border-slate-800 bg-slate-950/40">
          <div className="max-w-6xl mx-auto px-6">
            <h3 className="text-center text-xs font-bold uppercase tracking-wider text-blue-400 mb-10">
              Alur Produksi Modern & Terkontrol
            </h3>

            <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
              {[
                { title: '1. Print Sublim', desc: 'Tinta kualitas tinggi tajam & awet', icon: Printer },
                { title: '2. Press Panas', desc: 'Transfer warna menyatu sempurna', icon: Layers },
                { title: '3. Potong Pola', desc: 'Presisi ukuran standard atlet', icon: Scissors },
                { title: '4. Jahit Rapih', desc: 'Jahitan kokoh & benang elastis', icon: Shirt },
                { title: '5. Finishing & QC', desc: 'Quality control sebelum kirim', icon: CheckCircle2 },
              ].map((step, idx) => {
                const Icon = step.icon;
                return (
                  <div key={idx} className="p-4 rounded-xl bg-slate-900/60 border border-slate-800 text-center">
                    <div className="w-10 h-10 mx-auto rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 mb-3">
                      <Icon className="w-5 h-5" />
                    </div>
                    <h4 className="text-sm font-bold text-white mb-1">{step.title}</h4>
                    <p className="text-xs text-slate-400">{step.desc}</p>
                  </div>
                );
              })}
            </div>
          </div>
        </section>
      </main>

      {/* Footer */}
      <footer className="border-t border-slate-800 py-8 bg-slate-950 text-center text-xs text-slate-400">
        <div className="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p>© {new Date().getFullYear()} Eighteen Custom Apparel. All rights reserved.</p>
          <div className="flex items-center gap-6">
            <Link href="/track-order" className="hover:text-slate-300">Tracking Pesanan</Link>
            <Link href="/custom-order" className="hover:text-slate-300">Form Pemesanan</Link>
            <Link href="/login" className="hover:text-slate-300">Staff Portal</Link>
          </div>
        </div>
      </footer>
    </div>
  );
}
