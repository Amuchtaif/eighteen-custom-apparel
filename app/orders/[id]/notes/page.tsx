'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useParams, useRouter } from 'next/navigation';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import TipTapEditor from '@/components/TipTapEditor';
import { ArrowLeft, Save, Sparkles, CheckCircle2, AlertCircle, FileText, Printer } from 'lucide-react';

export default function OrderNotesPage() {
  const params = useParams();
  const router = useRouter();
  const orderId = params?.id as string;

  const [orderInfo, setOrderInfo] = useState<{ kode_order?: string; nama_team?: string }>({});
  const [content, setContent] = useState<string>('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' } | null>(null);

  const showToast = (message: string, type: 'success' | 'error' = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  };

  useEffect(() => {
    if (!orderId) return;

    const fetchNotes = async () => {
      setLoading(true);
      try {
        const res = await fetch(`/api/orders/${orderId}/notes`);
        const data = await res.json();
        if (data.success) {
          setOrderInfo({ kode_order: data.kode_order, nama_team: data.nama_team });
          setContent(data.notes_html || '');
        } else {
          showToast(data.message || 'Gagal memuat catatan', 'error');
        }
      } catch (err: any) {
        showToast('Gagal terhubung ke server', 'error');
      } finally {
        setLoading(false);
      }
    };

    fetchNotes();
  }, [orderId]);

  const handleSave = async () => {
    setSaving(true);
    try {
      const res = await fetch(`/api/orders/${orderId}/notes`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ notes_html: content }),
      });
      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal menyimpan');
      }
      showToast('Catatan spesifikasi berhasil disimpan!', 'success');
    } catch (err: any) {
      showToast(err.message || 'Gagal menyimpan catatan', 'error');
    } finally {
      setSaving(false);
    }
  };

  const handleInsertTemplate = () => {
    const template = `
      <h3>SPESIFIKASI PRODUKSI APPAREL CUSTOM</h3>
      <p><strong>Model / Pola:</strong> Raglan / Regular Fit</p>
      <p><strong>Bahan Badan:</strong> Milano Dryfit Premium</p>
      <p><strong>Kerah:</strong> V-Neck Rib Spandex Elastis</p>
      <p><strong>Lengan:</strong> Manset Rib 2.5cm</p>
      <hr>
      <h4>Instruksi Sublimasi & Press:</h4>
      <ul>
        <li>Gunakan kertas transfer high-release 100gsm</li>
        <li>Suhu press 205°C durasi 32 detik</li>
        <li>Pastikan logo dada dan sponsor sejajar dan simetris</li>
      </ul>
      <h4>Instruksi Jahitan:</h4>
      <ul>
        <li>Jahit rantai pada bahu dan leher belakang</li>
        <li>Obras benang 4 kuat dan rapi</li>
        <li>Label size dan washing instruction terpasang rapi di leher dalam</li>
      </ul>
    `;
    setContent((prev) => prev ? prev + '<br>' + template : template);
  };

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Admin Produksi" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title={`Catatan & Spesifikasi: ${orderInfo.kode_order || 'Loading...'}`}
          subtitle={`Lembar kerja instruksi pengerjaan produksi untuk ${orderInfo.nama_team || ''}`}
        />

        <main className="flex-1 p-8 mt-16 max-w-5xl w-full mx-auto space-y-6">
          {/* Toast Notification */}
          {toast && (
            <div
              className={`fixed top-20 right-8 z-50 p-4 rounded-xl shadow-xl text-xs font-bold flex items-center gap-2 border animate-fadeIn ${
                toast.type === 'success'
                  ? 'bg-emerald-600 text-white border-emerald-500'
                  : 'bg-rose-600 text-white border-rose-500'
              }`}
            >
              {toast.type === 'success' ? <CheckCircle2 className="w-4 h-4" /> : <AlertCircle className="w-4 h-4" />}
              <span>{toast.message}</span>
            </div>
          )}

          {/* Action Header */}
          <div className="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <Link
              href="/orders"
              className="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200"
            >
              <ArrowLeft className="w-4 h-4" />
              <span>Kembali ke Daftar Pesanan</span>
            </Link>

            <div className="flex items-center gap-2">
              <button
                type="button"
                onClick={handleInsertTemplate}
                className="flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition border border-indigo-200"
                title="Sisipkan template spesifikasi produksi siap pakai"
              >
                <Sparkles className="w-4 h-4" />
                <span>Sisipkan Template SOP</span>
              </button>

              <button
                type="button"
                onClick={() => window.print()}
                className="hidden sm:flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition"
              >
                <Printer className="w-4 h-4" />
                <span>Cetak SPK</span>
              </button>

              <button
                type="button"
                onClick={handleSave}
                disabled={saving || loading}
                className="flex items-center gap-1.5 px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-md shadow-blue-500/20 disabled:opacity-50"
              >
                <Save className="w-4 h-4" />
                <span>{saving ? 'Menyimpan...' : 'Simpan Catatan'}</span>
              </button>
            </div>
          </div>

          {/* Editor Container */}
          <div className="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="mb-4 pb-4 border-b border-slate-100 flex items-center justify-between">
              <div>
                <h3 className="text-base font-black text-slate-800 flex items-center gap-2">
                  <FileText className="w-4 h-4 text-blue-600" />
                  Instruksi Teknis & Catatan Khusus
                </h3>
                <p className="text-xs text-slate-500 mt-0.5">
                  Gunakan editor berbasis blok (TipTap) untuk mencatat detail print, penomoran nama pemain, pola jahit, dan instruksi qc.
                </p>
              </div>
              <div className="text-xs font-medium text-slate-400">
                HTML Rich Text
              </div>
            </div>

            {loading ? (
              <div className="p-12 text-center text-sm text-slate-400">Memuat catatan...</div>
            ) : (
              <TipTapEditor
                content={content}
                onChange={(html) => setContent(html)}
                placeholder="Mulai ketik instruksi spesifikasi produksi atau gunakan tombol Sisipkan Template SOP..."
              />
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
