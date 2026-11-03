import React from 'react';
import Link from 'next/link';
import ThemeToggle from '@/components/ThemeToggle';
import { 
  Shirt, 
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
  Check,
  MessageCircle,
  Award,
  Flame
} from 'lucide-react';

export default function HomePage() {
  const adminPhone = '6281234567890';
  const waConsultUrl = `https://wa.me/${adminPhone}?text=${encodeURIComponent(
    'Halo Eighteen Custom Apparel, saya ingin konsultasi pembuatan jersey untuk tim saya.'
  )}`;

  return (
    <div className="min-h-screen bg-white dark:bg-slate-900 text-slate-900 dark:text-white flex flex-col selection:bg-blue-600 selection:text-white transition-colors duration-150">
      {/* Top Navigation */}
      <header className="border-b border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 sticky top-0 z-40 transition-colors duration-150">
        <div className="max-w-7xl mx-auto px-6 h-18 flex items-center justify-between">
          {/* Brand Logo */}
          <Link href="/" className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-lg bg-slate-900 dark:bg-slate-800 flex items-center justify-center p-1.5 overflow-hidden">
              <img src="/icon.png" alt="Eighteen Logo" className="w-full h-full object-contain" />
            </div>
            <div>
              <span className="font-extrabold text-lg tracking-tight text-slate-900 dark:text-white">EIGHTEEN</span>
              <span className="block text-[10px] font-semibold text-blue-600 dark:text-blue-400 tracking-wider uppercase">Custom Apparel</span>
            </div>
          </Link>

          {/* Nav Links */}
          <nav className="hidden md:flex items-center gap-8 text-xs font-semibold text-slate-600 dark:text-slate-300">
            <a href="#alur-produksi" className="hover:text-blue-600 dark:hover:text-white transition">Alur Produksi</a>
            <a href="#pricing" className="hover:text-blue-600 dark:hover:text-white transition">Paket Layanan</a>
            <a href="#testimoni" className="hover:text-blue-600 dark:hover:text-white transition">Testimoni</a>
            <a href="#cta" className="hover:text-blue-600 dark:hover:text-white transition">Kontak</a>
          </nav>

          {/* Action Header */}
          <div className="flex items-center gap-3">
            <ThemeToggle />

            <Link
              href="/track-order"
              className="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition border border-slate-200 dark:border-slate-700"
            >
              <Search className="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" />
              <span>Lacak Pesanan</span>
            </Link>

            <Link
              href="/custom-order"
              className="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm"
            >
              <span>Pesan Sekarang</span>
              <ArrowRight className="w-3.5 h-3.5" />
            </Link>

            <Link
              href="/login"
              className="hidden lg:flex items-center gap-1.5 text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 pl-2 transition"
              title="Akses Internal Staff"
            >
              <Lock className="w-3 h-3" />
              <span>Staff</span>
            </Link>
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="flex-1">
        {/* HERO SECTION */}
        <section className="py-12 lg:py-20 bg-slate-50/60 dark:bg-slate-900/60 transition-colors duration-150">
          <div className="max-w-7xl mx-auto px-6">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
              {/* Left Column: Headline, Specs & Actions */}
              <div className="lg:col-span-7 space-y-6 text-left">
                <span className="inline-block text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                  Konveksi & Apparel Printing Olahraga
                </span>

                <h1 className="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">
                  Produksi Jersey dan Sportswear Custom Berkualitas untuk Tim Anda
                </h1>

                <p className="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl font-normal">
                  Melayani pesanan jersey futsal, sepak bola, basket, running, esport, dan komunitas dengan sistem cetak sublimasi full print presisi tinggi serta pemantauan tahapan produksi secara transparan.
                </p>

                {/* Action Buttons */}
                <div className="flex flex-wrap items-center gap-3 pt-2">
                  <Link
                    href="/custom-order"
                    className="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm transition shadow-sm flex items-center gap-2"
                  >
                    <span>Form Pemesanan Online</span>
                    <ArrowRight className="w-4 h-4" />
                  </Link>

                  <Link
                    href="/track-order"
                    className="px-5 py-3 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs sm:text-sm transition border border-slate-200 dark:border-slate-700 flex items-center gap-2 shadow-sm"
                  >
                    <Search className="w-4 h-4 text-slate-500 dark:text-slate-400" />
                    <span>Lacak Status Pesanan</span>
                  </Link>

                  <a
                    href={waConsultUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="px-5 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-bold text-xs sm:text-sm transition border border-emerald-200 dark:border-emerald-800 flex items-center gap-2"
                  >
                    <MessageCircle className="w-4 h-4" />
                    <span>Konsultasi Desain</span>
                  </a>
                </div>

                {/* Key Facts */}
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-xs">
                  <div className="p-3 bg-white dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/80">
                    <div className="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                      <ShieldCheck className="w-4 h-4 text-blue-600 dark:text-blue-400" />
                      Garansi Produk
                    </div>
                    <div className="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5">Jaminan kerapian jahitan</div>
                  </div>

                  <div className="p-3 bg-white dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/80">
                    <div className="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                      <Clock className="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                      SOP Tepat Waktu
                    </div>
                    <div className="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5">Jadwal terpantau di sistem</div>
                  </div>

                  <div className="p-3 bg-white dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/80">
                    <div className="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                      <Flame className="w-4 h-4 text-indigo-600 dark:text-indigo-400" />
                      Sublimasi HD
                    </div>
                    <div className="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5">Tinta tahan cuci & tajam</div>
                  </div>

                  <div className="p-3 bg-white dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/80">
                    <div className="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                      <Award className="w-4 h-4 text-amber-500" />
                      Min. 12 Pcs
                    </div>
                    <div className="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5">Bisa pesan untuk 1 tim</div>
                  </div>
                </div>
              </div>

              {/* Right Column: Clean Brand Showcase */}
              <div className="lg:col-span-5">
                <div className="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-5 shadow-sm space-y-4">
                  <div className="rounded-xl bg-slate-950 p-4 border border-slate-800 flex items-center justify-center overflow-hidden">
                    <img
                      src="/hero-logo.jpg"
                      alt="Eighteen Custom Apparel"
                      className="w-full h-auto max-h-44 object-contain rounded-md"
                    />
                  </div>

                  <div className="space-y-2 text-xs text-slate-700 dark:text-slate-300">
                    <div className="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 flex items-center gap-2">
                      <Check className="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Cetak Sublimasi Panas 205°C menyatu ke serat kain</span>
                    </div>
                    <div className="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 flex items-center gap-2">
                      <Check className="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Jahitan rantai pundak dan kerah rib spandex elastis</span>
                    </div>
                    <div className="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 flex items-center gap-2">
                      <Check className="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Pilihan bahan Dryfit Milano, Benzema, Brazil, dan Serena</span>
                    </div>
                  </div>

                  <div className="pt-2 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between border-t border-slate-100 dark:border-slate-700/60 font-medium">
                    <span>Eighteen Apparel Production Workshop</span>
                    <span className="text-emerald-600 dark:text-emerald-400 font-semibold">Kapasitas 5.000+ Pcs/Bulan</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* ALUR PRODUKSI */}
        <section id="alur-produksi" className="py-16 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-150">
          <div className="max-w-6xl mx-auto px-6">
            <div className="text-center max-w-2xl mx-auto mb-12">
              <span className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">
                Alur Produksi
              </span>
              <h2 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                5 Tahapan Manufaktur Apparel
              </h2>
              <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-2">
                Setiap tahapan dikerjakan dengan standar checklist QC untuk memastikan pesanan selesai sesuai jadwal.
              </p>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
              {[
                { step: '01', title: 'Print Sublim', desc: 'Pencetakan desain resolusi tinggi ke transfer paper', icon: Printer },
                { step: '02', title: 'Press Pemanas', desc: 'Pemindahan warna ke kain menggunakan mesin roll press', icon: Layers },
                { step: '03', title: 'Potong Pola', desc: 'Pemotongan kain sesuai ukuran dan pola jersey', icon: Scissors },
                { step: '04', title: 'Penjahitan', desc: 'Perakitan badan, lengan, rib leher, dan obras benang', icon: Shirt },
                { step: '05', title: 'Finishing & QC', desc: 'Pembersihan sisa benang, steam, cek ukuran, dan packing', icon: CheckCircle2 },
              ].map((item) => {
                const Icon = item.icon;
                return (
                  <div
                    key={item.step}
                    className="p-5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 text-left space-y-3"
                  >
                    <div className="flex items-center justify-between">
                      <span className="text-xs font-black text-blue-600 dark:text-blue-400">{item.step}</span>
                      <Icon className="w-4 h-4 text-slate-400" />
                    </div>
                    <div>
                      <h3 className="text-sm font-bold text-slate-900 dark:text-white">{item.title}</h3>
                      <p className="text-xs text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">{item.desc}</p>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* PRICING SECTION */}
        <section id="pricing" className="py-16 bg-slate-50/70 dark:bg-slate-950/70 border-t border-slate-200 dark:border-slate-800 transition-colors duration-150">
          <div className="max-w-6xl mx-auto px-6">
            <div className="text-center max-w-2xl mx-auto mb-12">
              <span className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">
                Paket Layanan
              </span>
              <h2 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                Pilihan Paket Produksi Jersey
              </h2>
              <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-2">
                Seluruh paket berlaku untuk minimum pemesanan 12 pcs dan mencakup cetak nama serta nomor punggung.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
              {/* Paket Reguler */}
              <div className="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-7 flex flex-col justify-between shadow-sm">
                <div className="space-y-4">
                  <div>
                    <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">Standar</span>
                    <h3 className="text-xl font-black text-slate-900 dark:text-white mt-1">Paket Reguler</h3>
                    <p className="text-xs text-slate-600 dark:text-slate-400 mt-1">Untuk seragam santai, sparing tim, dan turnamen lokal.</p>
                  </div>

                  <div className="py-3 border-y border-slate-100 dark:border-slate-800">
                    <span className="text-2xl font-black text-slate-900 dark:text-white">Rp 120.000</span>
                    <span className="text-xs text-slate-500 font-medium ml-1">/ pcs</span>
                    <div className="text-[11px] text-slate-400 mt-0.5">Estimasi pengerjaan 7-10 hari kerja</div>
                  </div>

                  <ul className="space-y-2.5 text-xs text-slate-700 dark:text-slate-300">
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Bahan Milano Dryfit atau Serena</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Full Print Sublimasi HD</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Jahitan Standar Konveksi Obras</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Gratis cetak nama dan nomor punggung</span>
                    </li>
                  </ul>
                </div>

                <Link
                  href="/custom-order?package_id=1"
                  className="mt-6 w-full py-2.5 px-4 rounded-xl text-center text-xs font-bold text-slate-800 dark:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition"
                >
                  Pilih Paket Reguler
                </Link>
              </div>

              {/* Paket Premium */}
              <div className="rounded-2xl bg-white dark:bg-slate-900 border-2 border-blue-600 p-7 flex flex-col justify-between shadow-sm relative">
                <div className="space-y-4">
                  <div>
                    <span className="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 mb-1">
                      Pilihan Utama
                    </span>
                    <h3 className="text-xl font-black text-slate-900 dark:text-white">Paket Premium</h3>
                    <p className="text-xs text-slate-600 dark:text-slate-400 mt-1">Standar liga resmi turnamen kompetitif dan apparel esport.</p>
                  </div>

                  <div className="py-3 border-y border-slate-100 dark:border-slate-800">
                    <span className="text-2xl font-black text-blue-600 dark:text-blue-400">Rp 145.000</span>
                    <span className="text-xs text-slate-500 font-medium ml-1">/ pcs</span>
                    <div className="text-[11px] text-slate-400 mt-0.5">Estimasi pengerjaan 5-7 hari kerja</div>
                  </div>

                  <ul className="space-y-2.5 text-xs text-slate-700 dark:text-slate-300">
                    <li className="flex items-center gap-2 font-medium">
                      <Check className="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Bahan Milano Premium, Benzema, atau Brazil</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Sublimasi Ultra HD Tinta Original Awet</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Kerah Rib Spandex Elastis dan Jahit Rantai</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Termasuk label size woven dan plastik polybag</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                      <span>Gratis pembuatan mockup dan penyesuaian desain</span>
                    </li>
                  </ul>
                </div>

                <Link
                  href="/custom-order?package_id=2"
                  className="mt-6 w-full py-2.5 px-4 rounded-xl text-center text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition"
                >
                  Pilih Paket Premium
                </Link>
              </div>

              {/* Paket Express */}
              <div className="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-7 flex flex-col justify-between shadow-sm">
                <div className="space-y-4">
                  <div>
                    <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">Kilat</span>
                    <h3 className="text-xl font-black text-slate-900 dark:text-white mt-1">Paket Express</h3>
                    <p className="text-xs text-slate-600 dark:text-slate-400 mt-1">Slot antrian prioritas bagi tim yang butuh jersey dalam waktu cepat.</p>
                  </div>

                  <div className="py-3 border-y border-slate-100 dark:border-slate-800">
                    <span className="text-2xl font-black text-slate-900 dark:text-white">Rp 175.000</span>
                    <span className="text-xs text-slate-500 font-medium ml-1">/ pcs</span>
                    <div className="text-[11px] text-slate-400 mt-0.5">Estimasi pengerjaan 3-5 hari kerja</div>
                  </div>

                  <ul className="space-y-2.5 text-xs text-slate-700 dark:text-slate-300">
                    <li className="flex items-center gap-2 font-medium">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Antrian produksi prioritas nomor satu</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Pengerjaan selesai 3-5 hari kerja</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Seluruh spesifikasi bahan Paket Premium</span>
                    </li>
                    <li className="flex items-center gap-2">
                      <Check className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                      <span>Koordinasi langsung via admin WhatsApp khusus</span>
                    </li>
                  </ul>
                </div>

                <Link
                  href="/custom-order?package_id=3"
                  className="mt-6 w-full py-2.5 px-4 rounded-xl text-center text-xs font-bold text-slate-800 dark:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition"
                >
                  Pilih Paket Express
                </Link>
              </div>
            </div>
          </div>
        </section>

        {/* TESTIMONIALS SECTION */}
        <section id="testimoni" className="py-16 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-150">
          <div className="max-w-6xl mx-auto px-6">
            <div className="text-center max-w-2xl mx-auto mb-12">
              <span className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">
                Ulasan Pelanggan
              </span>
              <h2 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                Pengalaman Tim Bersama Eighteen
              </h2>
              <div className="flex items-center justify-center gap-1 mt-2 text-amber-500">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className="w-4 h-4 fill-amber-500" />
                ))}
                <span className="text-xs font-semibold text-slate-700 dark:text-slate-300 ml-1.5">5.0 dari ratusan komunitas</span>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              {/* Testimonial 1 */}
              <div className="p-6 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 flex flex-col justify-between">
                <p className="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-4">
                  "Pesan 24 set jersey futsal untuk turnamen. Hasil cetak warna sesuai dengan rancangan desain, bahan kain adem saat digunakan bertanding, dan kemajuan proses jahit dapat dipantau di halaman tracking."
                </p>
                <div>
                  <div className="text-xs font-bold text-slate-900 dark:text-white">Rian Hidayat</div>
                  <div className="text-[11px] text-slate-500">Garuda Muda FC, Jakarta</div>
                </div>
              </div>

              {/* Testimonial 2 */}
              <div className="p-6 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 flex flex-col justify-between">
                <p className="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-4">
                  "Kualitas jersey tim esport kami rapi. Jahitan rib leher kokoh dan logo sponsor di bagian dada terlihat tajam tidak pecah. Pengerjaan paket express tepat waktu sebelum hari pertandingan."
                </p>
                <div>
                  <div className="text-xs font-bold text-slate-900 dark:text-white">Kevin Pratama</div>
                  <div className="text-[11px] text-slate-500">Phoenix Esports, Bandung</div>
                </div>
              </div>

              {/* Testimonial 3 */}
              <div className="p-6 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 flex flex-col justify-between">
                <p className="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-4">
                  "Transparansi pengerjaan sangat membantu. Mulai dari potong pola sampai siap kirim ada update statusnya. Bahannya nyaman untuk rute gowes jarak jauh."
                </p>
                <div>
                  <div className="text-xs font-bold text-slate-900 dark:text-white">Dimas Aryo</div>
                  <div className="text-[11px] text-slate-500">Komunitas Sepeda, Surabaya</div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* CTA SECTION */}
        <section id="cta" className="py-16 bg-slate-900 text-white border-t border-slate-800 transition-colors duration-150">
          <div className="max-w-4xl mx-auto px-6 text-center space-y-5">
            <h2 className="text-2xl sm:text-4xl font-black tracking-tight text-white">
              Siap Memulai Produksi Jersey Tim Anda?
            </h2>

            <p className="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed">
              Konsultasikan kebutuhan model, bahan, dan desain seragam olahraga Anda bersama Eighteen Custom Apparel. Minimum order mulai 12 pcs.
            </p>

            <div className="flex flex-wrap items-center justify-center gap-3 pt-2">
              <Link
                href="/custom-order"
                className="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm transition shadow-sm flex items-center gap-2"
              >
                <span>Isi Form Pesanan</span>
                <ArrowRight className="w-4 h-4" />
              </Link>

              <a
                href={waConsultUrl}
                target="_blank"
                rel="noopener noreferrer"
                className="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition flex items-center gap-2"
              >
                <MessageCircle className="w-4 h-4" />
                <span>Hubungi Admin WhatsApp</span>
              </a>
            </div>
          </div>
        </section>
      </main>

      {/* Footer */}
      <footer className="border-t border-slate-200 dark:border-slate-800 py-8 bg-white dark:bg-slate-950 text-xs text-slate-500 dark:text-slate-400 transition-colors duration-150">
        <div className="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div className="flex items-center gap-2.5">
            <div className="w-6 h-6 rounded bg-slate-900 dark:bg-slate-800 p-1 flex items-center justify-center">
              <img src="/icon.png" alt="Eighteen Logo" className="w-full h-full object-contain" />
            </div>
            <span className="font-bold text-slate-800 dark:text-white">Eighteen Custom Apparel</span>
          </div>

          <div className="flex items-center gap-6">
            <Link href="/track-order" className="hover:text-slate-900 dark:hover:text-white transition">Lacak Pesanan</Link>
            <Link href="/custom-order" className="hover:text-slate-900 dark:hover:text-white transition">Form Pesanan</Link>
            <a href="#pricing" className="hover:text-slate-900 dark:hover:text-white transition">Paket Layanan</a>
            <Link href="/login" className="hover:text-slate-900 dark:hover:text-white transition">Staff Portal</Link>
          </div>

          <p className="text-[11px]">
            &copy; {new Date().getFullYear()} Eighteen Custom Apparel.
          </p>
        </div>
      </footer>
    </div>
  );
}
