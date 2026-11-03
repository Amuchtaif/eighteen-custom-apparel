'use client';

import React, { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { Shirt, ArrowLeft, Send, Sparkles, AlertCircle } from 'lucide-react';

export default function CustomOrderPage() {
  const router = useRouter();
  const [packages, setPackages] = useState<any[]>([]);
  const [materials, setMaterials] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const [formData, setFormData] = useState({
    nama_pemesan: '',
    no_wa: '',
    email: '',
    nama_tim: '',
    paket_id: '',
    bahan: 'Dryfit',
    estimasi_qty: 12,
    deadline: '',
    catatan_pesanan: '',
  });

  useEffect(() => {
    // Fetch packages and materials
    fetch('/api/orders')
      .then((res) => res.json())
      .then((data) => {
        if (data.meta) {
          setPackages(data.meta.packages || []);
          setMaterials(data.meta.materials || []);
          if (data.meta.packages?.[0]) {
            setFormData((prev) => ({ ...prev, paket_id: String(data.meta.packages[0].id) }));
          }
        }
      })
      .catch((e) => console.error(e));
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const res = await fetch('/api/custom-order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal mengirim pesanan.');
      }

      router.push(data.redirect_url || `/custom-order/success?order_number=${data.order_number}`);
    } catch (err: any) {
      setError(err.message || 'Terjadi kesalahan.');
    } finally {
      setLoading(false);
    }
  };

  const minDate = new Date();
  minDate.setDate(minDate.getDate() + 3);
  const minDateStr = minDate.toISOString().split('T')[0];

  return (
    <div className="min-h-screen bg-slate-900 text-white py-12 px-4 selection:bg-blue-600 selection:text-white">
      <div className="max-w-3xl mx-auto">
        {/* Back Link */}
        <Link
          href="/"
          className="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white mb-8 transition"
        >
          <ArrowLeft className="w-4 h-4" />
          <span>Kembali ke Beranda</span>
        </Link>

        {/* Card */}
        <div className="bg-slate-950 border border-slate-800 rounded-3xl p-8 sm:p-12 shadow-2xl relative">
          <div className="flex items-center gap-3 mb-6">
            <div className="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center font-black text-white shadow-lg shadow-blue-500/30">
              <Shirt className="w-6 h-6" />
            </div>
            <div>
              <h1 className="text-2xl font-black text-white tracking-tight">Formulir Pemesanan Custom Apparel</h1>
              <p className="text-xs text-slate-400 mt-0.5">
                Isi spesifikasi pesanan Anda. Sistem kami akan menerbitkan nomor pesanan dan menghubungkan ke WhatsApp Admin.
              </p>
            </div>
          </div>

          {error && (
            <div className="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs flex items-center gap-2.5">
              <AlertCircle className="w-4 h-4 flex-shrink-0" />
              <span>{error}</span>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Pemesan Information */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-300 mb-1.5">
                  Nama Pemesan / Kontak <span className="text-red-400">*</span>
                </label>
                <input
                  type="text"
                  required
                  placeholder="Contoh: Budi Santoso"
                  value={formData.nama_pemesan}
                  onChange={(e) => setFormData({ ...formData, nama_pemesan: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-300 mb-1.5">
                  Nomor WhatsApp Aktif <span className="text-red-400">*</span>
                </label>
                <input
                  type="text"
                  required
                  placeholder="Contoh: 081234567890"
                  value={formData.no_wa}
                  onChange={(e) => setFormData({ ...formData, no_wa: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-300 mb-1.5">Nama Tim / Komunitas</label>
                <input
                  type="text"
                  placeholder="Contoh: Garuda Muda FC"
                  value={formData.nama_tim}
                  onChange={(e) => setFormData({ ...formData, nama_tim: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-300 mb-1.5">Email (Opsional)</label>
                <input
                  type="email"
                  placeholder="nama@email.com"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                />
              </div>
            </div>

            {/* Spesifikasi Pesanan */}
            <div className="pt-4 border-t border-slate-800/80 grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-300 mb-1.5">
                  Paket Kualitas <span className="text-red-400">*</span>
                </label>
                <select
                  required
                  value={formData.paket_id}
                  onChange={(e) => setFormData({ ...formData, paket_id: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:outline-none focus:border-blue-500 transition"
                >
                  <option value="">Pilih Paket</option>
                  {packages.map((p) => (
                    <option key={p.id} value={p.id}>
                      {p.name}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-300 mb-1.5">Jenis Bahan</label>
                <select
                  value={formData.bahan}
                  onChange={(e) => setFormData({ ...formData, bahan: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:outline-none focus:border-blue-500 transition"
                >
                  <option value="Dryfit">Dryfit Jarum (Sporty)</option>
                  <option value="Serena">Serena Premium (Smooth)</option>
                  <option value="Milano">Dryfit Milano (Zigzag)</option>
                  <option value="Cotton Combed">Cotton Combed 30s</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-300 mb-1.5">
                  Estimasi Qty (Pcs) <span className="text-red-400">*</span>
                </label>
                <input
                  type="number"
                  min="1"
                  required
                  value={formData.estimasi_qty}
                  onChange={(e) => setFormData({ ...formData, estimasi_qty: Number(e.target.value) })}
                  className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:outline-none focus:border-blue-500 transition"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-slate-300 mb-1.5">
                Target Deadline Selesai <span className="text-red-400">*</span>
              </label>
              <input
                type="date"
                min={minDateStr}
                required
                value={formData.deadline}
                onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
                className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:outline-none focus:border-blue-500 transition"
              />
            </div>

            <div>
              <label className="block text-xs font-bold text-slate-300 mb-1.5">
                Catatan Desain, Warna, & Spesifikasi Khusus
              </label>
              <textarea
                rows={4}
                placeholder="Tuliskan warna dominan, kerah (V-neck / O-neck), lengan panjang/pendek, atau detail sponsor..."
                value={formData.catatan_pesanan}
                onChange={(e) => setFormData({ ...formData, catatan_pesanan: e.target.value })}
                className="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
              />
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-4 px-6 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition shadow-xl shadow-blue-600/30 flex items-center justify-center gap-2 disabled:opacity-50"
            >
              <Send className="w-4 h-4" />
              <span>{loading ? 'Mengirim Pesanan...' : 'Kirim Pesanan & Dapatkan Kode Tracking'}</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}

