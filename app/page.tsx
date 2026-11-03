import React from 'react';
import Link from 'next/link';
import ThemeToggle from '@/components/ThemeToggle';
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
  Lock,
  Star,
  Zap,
  Check,
  MessageCircle,
  Award,
  Users,
  BadgeCheck,
  Flame,
  ArrowUpRight,
  TrendingUp,
  Activity
} from 'lucide-react';

export default function HomePage() {
  const adminPhone = '6281234567890';
  const waConsultUrl = `https://wa.me/${adminPhone}?text=${encodeURIComponent(
    'Halo Eighteen Custom Apparel, saya ingin konsultasi pembuatan jersey/apparel custom untuk tim saya.'
  )}`;

  return (
    <div className="min-h-screen bg-white dark:bg-slate-900 text-slate-900 dark:text-white flex flex-col selection:bg-blue-600 selection:text-white transition-colors duration-200">
      {/* Top Navigation Bar */}
      <header className="border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md sticky top-0 z-40 transition-colors duration-200">
        <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
          {/* Brand Logo */}
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 flex items-center justify-center p-1.5 shadow-sm shadow-blue-500/10 overflow-hidden">
              <img src="/icon.png" alt="Eighteen Logo" className="w-full h-full object-contain" />
            </div>
            <div>
              <span className="font-extrabold text-xl tracking-tight text-slate-900 dark:text-white">EIGHTEEN</span>
              <span className="block text-[10px] font-bold text-blue-600 dark:text-blue-400 tracking-wider uppercase">Custom Apparel</span>
            </div>
          </div>

          {/* Nav Links */}
          <nav className="hidden md:flex items-center gap-6 text-xs font-semibold text-slate-600 dark:text-slate-300">
            <a href="#alur-produksi" className="hover:text-blue-600 dark:hover:text-white transition">Alur Produksi</a>
            <a href="#pricing" className="hover:text-blue-600 dark:hover:text-white transition">Paket & Harga</a>
            <a href="#testimoni" className="hover:text-blue-600 dark:hover:text-white transition">Testimoni Tim</a>
            <a href="#cta" className="hover:text-blue-600 dark:hover:text-white transition">Konsultasi</a>
          </nav>

          {/* Action Header Elements */}
          <div className="flex items-center gap-3">
            <ThemeToggle />

            <Link
              href="/track-order"
              className="hidden sm:flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition border border-slate-200 dark:border-slate-800"
            >
              <Search className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" />
              <span>Lacak Pesanan</span>
            </Link>

            <Link
              href="/custom-order"
              className="flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white transition shadow-md shadow-blue-600/20"
            >
              <span>Pesan Sekarang</span>
              <ArrowRight className="w-3.5 h-3.5" />
            </Link>

            <div className="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block" />

            <Link
              href="/login"
              className="hidden lg:flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 transition"
              title="Akses Internal Staff"
            >
              <Lock className="w-3.5 h-3.5" />
              <span>Staff Portal</span>
            </Link>
          </div>
        </div>
      </header>

      {/* Main Content Area */}
      <main className="flex-1">
        {/* BALANCED 2-COLUMN HERO SECTION */}
        <section className="relative pt-12 pb-20 lg:pt-16 lg:pb-28 overflow-hidden bg-gradient-to-b from-blue-50/50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 transition-colors duration-200">
          <div className="max-w-7xl mx-auto px-6">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
              {/* Left Column: Headline, Description, CTAs, Trust Points (7 cols) */}
              <div className="lg:col-span-7 text-left space-y-6">
                <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 text-xs font-bold shadow-sm">
                  <Sparkles className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" />
                  <span>Konveksi Jersey & Sportswear Standar Atlet Ekspor</span>
                </div>

                <h1 className="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 dark:text-white leading-[1.15]">
                  Bikin Apparel Tim <br />
                  <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-sky-500 to-indigo-600 dark:from-blue-400 dark:via-sky-300 dark:to-blue-200">
                    Cepat, Presisi, & Terpantau
                  </span>
                </h1>

                <p className="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-xl font-normal">
                  Spesialis jersey futsal, sepakbola, basket, lari & komunitas gowes dengan 5 tahapan produksi terkontrol (Print, Press, Pola, Jahit, Finish) dan sistem pelacakan pesanan real-time.
                </p>

                {/* Action Buttons */}
                <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                  <Link
                    href="/custom-order"
                    className="px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 text-center"
                  >
                    <span>Mulai Buat Pesanan Online</span>
                    <ArrowRight className="w-4 h-4" />
                  </Link>

                  <Link
                    href="/track-order"
                    className="px-6 py-3.5 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs sm:text-sm transition border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-2 text-center shadow-sm"
                  >
                    <Search className="w-4 h-4 text-blue-600 dark:text-blue-400" />
                    <span>Lacak Pesanan</span>
                  </Link>

                  <a
                    href={waConsultUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="px-5 py-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 font-bold text-xs sm:text-sm transition border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center gap-2 text-center"
                  >
                    <MessageCircle className="w-4 h-4" />
                    <span>WhatsApp</span>
                  </a>
                </div>

                {/* 4 Trust Highlights */}
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6 border-t border-slate-200 dark:border-slate-800 text-left">
                  <div>
                    <div className="text-base font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                      <ShieldCheck className="w-4 h-4 text-blue-600 dark:text-blue-400" />
                      100% Garansi
                    </div>
                    <div className="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Kualitas jahitan & warna</div>
                  </div>
                  <div>
                    <div className="text-base font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                      <Clock className="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                      Tepat Waktu
                    </div>
                    <div className="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">SOP deadline ketat</div>
                  </div>
                  <div>
                    <div className="text-base font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                      <Flame className="w-4 h-4 text-indigo-600 dark:text-indigo-400" />
                      Sublimasi HD
                    </div>
                    <div className="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Tinta original awet</div>
                  </div>
                  <div>
                    <div className="text-base font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                      <Award className="w-4 h-4 text-amber-500" />
                      500+ Tim
                    </div>
                    <div className="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Klub & komunitas</div>
                  </div>
                </div>
              </div>

              {/* Right Column: Hero Brand Card & Live Simulation (5 cols) */}
              <div className="lg:col-span-5 relative">
                {/* Ambient glow effect */}
                <div className="absolute -inset-2 bg-gradient-to-r from-blue-500/20 via-sky-500/20 to-indigo-500/20 rounded-3xl blur-2xl pointer-events-none" />

                <div className="relative rounded-3xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden p-6 space-y-6">
                  {/* Brand Horizontal Logo Container */}
                  <div className="rounded-2xl bg-slate-950 p-4 border border-slate-800 shadow-inner flex items-center justify-center overflow-hidden">
                    <img
                      src="/hero-logo.jpg"
                      alt="Eighteen Custom Apparel Brand"
                      className="w-full h-auto max-h-40 object-contain rounded-lg"
                    />
                  </div>

                  {/* Quality Checklist Pills */}
                  <div className="space-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <div className="flex items-center gap-2 p-2 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800/80">
                      <CheckCircle2 className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Full Print Sublimasi HD Transfer Panas 205&deg;C</span>
                    </div>
                    <div className="flex items-center gap-2 p-2 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800/80">
                      <CheckCircle2 className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Jahitan Rantai Kokoh & Kerah Rib Spandex Elastis</span>
                    </div>
                    <div className="flex items-center gap-2 p-2 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800/80">
                      <CheckCircle2 className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Kain Dryfit Premium Anti-Gerah & Nyaman Bergerak</span>
                    </div>
                  </div>

                  {/* Live Simulation Card */}
                  <div className="p-4 rounded-2xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/50">
                    <div className="flex items-center justify-between text-xs mb-2">
                      <div className="flex items-center gap-2">
                        <span className="w-2 h-2 rounded-full bg-emerald-500 animate-ping" />
                        <span className="font-bold text-slate-900 dark:text-white">ORD-202611-0001</span>
                        <span className="text-slate-500 dark:text-slate-400 font-medium">&bull; Garuda FC</span>
                      </div>
                      <span className="font-bold text-blue-700 dark:text-blue-400">Tahap 4/5: Jahit</span>
                    </div>
                    <div className="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 overflow-hidden mb-1.5">
                      <div className="bg-blue-600 h-full rounded-full transition-all duration-500 w-[80%]" />
                    </div>
                    <div className="flex justify-between text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                      <span>Progress Produksi 80%</span>
                      <span className="text-emerald-600 dark:text-emerald-400 font-bold">Tepat Waktu (H-5)</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* 5-STAGE PRODUCTION FLOW SECTION */}
        <section id="alur-produksi" className="py-20 border-y border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 transition-colors duration-200">
          <div className="max-w-6xl mx-auto px-6">
            <div className="text-center max-w-2xl mx-auto mb-14">
              <h2 className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-2">
                Sistem Pengerjaan Terintegrasi
              </h2>
              <h3 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                5 Tahapan Produksi Presisi & Terkontrol
              </h3>
              <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-2">
                Setiap tahapan dikerjakan oleh operator terampil dengan checklist QC ketat sebelum dipacking dan dikirim.
              </p>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
              {[
                { title: '1. Print Sublim', desc: 'Tinta kualitas tinggi warna tajam & awet', icon: Printer },
                { title: '2. Press Panas', desc: 'Transfer warna 205°C menyatu serat', icon: Layers },
                { title: '3. Potong Pola', desc: 'Presisi ukuran standard atlet sporty fit', icon: Scissors },
                { title: '4. Jahit Rapih', desc: 'Jahitan rantai kokoh & benang elastis', icon: Shirt },
                { title: '5. Finishing & QC', desc: 'Pembersihan benang, steam & packing', icon: CheckCircle2 },
              ].map((step, idx) => {
                const Icon = step.icon;
                return (
                  <div key={idx} className="p-5 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 text-center hover:border-blue-500/40 transition shadow-sm">
                    <div className="w-12 h-12 mx-auto rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4 shadow-sm">
                      <Icon className="w-6 h-6" />
                    </div>
                    <h4 className="text-sm font-bold text-slate-900 dark:text-white mb-1.5">{step.title}</h4>
                    <p className="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{step.desc}</p>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* PRICING SECTION */}
        <section id="pricing" className="py-24 relative overflow-hidden bg-white dark:bg-slate-900 transition-colors duration-200">
          <div className="max-w-6xl mx-auto px-6">
            <div className="text-center max-w-2xl mx-auto mb-16">
              <span className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-2 block">
                Paket & Estimasi Biaya
              </span>
              <h2 className="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">
                Pilihan Paket Produksi Jersey Terbaik
              </h2>
              <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-2">
                Pilih paket sesuai kebutuhan tim Anda. Seluruh paket sudah termasuk gratis konsultasi desain dan minimum order 12 pcs.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
              {/* Card 1: Paket Reguler */}
              <div className="rounded-3xl bg-slate-50 dark:bg-slate-950/70 border border-slate-200 dark:border-slate-800 p-8 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition shadow-sm">
                <div>
                  <div className="flex items-center justify-between mb-4">
                    <span className="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Paket 01</span>
                    <span className="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-200/80 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                      Standard
                    </span>
                  </div>
                  <h3 className="text-xl font-black text-slate-900 dark:text-white mb-2">Paket Reguler</h3>
                  <p className="text-xs text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                    Pilihan hemat untuk tim futsal, komunitas hobi, dan seragam pertandingan santai.
                  </p>

                  <div className="mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
                    <span className="text-xs text-slate-500 dark:text-slate-400">Mulai dari</span>
                    <div className="text-3xl font-black text-slate-900 dark:text-white mt-1">
                      Rp 120.000 <span className="text-xs font-medium text-slate-500 dark:text-slate-400">/ pcs</span>
                    </div>
                    <span className="text-[11px] text-slate-500 dark:text-slate-500 block mt-1">Min. pemesanan 12 pcs</span>
                  </div>

                  <ul className="space-y-3 text-xs text-slate-700 dark:text-slate-300 mb-8">
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Bahan Milano Dryfit / Serena</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Full Print Sublimasi HD Depan/Belakang</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Jahitan Standar Obras & Overdeck</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Free Nama & Nomor Punggung Pemain</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Estimasi Waktu Pengerjaan 7-10 Hari</span>
                    </li>
                  </ul>
                </div>

                <Link
                  href="/custom-order?package_id=1"
                  className="w-full py-3.5 px-4 rounded-xl text-center text-xs font-bold text-slate-800 dark:text-white bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-2 shadow-sm"
                >
                  <span>Pilih Paket Reguler</span>
                  <ArrowRight className="w-4 h-4" />
                </Link>
              </div>

              {/* Card 2: Paket Premium (Featured) */}
              <div className="rounded-3xl bg-white dark:bg-slate-900 border-2 border-blue-600 p-8 flex flex-col justify-between relative shadow-xl shadow-blue-500/10 transform lg:-translate-y-2">
                <div className="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-blue-600 text-white text-[11px] font-black uppercase tracking-wider shadow-md shadow-blue-600/30 flex items-center gap-1.5">
                  <Star className="w-3.5 h-3.5 fill-white text-white" />
                  <span>Paling Diminati</span>
                </div>

                <div>
                  <div className="flex items-center justify-between mb-4">
                    <span className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Paket 02</span>
                    <span className="px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30">
                      Best Value
                    </span>
                  </div>
                  <h3 className="text-xl font-black text-slate-900 dark:text-white mb-2">Paket Premium</h3>
                  <p className="text-xs text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                    Kualitas liga profesional untuk tim turnamen, apparel esport, dan komunitas kompetitif.
                  </p>

                  <div className="mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
                    <span className="text-xs text-slate-500 dark:text-slate-400">Mulai dari</span>
                    <div className="text-3xl font-black text-blue-600 dark:text-blue-400 mt-1">
                      Rp 145.000 <span className="text-xs font-medium text-slate-500 dark:text-slate-400">/ pcs</span>
                    </div>
                    <span className="text-[11px] text-slate-500 dark:text-slate-500 block mt-1">Min. pemesanan 12 pcs</span>
                  </div>

                  <ul className="space-y-3 text-xs text-slate-700 dark:text-slate-200 mb-8">
                    <li className="flex items-center gap-2.5 font-semibold">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Bahan Milano Premium / Benzema / Brazil</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Sublimasi Ultra HD Tinta Original Awet</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Kerah Rib Spandex Elastis + Jahit Rantai</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Free Label Size Woven & Plastik Polybag</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Free Mockup 3D + Revisi Desain Tim</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Estimasi Pengerjaan Cepat 5-7 Hari Kerja</span>
                    </li>
                  </ul>
                </div>

                <Link
                  href="/custom-order?package_id=2"
                  className="w-full py-4 px-4 rounded-xl text-center text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2"
                >
                  <span>Pilih Paket Premium</span>
                  <ArrowRight className="w-4 h-4" />
                </Link>
              </div>

              {/* Card 3: Paket Express */}
              <div className="rounded-3xl bg-slate-50 dark:bg-slate-950/70 border border-slate-200 dark:border-slate-800 p-8 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition shadow-sm">
                <div>
                  <div className="flex items-center justify-between mb-4">
                    <span className="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Paket 03</span>
                    <span className="px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 flex items-center gap-1">
                      <Zap className="w-3 h-3" />
                      <span>Kilat</span>
                    </span>
                  </div>
                  <h3 className="text-xl font-black text-slate-900 dark:text-white mb-2">Paket Express</h3>
                  <p className="text-xs text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                    Jadwal tanding sudah mepet? Slot antrian prioritas utama selesai dalam 3-5 hari.
                  </p>

                  <div className="mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
                    <span className="text-xs text-slate-500 dark:text-slate-400">Mulai dari</span>
                    <div className="text-3xl font-black text-slate-900 dark:text-white mt-1">
                      Rp 175.000 <span className="text-xs font-medium text-slate-500 dark:text-slate-400">/ pcs</span>
                    </div>
                    <span className="text-[11px] text-slate-500 dark:text-slate-500 block mt-1">Min. pemesanan 12 pcs</span>
                  </div>

                  <ul className="space-y-3 text-xs text-slate-700 dark:text-slate-300 mb-8">
                    <li className="flex items-center gap-2.5 font-semibold text-amber-700 dark:text-amber-300">
                      <Zap className="w-4 h-4 text-amber-500 flex-shrink-0" />
                      <span>Slot Antrian Produksi Prioritas Pertama</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Selesai Kilat 3-5 Hari Kerja Garansi</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Bahan Bebas Pilih (Dryfit Premium/Brazil)</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Dedicated PIC WhatsApp Admin Khusus</span>
                    </li>
                    <li className="flex items-center gap-2.5">
                      <Check className="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Seluruh Keunggulan Paket Premium</span>
                    </li>
                  </ul>
                </div>

                <Link
                  href="/custom-order?package_id=3"
                  className="w-full py-3.5 px-4 rounded-xl text-center text-xs font-bold text-slate-800 dark:text-white bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-2 shadow-sm"
                >
                  <span>Pilih Paket Express</span>
                  <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
            </div>
          </div>
        </section>

        {/* TESTIMONIALS SECTION */}
        <section id="testimoni" className="py-20 border-y border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 transition-colors duration-200">
          <div className="max-w-6xl mx-auto px-6">
            <div className="text-center max-w-2xl mx-auto mb-16">
              <span className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-2 block">
                Kepuasan Pelanggan
              </span>
              <h2 className="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">
                Dipercaya Ratusan Tim di Indonesia
              </h2>
              <div className="flex items-center justify-center gap-1.5 mt-3 text-amber-400">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className="w-4 h-4 fill-amber-400 text-amber-400" />
                ))}
                <span className="text-xs font-bold text-slate-700 dark:text-slate-300 ml-2">5.0 / 5.0 Rating Kepuasan</span>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              {/* Testimonial 1 */}
              <div className="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 flex flex-col justify-between shadow-sm">
                <div>
                  <div className="flex items-center gap-1 text-amber-400 mb-4">
                    {[...Array(5)].map((_, i) => (
                      <Star key={i} className="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                    ))}
                  </div>
                  <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic mb-6">
                    "Pesan jersey 24 set buat turnamen futsal regional. Warna sublimnya bener-bener presisi sesuai mockup 3D, bahan dryfit-nya adem banget dipake tanding, dan tracking order-nya bikin tenang karena bisa cek tiap tahap print & jahit!"
                  </p>
                </div>
                <div className="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                  <div className="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-600/20 border border-blue-200 dark:border-blue-500/30 flex items-center justify-center font-bold text-xs text-blue-700 dark:text-blue-400">
                    GM
                  </div>
                  <div>
                    <div className="text-xs font-bold text-slate-900 dark:text-white">Rian Hidayat</div>
                    <div className="text-[11px] text-slate-500 dark:text-slate-400">Kapten Garuda Muda FC (Jakarta)</div>
                  </div>
                </div>
              </div>

              {/* Testimonial 2 */}
              <div className="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 flex flex-col justify-between shadow-sm">
                <div>
                  <div className="flex items-center gap-1 text-amber-400 mb-4">
                    {[...Array(5)].map((_, i) => (
                      <Star key={i} className="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                    ))}
                  </div>
                  <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic mb-6">
                    "Kualitas jersey esport Eighteen juara parah. Jahitan rantai di leher rapi banget, detail sponsor kecil di dada tajam dan gak pecah sama sekali. Paket express bener-bener selesai sebelum hari H tanding."
                  </p>
                </div>
                <div className="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                  <div className="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-600/20 border border-indigo-200 dark:border-indigo-500/30 flex items-center justify-center font-bold text-xs text-indigo-700 dark:text-indigo-400">
                    PE
                  </div>
                  <div>
                    <div className="text-xs font-bold text-slate-900 dark:text-white">Kevin Pratama</div>
                    <div className="text-[11px] text-slate-500 dark:text-slate-400">Manager Phoenix Esports (Bandung)</div>
                  </div>
                </div>
              </div>

              {/* Testimonial 3 */}
              <div className="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 flex flex-col justify-between shadow-sm">
                <div>
                  <div className="flex items-center gap-1 text-amber-400 mb-4">
                    {[...Array(5)].map((_, i) => (
                      <Star key={i} className="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                    ))}
                  </div>
                  <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic mb-6">
                    "Paling suka transparansi progress-nya. Mulai dari antrian bahan, print, press panas, sampai finish ada update-nya di web tanpa perlu tanya berkali-kali. Bahannya nyaman banget buat gowes 100km tanpa gerah."
                  </p>
                </div>
                <div className="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                  <div className="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-600/20 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center font-bold text-xs text-emerald-700 dark:text-emerald-400">
                    SN
                  </div>
                  <div>
                    <div className="text-xs font-bold text-slate-900 dark:text-white">Dimas Aryo</div>
                    <div className="text-[11px] text-slate-500 dark:text-slate-400">Ketua Komunitas Sepeda Nusantara</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* HIGH-CONVERSION CTA SECTION */}
        <section id="cta" className="py-24 relative overflow-hidden bg-white dark:bg-slate-900 transition-colors duration-200">
          <div className="max-w-5xl mx-auto px-6">
            <div className="relative rounded-3xl bg-gradient-to-b from-blue-600 via-blue-700 to-indigo-800 text-white p-8 sm:p-14 text-center overflow-hidden shadow-2xl">
              {/* Radial lighting glow */}
              <div className="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none" />

              <div className="relative z-10 max-w-2xl mx-auto">
                <span className="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-wider mb-6 border border-white/20">
                  <BadgeCheck className="w-4 h-4 text-white" />
                  Mulai Desain Jersey Impian Anda
                </span>

                <h2 className="text-3xl sm:text-5xl font-black text-white leading-tight mb-4">
                  Siap Tampil Beda & Juara Bersama Jersey Eighteen?
                </h2>

                <p className="text-xs sm:text-base text-blue-100 mb-8 leading-relaxed font-normal">
                  Konsultasikan konsep desain tim Anda gratis bersama spesialis apparel Eighteen. Tanpa biaya mockup awal, produksi cepat, dan status pesanan terpantau langsung secara real-time.
                </p>

                <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                  <Link
                    href="/custom-order"
                    className="w-full sm:w-auto px-8 py-4 rounded-xl bg-white hover:bg-slate-100 text-blue-900 font-bold text-xs sm:text-sm transition shadow-xl shadow-black/20 flex items-center justify-center gap-2"
                  >
                    <span>Isi Formulir Pesanan Online</span>
                    <ArrowRight className="w-4 h-4" />
                  </Link>

                  <a
                    href={waConsultUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-full sm:w-auto px-8 py-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm transition shadow-xl shadow-emerald-600/30 flex items-center justify-center gap-2 border border-emerald-400"
                  >
                    <MessageCircle className="w-4 h-4 fill-white" />
                    <span>Chat WhatsApp Desainer</span>
                  </a>
                </div>

                <p className="text-[11px] text-blue-200 mt-6">
                  ⚡ Minimum pemesanan hanya 12 pcs &bull; Free konsultasi konsep desain &bull; Kirim ke seluruh Indonesia
                </p>
              </div>
            </div>
          </div>
        </section>
      </main>

      {/* Footer */}
      <footer className="border-t border-slate-200 dark:border-slate-800 py-10 bg-slate-100 dark:bg-slate-950 text-xs text-slate-600 dark:text-slate-400 transition-colors duration-200">
        <div className="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-6">
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-1 flex items-center justify-center shadow-sm">
              <img src="/icon.png" alt="Eighteen Logo" className="w-full h-full object-contain" />
            </div>
            <div>
              <p className="font-bold text-slate-900 dark:text-white text-sm">Eighteen Custom Apparel</p>
              <p className="text-[11px] text-slate-500">Konveksi Jersey & Sportswear Printing Sublimasi</p>
            </div>
          </div>

          <div className="flex items-center gap-6 font-medium">
            <Link href="/track-order" className="hover:text-blue-600 dark:hover:text-white transition">Lacak Pesanan</Link>
            <Link href="/custom-order" className="hover:text-blue-600 dark:hover:text-white transition">Form Pemesanan</Link>
            <a href="#pricing" className="hover:text-blue-600 dark:hover:text-white transition">Paket Harga</a>
            <Link href="/login" className="hover:text-blue-600 dark:hover:text-white transition">Portal Staff</Link>
          </div>

          <p className="text-[11px] text-slate-500">
            &copy; {new Date().getFullYear()} Eighteen Custom Apparel. All rights reserved.
          </p>
        </div>
      </footer>
    </div>
  );
}
