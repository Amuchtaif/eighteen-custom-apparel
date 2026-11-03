'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import CalendarView from '@/components/CalendarView';
import Modal from '@/components/Modal';
import { Kanban, List, CalendarDays, RefreshCw, Clock, User, Package, MapPin, Eye, FileText } from 'lucide-react';
import { Order } from '@/lib/types';
import { formatDate, formatRupiah, calculateDeadlineInfo } from '@/lib/utils';

export default function OrderCalendarPage() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedOrder, setSelectedOrder] = useState<Order | null>(null);
  const [modalOpen, setModalOpen] = useState(false);

  const fetchOrders = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/orders');
      const data = await res.json();
      if (data.success) {
        setOrders(data.orders || []);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrders();
  }, []);

  const handleOrderClick = (order: Order) => {
    setSelectedOrder(order);
    setModalOpen(true);
  };

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Admin Produksi" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Kalender Deadline Produksi"
          subtitle="Jadwal estimasi selesai pesanan berbasis FullCalendar dengan kode warna prioritas"
        />

        <main className="flex-1 p-8 mt-16 max-w-7xl w-full mx-auto space-y-6">
          {/* View Toggle Bar */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="flex items-center gap-2">
              <Link
                href="/orders"
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200"
              >
                <List className="w-3.5 h-3.5 text-blue-600" />
                <span>Tabel Checklist</span>
              </Link>
              <Link
                href="/orders/kanban"
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200"
              >
                <Kanban className="w-3.5 h-3.5 text-blue-600" />
                <span>Kanban Board</span>
              </Link>
              <span className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-600 text-white text-xs font-bold shadow-sm shadow-blue-500/30">
                <CalendarDays className="w-3.5 h-3.5" />
                <span>Kalender Deadline</span>
              </span>
            </div>

            <button
              onClick={fetchOrders}
              disabled={loading}
              className="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition"
            >
              <RefreshCw className={`w-3.5 h-3.5 ${loading ? 'animate-spin' : ''}`} />
              <span>Muat Ulang</span>
            </button>
          </div>

          {/* Color Legend */}
          <div className="flex flex-wrap items-center gap-4 bg-white px-5 py-3 rounded-2xl border border-slate-200/80 shadow-sm text-xs font-medium text-slate-600">
            <span className="font-bold text-slate-700">Keterangan Warna:</span>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-full bg-[#ef4444]" />
              <span>Terlambat / Overdue</span>
            </div>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-full bg-[#ea580c]" />
              <span>Tahap Pola / H-3</span>
            </div>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-full bg-[#d97706]" />
              <span>Tahap Jahit / H-7</span>
            </div>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-full bg-[#0284c7]" />
              <span>On Progress Normal (&gt;7 Hari)</span>
            </div>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-full bg-[#10b981]" />
              <span>Selesai Produksi</span>
            </div>
          </div>

          {/* Calendar Component */}
          {loading && orders.length === 0 ? (
            <div className="p-12 text-center text-sm text-slate-500 bg-white rounded-2xl border border-slate-200">
              Memuat kalender produksi...
            </div>
          ) : (
            <CalendarView orders={orders} onOrderClick={handleOrderClick} />
          )}

          {/* Detail Order Modal */}
          {selectedOrder && (
            <Modal
              isOpen={modalOpen}
              onClose={() => setModalOpen(false)}
              title={`Detail Deadline: ${selectedOrder.kode_order}`}
              size="lg"
            >
              <div className="space-y-6">
                <div className="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                  <div>
                    <div className="text-base font-black text-slate-900">{selectedOrder.nama_team}</div>
                    <div className="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                      <User className="w-3 h-3" />
                      <span>{selectedOrder.customer_name} ({selectedOrder.customer_phone})</span>
                    </div>
                  </div>
                  <div className="text-right">
                    <span className="text-xs font-bold px-3 py-1 rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                      {selectedOrder.status_produksi}
                    </span>
                    <div className="text-xs text-slate-500 mt-1">
                      Progress: {selectedOrder.progress_percent || 0}%
                    </div>
                  </div>
                </div>

                <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
                  <div className="p-3 bg-white rounded-xl border border-slate-200">
                    <div className="text-[11px] font-bold text-slate-400 uppercase">Jumlah / Qty</div>
                    <div className="text-base font-black text-slate-800 mt-0.5">{selectedOrder.qty_esti} pcs</div>
                  </div>
                  <div className="p-3 bg-white rounded-xl border border-slate-200">
                    <div className="text-[11px] font-bold text-slate-400 uppercase">Paket / Bahan</div>
                    <div className="text-xs font-bold text-slate-800 mt-0.5">{selectedOrder.package_name || '-'} / {selectedOrder.bahan || '-'}</div>
                  </div>
                  <div className="p-3 bg-white rounded-xl border border-slate-200">
                    <div className="text-[11px] font-bold text-slate-400 uppercase">Lokasi Produksi</div>
                    <div className="text-xs font-bold text-slate-800 mt-0.5">{selectedOrder.lokasi_produksi || 'Workshop Utama'}</div>
                  </div>
                  <div className="p-3 bg-white rounded-xl border border-slate-200">
                    <div className="text-[11px] font-bold text-slate-400 uppercase">Tanggal Masuk</div>
                    <div className="text-xs font-bold text-slate-800 mt-0.5">{formatDate(selectedOrder.tanggal_masuk)}</div>
                  </div>
                  <div className="p-3 bg-white rounded-xl border border-slate-200">
                    <div className="text-[11px] font-bold text-slate-400 uppercase">Deadline Target</div>
                    <div className="text-xs font-black text-rose-600 mt-0.5">{formatDate(selectedOrder.deadline)}</div>
                  </div>
                  <div className="p-3 bg-white rounded-xl border border-slate-200">
                    <div className="text-[11px] font-bold text-slate-400 uppercase">Sisa Waktu</div>
                    <div className="text-xs font-bold mt-0.5">
                      {calculateDeadlineInfo(selectedOrder.deadline).label}
                    </div>
                  </div>
                </div>

                {/* Progress Checklist Indicators */}
                <div>
                  <div className="text-xs font-bold text-slate-700 mb-2">Checklist Tahapan Produksi:</div>
                  <div className="grid grid-cols-5 gap-2 text-center text-xs">
                    {[
                      { key: 'print', label: 'Print' },
                      { key: 'press', label: 'Press' },
                      { key: 'pola', label: 'Pola' },
                      { key: 'jahit', label: 'Jahit' },
                      { key: 'finish', label: 'Finish' },
                    ].map((stg) => {
                      const isDone = (selectedOrder as any)[`${stg.key}_status`];
                      return (
                        <div
                          key={stg.key}
                          className={`p-2 rounded-lg font-bold border ${
                            isDone ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-slate-100 text-slate-400 border-slate-200'
                          }`}
                        >
                          {stg.label}: {isDone ? '✓ OK' : 'Antre'}
                        </div>
                      );
                    })}
                  </div>
                </div>

                {/* Action Shortcuts */}
                <div className="flex items-center justify-between pt-4 border-t border-slate-200">
                  <Link
                    href={`/orders/${selectedOrder.id}/notes`}
                    className="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs transition border border-purple-200"
                  >
                    <FileText className="w-3.5 h-3.5" />
                    <span>Buka Lembar Kerja & Spesifikasi (TipTap)</span>
                  </Link>
                  <button
                    onClick={() => setModalOpen(false)}
                    className="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition"
                  >
                    Tutup
                  </button>
                </div>
              </div>
            </Modal>
          )}
        </main>
      </div>
    </div>
  );
}
