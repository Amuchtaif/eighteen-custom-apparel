'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import Modal from '@/components/Modal';
import { 
  ShoppingBag, 
  Filter, 
  Plus, 
  Search, 
  Kanban, 
  CalendarDays, 
  Edit3, 
  Trash2, 
  FileText, 
  Sparkles, 
  MapPin, 
  Clock, 
  CheckCircle2, 
  AlertTriangle,
  RotateCcw,
  Check
} from 'lucide-react';
import { formatRupiah, formatDate } from '@/lib/utils';
import { Order } from '@/lib/types';

export default function OrdersIndexPage() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [summary, setSummary] = useState<any>({});
  const [meta, setMeta] = useState<any>({});
  const [loading, setLoading] = useState(true);

  // Filters state (TASK.md: 7 filters)
  const [search, setSearch] = useState('');
  const [filterTanggalMasuk, setFilterTanggalMasuk] = useState('');
  const [filterDeadline, setFilterDeadline] = useState('');
  const [filterPackage, setFilterPackage] = useState('');
  const [filterStatusProduksi, setFilterStatusProduksi] = useState('');
  const [filterBahan, setFilterBahan] = useState('');
  const [filterCustomer, setFilterCustomer] = useState('');
  const [filterLokasi, setFilterLokasi] = useState('');

  // Modals state
  const [createModalOpen, setCreateModalOpen] = useState(false);
  const [editModalOpen, setEditModalOpen] = useState(false);
  const [selectedOrder, setSelectedOrder] = useState<Order | null>(null);

  // Toast / notification
  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' } | null>(null);

  const showToast = (message: string, type: 'success' | 'error' = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  };

  const fetchOrders = async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (search) params.append('search', search);
      if (filterTanggalMasuk) params.append('tanggal_masuk', filterTanggalMasuk);
      if (filterDeadline) params.append('deadline', filterDeadline);
      if (filterPackage) params.append('package_id', filterPackage);
      if (filterStatusProduksi) params.append('status_produksi', filterStatusProduksi);
      if (filterBahan) params.append('bahan', filterBahan);
      if (filterCustomer) params.append('customer_id', filterCustomer);
      if (filterLokasi) params.append('lokasi_produksi', filterLokasi);

      const res = await fetch(`/api/orders?${params.toString()}`);
      const data = await res.json();
      if (data.success) {
        setOrders(data.orders || []);
        setSummary(data.summary || {});
        setMeta(data.meta || {});
      }
    } catch (err: any) {
      showToast(err.message || 'Gagal memuat pesanan', 'error');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrders();
  }, [filterTanggalMasuk, filterDeadline, filterPackage, filterStatusProduksi, filterBahan, filterCustomer, filterLokasi]);

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    fetchOrders();
  };

  // Toggle stage instant AJAX (Print, Press, Pola, Jahit, Finish)
  const handleToggleStage = async (orderId: number, stage: string, currentStatus: boolean) => {
    try {
      const newStatus = !currentStatus;
      const res = await fetch(`/api/orders/${orderId}/toggle-progress`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ stage, status: newStatus }),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal memperbarui tahapan.');
      }

      showToast(data.message, 'success');
      // Update local state instantly
      setOrders((prev) =>
        prev.map((o) => {
          if (o.id === orderId) {
            return {
              ...o,
              [`${stage}_status`]: newStatus,
              status_produksi: data.order_status,
              progress_percent: data.percentage,
              completed_stages_count: data.completed_count,
            };
          }
          return o;
        })
      );
    } catch (err: any) {
      showToast(err.message || 'Gagal mengubah status', 'error');
    }
  };

  const handleDelete = async (orderId: number, kodeOrder: string) => {
    if (!confirm(`Hapus pesanan ${kodeOrder}? Tindakan ini tidak dapat dibatalkan.`)) return;

    try {
      const res = await fetch(`/api/orders/${orderId}`, { method: 'DELETE' });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Gagal menghapus pesanan');

      showToast(data.message, 'success');
      fetchOrders();
    } catch (err: any) {
      showToast(err.message, 'error');
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Admin Produksi" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Monitoring Pesanan Produksi"
          subtitle="Checklist 5 tahapan produksi terintegrasi: Print, Press, Pola, Jahit, Finish"
        />

        <main className="flex-1 p-8 mt-16 max-w-7xl w-full mx-auto space-y-6">
          {/* Toast Notification */}
          {toast && (
            <div
              className={`fixed top-20 right-8 z-50 p-4 rounded-xl shadow-xl text-xs font-bold flex items-center gap-2 border animate-fadeIn ${
                toast.type === 'success'
                  ? 'bg-emerald-600 text-white border-emerald-500'
                  : 'bg-rose-600 text-white border-rose-500'
              }`}
            >
              {toast.type === 'success' ? <Check className="w-4 h-4" /> : <AlertTriangle className="w-4 h-4" />}
              <span>{toast.message}</span>
            </div>
          )}

          {/* 6 Top Summary KPI Widgets (TASK.md) */}
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            {[
              { label: 'Total Pcs', value: `${summary.total_qty || 0}`, icon: ShoppingBag, color: 'text-blue-600 bg-blue-50 border-blue-200' },
              { label: 'Pesanan Aktif', value: `${summary.total_active || 0}`, icon: Clock, color: 'text-indigo-600 bg-indigo-50 border-indigo-200' },
              { label: 'Sedang Produksi', value: `${summary.sedang_produksi || 0}`, icon: RotateCcw, color: 'text-amber-600 bg-amber-50 border-amber-200' },
              { label: 'Selesai', value: `${summary.selesai || 0}`, icon: CheckCircle2, color: 'text-emerald-600 bg-emerald-50 border-emerald-200' },
              { label: 'Terlambat', value: `${summary.terlambat || 0}`, icon: AlertTriangle, color: 'text-rose-600 bg-rose-50 border-rose-200' },
              { label: 'Paket Premium', value: `${summary.premium || 0}`, icon: Sparkles, color: 'text-purple-600 bg-purple-50 border-purple-200' },
            ].map((s, idx) => {
              const Icon = s.icon;
              return (
                <div key={idx} className="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                  <div className="flex items-center justify-between mb-1.5">
                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-500">{s.label}</span>
                    <div className={`p-1 rounded-md border ${s.color}`}>
                      <Icon className="w-3 h-3" />
                    </div>
                  </div>
                  <span className="text-base font-black text-slate-800">{s.value}</span>
                </div>
              );
            })}
          </div>

          {/* Header Action Bar: Views Switcher & Create Modal Trigger */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="flex items-center gap-2">
              <span className="px-3 py-1.5 rounded-xl bg-blue-600 text-white text-xs font-bold shadow-sm shadow-blue-500/30">
                Tabel Checklist
              </span>
              <Link
                href="/orders/kanban"
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200"
              >
                <Kanban className="w-3.5 h-3.5 text-blue-600" />
                <span>Kanban Board</span>
              </Link>
              <Link
                href="/orders/calendar"
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200"
              >
                <CalendarDays className="w-3.5 h-3.5 text-blue-600" />
                <span>Kalender</span>
              </Link>
            </div>

            <button
              type="button"
              onClick={() => setCreateModalOpen(true)}
              className="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-lg shadow-blue-600/30 transition"
            >
              <Plus className="w-4 h-4" />
              <span>Tambah Pesanan Baru</span>
            </button>
          </div>

          {/* 7 Filter Options Bar (TASK.md) */}
          <div className="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
            <div className="flex items-center justify-between">
              <span className="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                <Filter className="w-3.5 h-3.5 text-blue-600" />
                Filter & Pencarian Pesanan
              </span>
              <button
                type="button"
                onClick={() => {
                  setSearch('');
                  setFilterTanggalMasuk('');
                  setFilterDeadline('');
                  setFilterPackage('');
                  setFilterStatusProduksi('');
                  setFilterBahan('');
                  setFilterCustomer('');
                  setFilterLokasi('');
                  fetchOrders();
                }}
                className="text-[11px] font-bold text-slate-400 hover:text-slate-600"
              >
                Reset Filter
              </button>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-2.5">
              {/* Search */}
              <form onSubmit={handleSearchSubmit} className="lg:col-span-2 relative">
                <input
                  type="text"
                  placeholder="Cari kode, nama tim, customer..."
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:border-blue-500"
                />
                <Search className="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" />
              </form>

              {/* Tanggal Masuk */}
              <input
                type="date"
                title="Filter Tanggal Masuk"
                value={filterTanggalMasuk}
                onChange={(e) => setFilterTanggalMasuk(e.target.value)}
                className="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 focus:outline-none focus:border-blue-500"
              />

              {/* Deadline */}
              <input
                type="date"
                title="Filter Deadline"
                value={filterDeadline}
                onChange={(e) => setFilterDeadline(e.target.value)}
                className="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 focus:outline-none focus:border-blue-500"
              />

              {/* Status Produksi */}
              <select
                value={filterStatusProduksi}
                onChange={(e) => setFilterStatusProduksi(e.target.value)}
                className="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 focus:outline-none focus:border-blue-500"
              >
                <option value="">Semua Status</option>
                {['Antrian', 'Print', 'Press', 'Pola', 'Jahit', 'Finish', 'Siap Kirim', 'Selesai'].map((s) => (
                  <option key={s} value={s}>
                    {s}
                  </option>
                ))}
              </select>

              {/* Bahan */}
              <input
                type="text"
                placeholder="Filter Bahan..."
                value={filterBahan}
                onChange={(e) => setFilterBahan(e.target.value)}
                className="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:border-blue-500"
              />

              {/* Lokasi Produksi */}
              <select
                value={filterLokasi}
                onChange={(e) => setFilterLokasi(e.target.value)}
                className="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 focus:outline-none focus:border-blue-500"
              >
                <option value="">Semua Lokasi</option>
                <option value="Kantor Produksi">Kantor Produksi</option>
                <option value="Studio Desain">Studio Desain</option>
              </select>
            </div>
          </div>

          {/* Orders Table with 5 Stage Checklist (TASK.md) */}
          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs">
                <thead>
                  <tr className="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                    <th className="py-3.5 px-4">Kode & Tim</th>
                    <th className="py-3.5 px-3">Bahan & Paket</th>
                    <th className="py-3.5 px-3 text-center">Jumlah</th>
                    <th className="py-3.5 px-3 text-center">Deadline & Sisa</th>
                    <th className="py-3.5 px-3 text-center">Lokasi</th>
                    {/* 5 Stages Checklists */}
                    <th className="py-3.5 px-2 text-center w-12 bg-blue-50/50 text-blue-700">Print</th>
                    <th className="py-3.5 px-2 text-center w-12 bg-purple-50/50 text-purple-700">Press</th>
                    <th className="py-3.5 px-2 text-center w-12 bg-orange-50/50 text-orange-700">Pola</th>
                    <th className="py-3.5 px-2 text-center w-12 bg-amber-50/50 text-amber-700">Jahit</th>
                    <th className="py-3.5 px-2 text-center w-12 bg-emerald-50/50 text-emerald-700">Finish</th>
                    <th className="py-3.5 px-3 text-center">Status</th>
                    <th className="py-3.5 px-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {orders.map((order) => {
                    const isPremium = order.package_name?.toLowerCase().includes('premium');
                    const badgeClass =
                      order.deadline_tier === 'red'
                        ? 'bg-red-50 text-red-700 border-red-200 animate-pulse'
                        : order.deadline_tier === 'orange'
                        ? 'bg-orange-50 text-orange-700 border-orange-200'
                        : order.deadline_tier === 'yellow'
                        ? 'bg-amber-50 text-amber-700 border-amber-200'
                        : 'bg-blue-50 text-blue-700 border-blue-200';

                    return (
                      <tr key={order.id} className="hover:bg-slate-50/60 transition group">
                        {/* Kode & Tim */}
                        <td className="py-3 px-4">
                          <div className="flex items-center gap-2">
                            <span className="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">
                              {order.kode_order}
                            </span>
                            {isPremium && (
                              <span className="p-1 rounded-full bg-amber-50 border border-amber-200 text-amber-600" title="Paket Premium">
                                <Sparkles className="w-3 h-3 fill-amber-500" />
                              </span>
                            )}
                          </div>
                          <p className="font-extrabold text-slate-800 text-xs mt-1 truncate max-w-[160px]">
                            {order.nama_team}
                          </p>
                          <p className="text-[11px] text-slate-400 truncate">{order.customer_name}</p>
                        </td>

                        {/* Bahan & Paket */}
                        <td className="py-3 px-3">
                          <span className="font-bold text-slate-700">{order.bahan}</span>
                          <p className="text-[11px] text-slate-400">{order.package_name}</p>
                        </td>

                        {/* Jumlah */}
                        <td className="py-3 px-3 text-center">
                          <span className="font-extrabold text-slate-800">{order.qty_esti}</span>
                          <span className="block text-[10px] text-slate-400">pcs</span>
                        </td>

                        {/* Deadline (4-tier color) */}
                        <td className="py-3 px-3 text-center whitespace-nowrap">
                          <p className="font-medium text-slate-600">{formatDate(order.deadline)}</p>
                          <span className={`inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full border mt-0.5 ${badgeClass}`}>
                            <Clock className="w-2.5 h-2.5" />
                            {order.sisa_hari_text}
                          </span>
                        </td>

                        {/* Lokasi Produksi */}
                        <td className="py-3 px-3 text-center whitespace-nowrap">
                          <span className="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                            <MapPin className="w-2.5 h-2.5 text-slate-400" />
                            {order.lokasi_produksi || 'Kantor Produksi'}
                          </span>
                        </td>

                        {/* Checkbox: Print */}
                        <td className="py-3 px-2 text-center bg-blue-50/20">
                          <input
                            type="checkbox"
                            checked={Boolean(order.print_status)}
                            onChange={() => handleToggleStage(order.id, 'print', Boolean(order.print_status))}
                            className="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 cursor-pointer"
                          />
                        </td>

                        {/* Checkbox: Press */}
                        <td className="py-3 px-2 text-center bg-purple-50/20">
                          <input
                            type="checkbox"
                            checked={Boolean(order.press_status)}
                            onChange={() => handleToggleStage(order.id, 'press', Boolean(order.press_status))}
                            className="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-slate-300 cursor-pointer"
                          />
                        </td>

                        {/* Checkbox: Pola */}
                        <td className="py-3 px-2 text-center bg-orange-50/20">
                          <input
                            type="checkbox"
                            checked={Boolean(order.pola_status)}
                            onChange={() => handleToggleStage(order.id, 'pola', Boolean(order.pola_status))}
                            className="w-4 h-4 rounded text-orange-600 focus:ring-orange-500 border-slate-300 cursor-pointer"
                          />
                        </td>

                        {/* Checkbox: Jahit */}
                        <td className="py-3 px-2 text-center bg-amber-50/20">
                          <input
                            type="checkbox"
                            checked={Boolean(order.jahit_status)}
                            onChange={() => handleToggleStage(order.id, 'jahit', Boolean(order.jahit_status))}
                            className="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-slate-300 cursor-pointer"
                          />
                        </td>

                        {/* Checkbox: Finish */}
                        <td className="py-3 px-2 text-center bg-emerald-50/20">
                          <input
                            type="checkbox"
                            checked={Boolean(order.finish_status)}
                            onChange={() => handleToggleStage(order.id, 'finish', Boolean(order.finish_status))}
                            className="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 cursor-pointer"
                          />
                        </td>

                        {/* Status Produksi & Progress Bar */}
                        <td className="py-3 px-3 text-center whitespace-nowrap">
                          <span
                            className={`inline-flex px-2 py-0.5 rounded-full text-[10px] font-extrabold border ${
                              order.status_produksi === 'Selesai'
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : order.status_produksi === 'Antrian'
                                ? 'bg-slate-100 text-slate-700 border-slate-200'
                                : 'bg-blue-50 text-blue-700 border-blue-200'
                            }`}
                          >
                            {order.status_produksi}
                          </span>
                          <div className="w-16 bg-slate-200 rounded-full h-1.5 mx-auto mt-1.5 overflow-hidden">
                            <div
                              className="bg-blue-600 h-1.5 rounded-full transition-all"
                              style={{ width: `${order.progress_percent || 0}%` }}
                            />
                          </div>
                        </td>

                        {/* Aksi: Notes, Edit Modal, Delete */}
                        <td className="py-3 px-4 text-center whitespace-nowrap">
                          <div className="flex items-center justify-center gap-1">
                            <Link
                              href={`/orders/${order.id}/notes`}
                              title="Editor Catatan & Spesifikasi"
                              className="p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-slate-100 transition"
                            >
                              <FileText className="w-4 h-4" />
                            </Link>

                            <button
                              type="button"
                              onClick={() => {
                                setSelectedOrder(order);
                                setEditModalOpen(true);
                              }}
                              title="Edit Data Pesanan"
                              className="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-slate-100 transition"
                            >
                              <Edit3 className="w-4 h-4" />
                            </button>

                            <button
                              type="button"
                              onClick={() => handleDelete(order.id, order.kode_order)}
                              title="Hapus Pesanan"
                              className="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-slate-100 transition"
                            >
                              <Trash2 className="w-4 h-4" />
                            </button>
                          </div>
                        </td>
                      </tr>
                    );
                  })}

                  {orders.length === 0 && !loading && (
                    <tr>
                      <td colSpan={12} className="py-12 text-center text-slate-400">
                        Tidak ada data pesanan yang sesuai filter.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>

      {/* Modal Create Order */}
      <CreateOrderModal
        isOpen={createModalOpen}
        onClose={() => setCreateModalOpen(false)}
        meta={meta}
        onSuccess={() => {
          setCreateModalOpen(false);
          showToast('Pesanan berhasil dibuat.', 'success');
          fetchOrders();
        }}
      />

      {/* Modal Edit Order */}
      {selectedOrder && (
        <EditOrderModal
          isOpen={editModalOpen}
          onClose={() => {
            setEditModalOpen(false);
            setSelectedOrder(null);
          }}
          order={selectedOrder}
          meta={meta}
          onSuccess={() => {
            setEditModalOpen(false);
            setSelectedOrder(null);
            showToast('Pesanan berhasil diperbarui.', 'success');
            fetchOrders();
          }}
        />
      )}
    </div>
  );
}

// Modal Create Form
function CreateOrderModal({
  isOpen,
  onClose,
  meta,
  onSuccess,
}: {
  isOpen: boolean;
  onClose: () => void;
  meta: any;
  onSuccess: () => void;
}) {
  const [formData, setFormData] = useState({
    customer_id: '',
    nama_team: '',
    bahan: 'Dryfit',
    tanggal_masuk: new Date().toISOString().split('T')[0],
    deadline: '',
    package_id: '',
    qty_esti: 12,
    total_tagihan: 1200000,
    dp: 500000,
    lokasi_produksi: 'Kantor Produksi',
    designer_id: '',
    operator_print_id: '',
    penjahit_id: '',
    qc_id: '',
    catatan: '',
  });

  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    if (meta.customers?.[0]) setFormData((p) => ({ ...p, customer_id: String(meta.customers[0].id) }));
    if (meta.packages?.[0]) setFormData((p) => ({ ...p, package_id: String(meta.packages[0].id) }));
  }, [meta]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      const res = await fetch('/api/orders', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Gagal menyimpan.');
      onSuccess();
    } catch (err: any) {
      alert(err.message);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Tambah Pesanan Produksi Baru" maxWidth="3xl">
      <form onSubmit={handleSubmit} className="space-y-4 text-xs">
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label className="block font-bold text-slate-700 mb-1">Pilih Customer *</label>
            <select
              required
              value={formData.customer_id}
              onChange={(e) => setFormData({ ...formData, customer_id: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            >
              <option value="">Pilih Customer</option>
              {(meta.customers || []).map((c: any) => (
                <option key={c.id} value={c.id}>
                  {c.company_name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Nama Tim / Komunitas *</label>
            <input
              type="text"
              required
              placeholder="Contoh: Real Madrid Club"
              value={formData.nama_team}
              onChange={(e) => setFormData({ ...formData, nama_team: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label className="block font-bold text-slate-700 mb-1">Paket Apparel *</label>
            <select
              required
              value={formData.package_id}
              onChange={(e) => setFormData({ ...formData, package_id: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            >
              <option value="">Pilih Paket</option>
              {(meta.packages || []).map((p: any) => (
                <option key={p.id} value={p.id}>
                  {p.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Pilihan Bahan</label>
            <select
              value={formData.bahan}
              onChange={(e) => setFormData({ ...formData, bahan: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            >
              <option value="Dryfit">Dryfit</option>
              <option value="Serena">Serena</option>
              <option value="Cotton Combed">Cotton Combed</option>
            </select>
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Estimasi Qty (Pcs) *</label>
            <input
              type="number"
              min="1"
              required
              value={formData.qty_esti}
              onChange={(e) => setFormData({ ...formData, qty_esti: Number(e.target.value) })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label className="block font-bold text-slate-700 mb-1">Tanggal Masuk *</label>
            <input
              type="date"
              required
              value={formData.tanggal_masuk}
              onChange={(e) => setFormData({ ...formData, tanggal_masuk: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Deadline Selesai *</label>
            <input
              type="date"
              required
              value={formData.deadline}
              onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Lokasi Produksi</label>
            <select
              value={formData.lokasi_produksi}
              onChange={(e) => setFormData({ ...formData, lokasi_produksi: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            >
              <option value="Kantor Produksi">Kantor Produksi</option>
              <option value="Studio Desain">Studio Desain</option>
            </select>
          </div>
        </div>

        {/* Keuangan: Total & DP */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
          <div>
            <label className="block font-bold text-slate-700 mb-1">Total Tagihan (Rp)</label>
            <input
              type="number"
              value={formData.total_tagihan}
              onChange={(e) => setFormData({ ...formData, total_tagihan: Number(e.target.value) })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>
          <div>
            <label className="block font-bold text-slate-700 mb-1">Uang Muka / DP (Rp)</label>
            <input
              type="number"
              value={formData.dp}
              onChange={(e) => setFormData({ ...formData, dp: Number(e.target.value) })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>
        </div>

        {/* Staff Assignment */}
        <div className="pt-2 border-t border-slate-100">
          <p className="font-bold text-slate-700 mb-2">Penugasan Staff Produksi</p>
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div>
              <label className="block text-[11px] text-slate-500 mb-1">Designer</label>
              <select
                value={formData.designer_id}
                onChange={(e) => setFormData({ ...formData, designer_id: e.target.value })}
                className="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-xs"
              >
                <option value="">Pilih Staff</option>
                {(meta.staffUsers || []).map((u: any) => (
                  <option key={u.id} value={u.id}>{u.name}</option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-[11px] text-slate-500 mb-1">Operator Print</label>
              <select
                value={formData.operator_print_id}
                onChange={(e) => setFormData({ ...formData, operator_print_id: e.target.value })}
                className="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-xs"
              >
                <option value="">Pilih Staff</option>
                {(meta.staffUsers || []).map((u: any) => (
                  <option key={u.id} value={u.id}>{u.name}</option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-[11px] text-slate-500 mb-1">Penjahit</label>
              <select
                value={formData.penjahit_id}
                onChange={(e) => setFormData({ ...formData, penjahit_id: e.target.value })}
                className="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-xs"
              >
                <option value="">Pilih Staff</option>
                {(meta.staffUsers || []).map((u: any) => (
                  <option key={u.id} value={u.id}>{u.name}</option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-[11px] text-slate-500 mb-1">Quality Control</label>
              <select
                value={formData.qc_id}
                onChange={(e) => setFormData({ ...formData, qc_id: e.target.value })}
                className="w-full px-2 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-xs"
              >
                <option value="">Pilih Staff</option>
                {(meta.staffUsers || []).map((u: any) => (
                  <option key={u.id} value={u.id}>{u.name}</option>
                ))}
              </select>
            </div>
          </div>
        </div>

        <div className="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
          <button
            type="button"
            onClick={onClose}
            className="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold"
          >
            Batal
          </button>
          <button
            type="submit"
            disabled={submitting}
            className="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold disabled:opacity-50"
          >
            {submitting ? 'Menyimpan...' : 'Simpan Pesanan'}
          </button>
        </div>
      </form>
    </Modal>
  );
}

// Modal Edit Form
function EditOrderModal({
  isOpen,
  onClose,
  order,
  meta,
  onSuccess,
}: {
  isOpen: boolean;
  onClose: () => void;
  order: Order;
  meta: any;
  onSuccess: () => void;
}) {
  const [formData, setFormData] = useState({
    nama_team: order.nama_team,
    bahan: order.bahan,
    deadline: order.deadline ? order.deadline.split('T')[0] : '',
    package_id: String(order.package_id),
    qty_esti: order.qty_esti,
    total_tagihan: order.total_tagihan,
    dp: order.dp,
    lokasi_produksi: order.lokasi_produksi || 'Kantor Produksi',
    designer_id: order.designer_id ? String(order.designer_id) : '',
    operator_print_id: order.operator_print_id ? String(order.operator_print_id) : '',
    penjahit_id: order.penjahit_id ? String(order.penjahit_id) : '',
    qc_id: order.qc_id ? String(order.qc_id) : '',
    catatan: order.catatan || '',
  });

  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      const res = await fetch(`/api/orders/${order.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Gagal memperbarui.');
      onSuccess();
    } catch (err: any) {
      alert(err.message);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title={`Edit Pesanan ${order.kode_order}`} maxWidth="3xl">
      <form onSubmit={handleSubmit} className="space-y-4 text-xs">
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label className="block font-bold text-slate-700 mb-1">Nama Tim / Komunitas *</label>
            <input
              type="text"
              required
              value={formData.nama_team}
              onChange={(e) => setFormData({ ...formData, nama_team: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Paket Apparel *</label>
            <select
              required
              value={formData.package_id}
              onChange={(e) => setFormData({ ...formData, package_id: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            >
              {(meta.packages || []).map((p: any) => (
                <option key={p.id} value={p.id}>{p.name}</option>
              ))}
            </select>
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label className="block font-bold text-slate-700 mb-1">Bahan</label>
            <select
              value={formData.bahan}
              onChange={(e) => setFormData({ ...formData, bahan: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            >
              <option value="Dryfit">Dryfit</option>
              <option value="Serena">Serena</option>
              <option value="Cotton Combed">Cotton Combed</option>
            </select>
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Deadline *</label>
            <input
              type="date"
              required
              value={formData.deadline}
              onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Estimasi Qty (Pcs) *</label>
            <input
              type="number"
              min="1"
              required
              value={formData.qty_esti}
              onChange={(e) => setFormData({ ...formData, qty_esti: Number(e.target.value) })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label className="block font-bold text-slate-700 mb-1">Lokasi Produksi</label>
            <select
              value={formData.lokasi_produksi}
              onChange={(e) => setFormData({ ...formData, lokasi_produksi: e.target.value })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            >
              <option value="Kantor Produksi">Kantor Produksi</option>
              <option value="Studio Desain">Studio Desain</option>
            </select>
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">Total Tagihan (Rp)</label>
            <input
              type="number"
              value={formData.total_tagihan}
              onChange={(e) => setFormData({ ...formData, total_tagihan: Number(e.target.value) })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>

          <div>
            <label className="block font-bold text-slate-700 mb-1">DP (Rp)</label>
            <input
              type="number"
              value={formData.dp}
              onChange={(e) => setFormData({ ...formData, dp: Number(e.target.value) })}
              className="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500"
            />
          </div>
        </div>

        <div className="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
          <button type="button" onClick={onClose} className="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold">
            Batal
          </button>
          <button type="submit" disabled={submitting} className="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold disabled:opacity-50">
            {submitting ? 'Memperbarui...' : 'Simpan Perubahan'}
          </button>
        </div>
      </form>
    </Modal>
  );
}

