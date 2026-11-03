'use client';

import React, { useState, useEffect, Suspense } from 'react';
import { useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { 
  Search, 
  ArrowLeft, 
  Shirt, 
  CheckCircle2, 
  Clock, 
  AlertCircle,
  PackageCheck,
  Calendar,
  Layers
} from 'lucide-react';

function TrackOrderContent() {
  const searchParams = useSearchParams();
  const initialCode = searchParams.get('code') || '';

  const [code, setCode] = useState(initialCode);
  const [loading, setLoading] = useState(false);
  const [orderData, setOrderData] = useState<any>(null);
  const [error, setError] = useState('');

  const fetchTracking = async (searchCode: string) => {
    if (!searchCode.trim()) return;
    setLoading(true);
    setError('');

    try {
      const res = await fetch(`/api/track-order?code=${encodeURIComponent(searchCode.trim())}`);
      const data = await res.json();

      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Pesanan tidak ditemukan.');
      }

      setOrderData(data);
    } catch (err: any) {
      setError(err.message || 'Terjadi kesalahan.');
      setOrderData(null);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (initialCode) {
      fetchTracking(initialCode);
    }
  }, [initialCode]);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    fetchTracking(code);
  };

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

        {/* Tracking Input Card */}
        <div className="bg-slate-950 border border-slate-800 rounded-3xl p-8 shadow-2xl mb-8">
          <div className="text-center mb-6">
            <h1 className="text-2xl font-black text-white">Lacak Progress Pesanan</h1>
            <p className="text-xs text-slate-400 mt-1">
              Masukkan nomor pesanan (Contoh: <span className="font-mono text-blue-400">ORD-202611-0001</span>)
            </p>
          </div>

          <form onSubmit={handleSearch} className="flex gap-2">
            <div className="relative flex-1">
              <input
                type="text"
                required
                placeholder="ORD-YYYYMM-XXXX"
                value={code}
                onChange={(e) => setCode(e.target.value)}
                className="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-900 border border-slate-800 text-white font-mono text-xs uppercase placeholder:normal-case placeholder:font-sans placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
              />
              <Search className="w-4 h-4 text-slate-500 absolute left-4 top-4" />
            </div>
            <button
              type="submit"
              disabled={loading}
              className="px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition shadow-lg shadow-blue-600/30 disabled:opacity-50 flex items-center gap-2"
            >
              <span>{loading ? 'Mencari...' : 'Cari'}</span>
            </button>
          </form>

          {error && (
            <div className="mt-4 p-3.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs flex items-center gap-2.5">
              <AlertCircle className="w-4 h-4 flex-shrink-0" />
              <span>{error}</span>
            </div>
          )}
        </div>

        {/* Tracking Details */}
        {orderData && (
          <div className="bg-slate-950 border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl animate-fadeIn">
            {/* Order Header Summary */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-800 gap-4 mb-8">
              <div>
                <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                  Nomor Pesanan
                </span>
                <h2 className="text-xl font-black font-mono text-white mt-0.5">
                  {orderData.order.order_number}
                </h2>
                <p className="text-sm font-bold text-blue-400 mt-1">{orderData.order.team_name}</p>
              </div>

              <div className="sm:text-right">
                <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                  Status Saat Ini
                </span>
                <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-blue-500/20 text-blue-400 border border-blue-500/30">
                  <Clock className="w-3.5 h-3.5" />
                  {orderData.order.status}
                </span>
              </div>
            </div>

            {/* Quick Specs */}
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 rounded-2xl bg-slate-900 border border-slate-800 mb-8 text-xs">
              <div>
                <span className="text-slate-400 block text-[10px]">Bahan</span>
                <span className="font-bold text-white">{orderData.order.bahan || '-'}</span>
              </div>
              <div>
                <span className="text-slate-400 block text-[10px]">Jumlah</span>
                <span className="font-bold text-white">{orderData.order.qty} pcs</span>
              </div>
              <div>
                <span className="text-slate-400 block text-[10px]">Tanggal Masuk</span>
                <span className="font-bold text-white">{orderData.order.date}</span>
              </div>
              <div>
                <span className="text-slate-400 block text-[10px]">Target Deadline</span>
                <span className="font-bold text-amber-400">{orderData.order.deadline}</span>
              </div>
            </div>

            {/* Stage Progress Timeline */}
            <div>
              <h3 className="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">
                Tahapan Alur Produksi
              </h3>

              <div className="space-y-4">
                {orderData.stages.map((stage: any, idx: number) => {
                  const isCompleted = stage.status === 'completed';
                  const isActive = stage.status === 'active';

                  return (
                    <div key={idx} className="flex items-center gap-4">
                      {/* Step Indicator Icon */}
                      <div
                        className={`w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 transition-all ${
                          isCompleted
                            ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 shadow-sm shadow-emerald-500/20'
                            : isActive
                            ? 'bg-blue-600 text-white ring-4 ring-blue-500/20 animate-pulse'
                            : 'bg-slate-900 text-slate-500 border border-slate-800'
                        }`}
                      >
                        {isCompleted ? (
                          <CheckCircle2 className="w-5 h-5" />
                        ) : (
                          <span className="text-xs font-bold">{idx + 1}</span>
                        )}
                      </div>

                      {/* Stage Name */}
                      <div className="flex-1">
                        <h4
                          className={`text-xs font-bold ${
                            isActive ? 'text-blue-400 font-extrabold' : isCompleted ? 'text-slate-200' : 'text-slate-400'
                          }`}
                        >
                          {stage.name} - {stage.label}
                        </h4>
                        <p className="text-[11px] text-slate-400">
                          {isCompleted ? 'Tahapan telah selesai' : isActive ? 'Sedang dalam pengerjaan tim produksi' : 'Menunggu antrian pengerjaan'}
                        </p>
                      </div>

                      {/* Status Badge */}
                      <span
                        className={`text-[10px] font-bold px-2.5 py-0.5 rounded-full border ${
                          isCompleted
                            ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                            : isActive
                            ? 'bg-blue-500/10 text-blue-400 border-blue-500/20'
                            : 'bg-slate-900 text-slate-400 border-slate-800'
                        }`}
                      >
                        {isCompleted ? 'Selesai' : isActive ? 'Proses' : 'Menunggu'}
                      </span>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

export default function TrackOrderPage() {
  return (
    <Suspense fallback={<div className="min-h-screen bg-slate-950 flex items-center justify-center text-white">Memuat pelacakan pesanan...</div>}>
      <TrackOrderContent />
    </Suspense>
  );
}
