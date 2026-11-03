'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import KanbanBoard from '@/components/KanbanBoard';
import { Kanban, List, CalendarDays, RefreshCw, AlertCircle, CheckCircle2 } from 'lucide-react';
import { Order } from '@/lib/types';

export default function KanbanPage() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' } | null>(null);

  const showToast = (message: string, type: 'success' | 'error' = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  };

  const fetchOrders = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/orders');
      const data = await res.json();
      if (data.success) {
        setOrders(data.orders || []);
      }
    } catch (err: any) {
      showToast('Gagal memuat data kanban.', 'error');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrders();
  }, []);

  const handleStageChange = async (orderId: number, newStage: string) => {
    try {
      const res = await fetch('/api/orders/kanban', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: orderId, stage: newStage }),
      });
      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal mengubah stage');
      }
      showToast(data.message || `Pesanan dipindahkan ke ${newStage}`, 'success');
    } catch (err: any) {
      showToast(err.message, 'error');
      fetchOrders(); // rollback
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Admin Produksi" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Kanban Alur Produksi"
          subtitle="Visualisasi drag and drop status pengerjaan pesanan apparel (dnd-kit)"
        />

        <main className="flex-1 p-8 mt-16 max-w-[1600px] w-full mx-auto space-y-6">
          {/* Toast */}
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

          {/* View Toggle Bar */}
          <div className="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="flex items-center gap-2">
              <Link
                href="/orders"
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200"
              >
                <List className="w-3.5 h-3.5 text-blue-600" />
                <span>Tabel Checklist</span>
              </Link>
              <span className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-600 text-white text-xs font-bold shadow-sm shadow-blue-500/30">
                <Kanban className="w-3.5 h-3.5" />
                <span>Kanban Board</span>
              </span>
              <Link
                href="/orders/calendar"
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200"
              >
                <CalendarDays className="w-3.5 h-3.5 text-blue-600" />
                <span>Kalender</span>
              </Link>
            </div>

            <button
              onClick={fetchOrders}
              className="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
              title="Refresh Data"
            >
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
            </button>
          </div>

          {/* Kanban Board Container */}
          {loading ? (
            <div className="p-12 text-center text-xs text-slate-400 bg-white rounded-2xl border border-slate-200">
              Memuat papan Kanban...
            </div>
          ) : (
            <KanbanBoard initialOrders={orders} onStageChange={handleStageChange} />
          )}
        </main>
      </div>
    </div>
  );
}
