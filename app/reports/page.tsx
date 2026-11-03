'use client';

import React, { useState, useEffect } from 'react';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import { 
  BarChart3, 
  TrendingUp, 
  ShoppingBag, 
  Clock, 
  CheckCircle2, 
  Printer, 
  Calendar, 
  DollarSign, 
  Layers 
} from 'lucide-react';
import { formatRupiah } from '@/lib/utils';

export default function ReportsPage() {
  const [data, setData] = useState<{
    monthly_sales?: any[];
    stages_distribution?: any[];
    attendance_recap?: any[];
  }>({});
  const [loading, setLoading] = useState(true);

  const fetchReports = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/reports');
      const json = await res.json();
      if (json.success) {
        setData(json);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchReports();
  }, []);

  const monthlySales = data.monthly_sales || [];
  const stages = data.stages_distribution || [];
  const attendances = data.attendance_recap || [];

  const maxRevenue = Math.max(...monthlySales.map((m) => Number(m.revenue) || 0), 1);
  const totalRevenueAll = monthlySales.reduce((acc, m) => acc + Number(m.revenue || 0), 0);
  const totalQtyAll = monthlySales.reduce((acc, m) => acc + Number(m.total_qty || 0), 0);
  const totalOrdersAll = monthlySales.reduce((acc, m) => acc + Number(m.total_orders || 0), 0);

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Executive Owner" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Laporan Bisnis & Analitik Produksi"
          subtitle="Ringkasan omset penjualan bulanan, utilisasi tahapan produksi, dan rekapitulasi SDM"
        />

        <main className="flex-1 p-8 mt-16 max-w-7xl w-full mx-auto space-y-6">
          {/* Header Action */}
          <div className="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
              <div className="text-xs font-bold uppercase text-slate-400">Periode Analisis</div>
              <div className="text-sm font-black text-slate-800">Performa 6 Bulan Terakhir</div>
            </div>

            <button
              onClick={() => window.print()}
              className="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition"
            >
              <Printer className="w-4 h-4" />
              <span>Cetak Laporan</span>
            </button>
          </div>

          {/* 3 Executive High-level Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Omset Penjualan</div>
                <div className="text-2xl font-black text-blue-600 mt-1">{formatRupiah(totalRevenueAll)}</div>
                <div className="text-[11px] text-slate-400 mt-0.5">Dari 6 periode bulan terakhir</div>
              </div>
              <div className="p-3 rounded-xl bg-blue-50 text-blue-600 border border-blue-200">
                <DollarSign className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Output Jersey</div>
                <div className="text-2xl font-black text-emerald-600 mt-1">{totalQtyAll} Pcs</div>
                <div className="text-[11px] text-slate-400 mt-0.5">Volume produksi tercatat</div>
              </div>
              <div className="p-3 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                <ShoppingBag className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total SPK Dikerjakan</div>
                <div className="text-2xl font-black text-indigo-600 mt-1">{totalOrdersAll} Pesanan</div>
                <div className="text-[11px] text-slate-400 mt-0.5">Rata-rata {Math.round(totalQtyAll / (totalOrdersAll || 1))} pcs/order</div>
              </div>
              <div className="p-3 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200">
                <Layers className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Monthly Revenue Chart Bars */}
          <div className="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div className="flex items-center justify-between">
              <div>
                <h3 className="text-base font-black text-slate-800 flex items-center gap-2">
                  <TrendingUp className="w-4 h-4 text-blue-600" />
                  Tren Omset Bulanan
                </h3>
                <p className="text-xs text-slate-500">Pertumbuhan pendapatan kotor konveksi per bulan</p>
              </div>
            </div>

            {loading ? (
              <div className="p-12 text-center text-xs text-slate-400">Memuat grafik analitik...</div>
            ) : monthlySales.length === 0 ? (
              <div className="p-8 text-center text-xs text-slate-400">Belum ada data penjualan tercatat.</div>
            ) : (
              <div className="space-y-4 pt-4">
                {monthlySales.map((item, idx) => {
                  const rev = Number(item.revenue) || 0;
                  const percent = Math.min(100, Math.round((rev / maxRevenue) * 100));

                  return (
                    <div key={idx} className="space-y-1.5">
                      <div className="flex justify-between items-center text-xs">
                        <span className="font-bold text-slate-700">{item.month}</span>
                        <div className="flex items-center gap-3">
                          <span className="text-slate-500">{item.total_orders} order ({item.total_qty} pcs)</span>
                          <span className="font-black text-slate-900">{formatRupiah(rev)}</span>
                        </div>
                      </div>
                      <div className="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
                        <div
                          className="bg-blue-600 h-full rounded-full transition-all duration-500"
                          style={{ width: `${percent}%` }}
                        />
                      </div>
                    </div>
                  );
                })}
              </div>
            )}
          </div>

          {/* 2 Grid: Stage Distribution & Attendance Recap */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {/* Stage Distribution */}
            <div className="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
              <h3 className="text-base font-black text-slate-800 flex items-center gap-2">
                <BarChart3 className="w-4 h-4 text-indigo-600" />
                Distribusi Tahapan Produksi
              </h3>

              <div className="space-y-3">
                {stages.map((stg, i) => (
                  <div key={i} className="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div>
                      <div className="font-bold text-slate-900 text-xs">{stg.stage || 'Antrian'}</div>
                      <div className="text-[11px] text-slate-500">{stg.qty || 0} pcs apparel</div>
                    </div>
                    <span className="px-3 py-1 rounded-full text-xs font-black bg-blue-100 text-blue-800 border border-blue-200">
                      {stg.count} SPK
                    </span>
                  </div>
                ))}
              </div>
            </div>

            {/* Attendance Recap */}
            <div className="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
              <h3 className="text-base font-black text-slate-800 flex items-center gap-2">
                <Clock className="w-4 h-4 text-emerald-600" />
                Rekapitulasi Kehadiran (30 Hari Terakhir)
              </h3>

              <div className="space-y-3">
                {attendances.length === 0 ? (
                  <div className="p-6 text-center text-xs text-slate-400">Belum ada absensi tercatat.</div>
                ) : (
                  attendances.map((att, i) => (
                    <div key={i} className="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                      <div className="font-bold text-slate-900 text-xs">
                        {att.attendance_status === 'present' ? 'Hadir Tepat Waktu' : att.attendance_status === 'late' ? 'Hadir Terlambat' : att.attendance_status || 'Kehadiran'}
                      </div>
                      <span className="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                        {att.count} Kali
                      </span>
                    </div>
                  ))
                )}
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}

