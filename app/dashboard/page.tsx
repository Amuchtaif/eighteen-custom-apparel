'use client';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import { 
  ShoppingBag, 
  DollarSign, 
  CheckCircle2, 
  AlertTriangle, 
  Clock, 
  ArrowRight,
  TrendingUp,
  Users,
  Sparkles
} from 'lucide-react';
import { formatRupiah, formatDate } from '@/lib/utils';

export default function ExecutiveDashboard() {
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/dashboard/summary')
      .then((res) => res.json())
      .then((resData) => {
        if (resData.success) {
          setData(resData);
        }
      })
      .catch((e) => console.error(e))
      .finally(() => setLoading(false));
  }, []);

  const stats = data?.stats || {};
  const recentOrders = data?.recent_orders || [];
  const stagesBreakdown = data?.stages_breakdown || [];

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Executive" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar title="Executive Dashboard" subtitle="Ringkasan performa bisnis & operasional Eighteen" userRole="Super Admin" />

        <main className="flex-1 p-8 mt-16 max-w-7xl w-full mx-auto space-y-8">
          {/* 6 Top KPI Metrics */}
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            {[
              {
                label: 'Total Omset',
                value: formatRupiah(stats.total_revenue || 0),
                icon: DollarSign,
                color: 'text-emerald-600 bg-emerald-50 border-emerald-200',
              },
              {
                label: 'Total Pesanan',
                value: `${stats.total_orders || 0}`,
                icon: ShoppingBag,
                color: 'text-blue-600 bg-blue-50 border-blue-200',
              },
              {
                label: 'Pesanan Aktif',
                value: `${stats.active_orders || 0}`,
                icon: Clock,
                color: 'text-indigo-600 bg-indigo-50 border-indigo-200',
              },
              {
                label: 'Pesanan Selesai',
                value: `${stats.completed_orders || 0}`,
                icon: CheckCircle2,
                color: 'text-teal-600 bg-teal-50 border-teal-200',
              },
              {
                label: 'Pesanan Terlambat',
                value: `${stats.delayed_orders || 0}`,
                icon: AlertTriangle,
                color: 'text-rose-600 bg-rose-50 border-rose-200',
              },
              {
                label: 'Absensi Hari Ini',
                value: `${stats.today_attendance || 0} Staff`,
                icon: Users,
                color: 'text-amber-600 bg-amber-50 border-amber-200',
              },
            ].map((kpi, idx) => {
              const Icon = kpi.icon;
              return (
                <div key={idx} className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                  <div className="flex items-center justify-between mb-2">
                    <span className="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{kpi.label}</span>
                    <div className={`p-1.5 rounded-lg border ${kpi.color}`}>
                      <Icon className="w-3.5 h-3.5" />
                    </div>
                  </div>
                  <span className="text-lg font-black text-slate-800 tracking-tight">{kpi.value}</span>
                </div>
              );
            })}
          </div>

          {/* Quick Shortcuts */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Link
              href="/orders"
              className="p-5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/20 flex items-center justify-between group hover:opacity-95 transition"
            >
              <div>
                <h3 className="text-sm font-black">Monitoring Pesanan Produksi</h3>
                <p className="text-xs text-blue-100 mt-1">Cek checklist 5 tahapan & deadline</p>
              </div>
              <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
            </Link>

            <Link
              href="/orders/kanban"
              className="p-5 rounded-2xl bg-slate-900 text-white shadow-lg shadow-slate-900/10 flex items-center justify-between group hover:opacity-95 transition border border-slate-800"
            >
              <div>
                <h3 className="text-sm font-black">Kanban Board Produksi</h3>
                <p className="text-xs text-slate-400 mt-1">Drag and drop pesanan antar stage</p>
              </div>
              <ArrowRight className="w-5 h-5 text-blue-400 group-hover:translate-x-1 transition-transform" />
            </Link>

            <Link
              href="/orders/calendar"
              className="p-5 rounded-2xl bg-white text-slate-800 shadow-sm border border-slate-200/80 flex items-center justify-between group hover:border-blue-400 transition"
            >
              <div>
                <h3 className="text-sm font-black">Kalender Deadline</h3>
                <p className="text-xs text-slate-500 mt-1">Jadwal pengiriman & timeline tim</p>
              </div>
              <ArrowRight className="w-5 h-5 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-1 transition" />
            </Link>
          </div>

          {/* Stage Breakdown & Recent Orders Table */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* Stages Distribution */}
            <div className="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
              <h3 className="text-sm font-black text-slate-800 mb-4">Distribusi Alur Produksi</h3>
              <div className="space-y-3">
                {['Antrian', 'Print', 'Press', 'Pola', 'Jahit', 'Finish', 'Siap Kirim', 'Selesai'].map((stage) => {
                  const item = stagesBreakdown.find((s: any) => s.stage === stage);
                  const count = item ? Number(item.count) : 0;
                  const total = stats.total_orders || 1;
                  const pct = Math.round((count / total) * 100);

                  return (
                    <div key={stage}>
                      <div className="flex justify-between text-xs font-semibold mb-1">
                        <span className="text-slate-700">{stage}</span>
                        <span className="text-slate-500 font-mono">{count} order ({pct}%)</span>
                      </div>
                      <div className="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div
                          className="bg-blue-600 h-2 rounded-full transition-all duration-500"
                          style={{ width: `${pct}%` }}
                        />
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>

            {/* Recent Orders */}
            <div className="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-sm font-black text-slate-800">Pesanan Masuk Terbaru</h3>
                <Link href="/orders" className="text-xs font-bold text-blue-600 hover:underline">
                  Lihat Semua →
                </Link>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left text-xs">
                  <thead>
                    <tr className="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                      <th className="pb-3">Kode Order</th>
                      <th className="pb-3">Tim / Customer</th>
                      <th className="pb-3 text-center">Bahan / Qty</th>
                      <th className="pb-3 text-center">Deadline</th>
                      <th className="pb-3 text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {recentOrders.map((order: any) => (
                      <tr key={order.id} className="hover:bg-slate-50 transition">
                        <td className="py-3 font-mono font-bold text-slate-800">{order.kode_order}</td>
                        <td className="py-3">
                          <p className="font-bold text-slate-800 truncate">{order.nama_team}</p>
                          <p className="text-[11px] text-slate-400">{order.customer_name}</p>
                        </td>
                        <td className="py-3 text-center">
                          <span className="font-medium text-slate-700">{order.bahan}</span>
                          <span className="block font-bold text-blue-600">{order.qty_esti} pcs</span>
                        </td>
                        <td className="py-3 text-center font-medium text-slate-600">
                          {formatDate(order.deadline)}
                        </td>
                        <td className="py-3 text-center">
                          <span className="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {order.status_produksi}
                          </span>
                        </td>
                      </tr>
                    ))}
                    {recentOrders.length === 0 && (
                      <tr>
                        <td colSpan={5} className="py-8 text-center text-slate-400">
                          Belum ada data pesanan.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}

