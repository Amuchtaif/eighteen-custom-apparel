'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import Modal from '@/components/Modal';
import { 
  Receipt, 
  Plus, 
  Search, 
  CreditCard, 
  CheckCircle2, 
  AlertCircle, 
  FileText, 
  Calendar, 
  DollarSign, 
  Printer 
} from 'lucide-react';
import { formatRupiah, formatDate } from '@/lib/utils';
import { Order } from '@/lib/types';

export default function InvoicesPage() {
  const [invoices, setInvoices] = useState<any[]>([]);
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState('');

  // Modal create
  const [modalOpen, setModalOpen] = useState(false);
  const [selectedOrderId, setSelectedOrderId] = useState('');
  const [submitting, setSubmitting] = useState(false);

  // Selected invoice for detail/print
  const [detailModalOpen, setDetailModalOpen] = useState(false);
  const [selectedInvoice, setSelectedInvoice] = useState<any | null>(null);

  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' } | null>(null);

  const showToast = (message: string, type: 'success' | 'error' = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  };

  const fetchInvoices = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/invoices');
      const data = await res.json();
      if (data.success) {
        setInvoices(data.data || []);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  const fetchOrders = async () => {
    try {
      const res = await fetch('/api/orders');
      const data = await res.json();
      if (data.success) {
        setOrders(data.orders || []);
      }
    } catch (e) {
      console.error(e);
    }
  };

  useEffect(() => {
    fetchInvoices();
    fetchOrders();
  }, []);

  const handleCreateInvoice = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedOrderId) return;

    setSubmitting(true);
    try {
      const res = await fetch('/api/invoices', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: Number(selectedOrderId) }),
      });
      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal membuat invoice');
      }

      showToast(data.message, 'success');
      setModalOpen(false);
      setSelectedOrderId('');
      fetchInvoices();
    } catch (err: any) {
      showToast(err.message, 'error');
    } finally {
      setSubmitting(false);
    }
  };

  const filteredInvoices = invoices.filter((inv) => {
    const q = search.toLowerCase();
    const matchSearch =
      inv.invoice_number?.toLowerCase().includes(q) ||
      inv.kode_order?.toLowerCase().includes(q) ||
      inv.nama_team?.toLowerCase().includes(q) ||
      inv.customer_name?.toLowerCase().includes(q);

    const matchStatus = !statusFilter || inv.status === statusFilter;
    return matchSearch && matchStatus;
  });

  const totalBilled = invoices.reduce((acc, i) => acc + Number(i.total_amount || 0), 0);
  const totalPaid = invoices.reduce((acc, i) => acc + Number(i.total_paid || 0), 0);
  const totalRemaining = Math.max(0, totalBilled - totalPaid);

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Finance Admin" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Faktur & Invoice Pesanan"
          subtitle="Penerbitan tagihan resmi, monitoring termin pembayaran, dan sisa piutang produksi"
        />

        <main className="flex-1 p-8 mt-16 max-w-7xl w-full mx-auto space-y-6">
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

          {/* 3 Finance KPI Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Nilai Tagihan</div>
                <div className="text-xl font-black text-slate-900 mt-1">{formatRupiah(totalBilled)}</div>
              </div>
              <div className="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-200">
                <Receipt className="w-5 h-5" />
              </div>
            </div>

            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Sudah Terbayar</div>
                <div className="text-xl font-black text-emerald-600 mt-1">{formatRupiah(totalPaid)}</div>
              </div>
              <div className="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                <CreditCard className="w-5 h-5" />
              </div>
            </div>

            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Sisa Piutang Berjalan</div>
                <div className="text-xl font-black text-amber-600 mt-1">{formatRupiah(totalRemaining)}</div>
              </div>
              <div className="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-200">
                <DollarSign className="w-5 h-5" />
              </div>
            </div>
          </div>

          {/* Action Bar */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="flex items-center gap-3 flex-1 max-w-lg">
              <div className="relative flex-1">
                <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input
                  type="text"
                  placeholder="Cari no. faktur, kode order, atau nama tim..."
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                />
              </div>

              <select
                value={statusFilter}
                onChange={(e) => setStatusFilter(e.target.value)}
                className="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
              >
                <option value="">Semua Status</option>
                <option value="unpaid">Belum Dibayar</option>
                <option value="partially_paid">Bayar Sebagian (DP)</option>
                <option value="paid">Lunas</option>
              </select>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={() => setModalOpen(true)}
                className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md shadow-blue-500/20"
              >
                <Plus className="w-4 h-4" />
                <span>Terbitkan Faktur Baru</span>
              </button>
            </div>
          </div>

          {/* Invoices Table */}
          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs">
                <thead className="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                  <tr>
                    <th className="py-3.5 px-4">No. Faktur</th>
                    <th className="py-3.5 px-4">Order / Klien</th>
                    <th className="py-3.5 px-4">Tgl & Jatuh Tempo</th>
                    <th className="py-3.5 px-4">Total Tagihan</th>
                    <th className="py-3.5 px-4">Terbayar</th>
                    <th className="py-3.5 px-4">Status</th>
                    <th className="py-3.5 px-4 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium text-slate-700">
                  {loading ? (
                    <tr>
                      <td colSpan={7} className="p-8 text-center text-slate-400">
                        Memuat data faktur...
                      </td>
                    </tr>
                  ) : filteredInvoices.length === 0 ? (
                    <tr>
                      <td colSpan={7} className="p-8 text-center text-slate-400">
                        Tidak ada faktur yang ditemukan.
                      </td>
                    </tr>
                  ) : (
                    filteredInvoices.map((inv) => (
                      <tr key={inv.id} className="hover:bg-slate-50/80 transition">
                        <td className="py-3.5 px-4">
                          <div className="font-mono font-bold text-blue-700">{inv.invoice_number}</div>
                          <div className="text-[11px] text-slate-400 font-mono mt-0.5">{inv.kode_order}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-black text-slate-900">{inv.nama_team}</div>
                          <div className="text-[11px] text-slate-500">{inv.customer_name || 'Pelanggan'}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="text-slate-700 font-semibold">{formatDate(inv.invoice_date)}</div>
                          <div className="text-[11px] text-rose-500 font-semibold mt-0.5">
                            Tempo: {formatDate(inv.due_date)}
                          </div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-bold text-slate-900">{formatRupiah(inv.total_amount)}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-bold text-emerald-600">{formatRupiah(inv.total_paid)}</div>
                          {Number(inv.total_amount) > Number(inv.total_paid) && (
                            <div className="text-[10px] text-amber-600 font-semibold">
                              Sisa: {formatRupiah(Number(inv.total_amount) - Number(inv.total_paid))}
                            </div>
                          )}
                        </td>
                        <td className="py-3.5 px-4">
                          <span
                            className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                              inv.status === 'paid'
                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                : inv.status === 'partially_paid'
                                ? 'bg-amber-50 text-amber-700 border border-amber-200'
                                : 'bg-rose-50 text-rose-700 border border-rose-200'
                            }`}
                          >
                            {inv.status === 'paid' ? 'Lunas' : inv.status === 'partially_paid' ? 'DP Masuk' : 'Belum Bayar'}
                          </span>
                        </td>
                        <td className="py-3.5 px-4 text-right">
                          <div className="flex items-center justify-end gap-1.5">
                            <button
                              onClick={() => {
                                setSelectedInvoice(inv);
                                setDetailModalOpen(true);
                              }}
                              className="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition"
                            >
                              Lihat Faktur
                            </button>
                            <Link
                              href="/payments"
                              className="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition border border-emerald-200"
                            >
                              Catat Bayar
                            </Link>
                          </div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Modal Terbitkan Faktur */}
          <Modal
            isOpen={modalOpen}
            onClose={() => setModalOpen(false)}
            title="Terbitkan Faktur Invoice Baru"
            size="md"
          >
            <form onSubmit={handleCreateInvoice} className="space-y-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Pilih Pesanan Produksi *
                </label>
                <select
                  required
                  value={selectedOrderId}
                  onChange={(e) => setSelectedOrderId(e.target.value)}
                  className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
                  <option value="">Pilih Order Yang Tersedia</option>
                  {orders.map((ord) => (
                    <option key={ord.id} value={ord.id}>
                      {ord.kode_order} - {ord.nama_team} ({ord.qty_esti} pcs) - {formatRupiah(ord.total_tagihan)}
                    </option>
                  ))}
                </select>
                <p className="text-[11px] text-slate-400 mt-1">
                  Sistem akan membuat nomor faktur resmi berformat INV-YYYYMM-XXXX otomatis.
                </p>
              </div>

              <div className="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalOpen(false)}
                  className="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  disabled={submitting}
                  className="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-md shadow-blue-500/20 disabled:opacity-50"
                >
                  {submitting ? 'Memproses...' : 'Terbitkan Sekarang'}
                </button>
              </div>
            </form>
          </Modal>

          {/* Modal Detail & Cetak Faktur */}
          {selectedInvoice && (
            <Modal
              isOpen={detailModalOpen}
              onClose={() => setDetailModalOpen(false)}
              title={`Faktur Tagihan: ${selectedInvoice.invoice_number}`}
              size="lg"
            >
              <div className="space-y-6 print:p-0">
                <div className="p-6 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                  <div className="flex justify-between items-start">
                    <div>
                      <div className="text-xl font-black text-slate-900">EIGHTEEN CUSTOM APPAREL</div>
                      <div className="text-xs text-slate-500 mt-0.5">Konveksi Jersey & Apparel Printing Sublim</div>
                    </div>
                    <div className="text-right">
                      <div className="text-xs font-bold uppercase text-slate-400">FAKTUR TAGIHAN</div>
                      <div className="text-sm font-mono font-black text-blue-700">{selectedInvoice.invoice_number}</div>
                    </div>
                  </div>

                  <div className="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 text-xs">
                    <div>
                      <div className="font-bold text-slate-400 uppercase text-[10px]">Ditujukan Kepada:</div>
                      <div className="font-black text-slate-900 text-sm mt-0.5">{selectedInvoice.nama_team}</div>
                      <div className="text-slate-600">{selectedInvoice.customer_name}</div>
                      <div className="text-slate-500 font-mono text-[11px] mt-1">Ref Order: {selectedInvoice.kode_order}</div>
                    </div>
                    <div className="text-right space-y-1">
                      <div>
                        <span className="text-slate-400">Tanggal Terbit: </span>
                        <span className="font-bold text-slate-800">{formatDate(selectedInvoice.invoice_date)}</span>
                      </div>
                      <div>
                        <span className="text-slate-400">Jatuh Tempo: </span>
                        <span className="font-bold text-rose-600">{formatDate(selectedInvoice.due_date)}</span>
                      </div>
                    </div>
                  </div>

                  <div className="border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <table className="w-full text-xs">
                      <thead className="bg-slate-100 font-bold text-slate-700 border-b border-slate-200">
                        <tr>
                          <th className="py-2.5 px-3 text-left">Deskripsi Tagihan</th>
                          <th className="py-2.5 px-3 text-right">Jumlah</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100 font-medium">
                        <tr>
                          <td className="py-3 px-3">
                            <div className="font-bold text-slate-900">Pesanan Produksi Apparel - {selectedInvoice.nama_team}</div>
                            <div className="text-[11px] text-slate-500">Kode Produksi: {selectedInvoice.kode_order}</div>
                          </td>
                          <td className="py-3 px-3 text-right font-bold text-slate-900">
                            {formatRupiah(selectedInvoice.total_amount)}
                          </td>
                        </tr>
                        <tr>
                          <td className="py-2 px-3 font-semibold text-slate-600">Total Pembayaran Masuk</td>
                          <td className="py-2 px-3 text-right font-bold text-emerald-600">
                            - {formatRupiah(selectedInvoice.total_paid)}
                          </td>
                        </tr>
                        <tr className="bg-slate-50 font-black text-sm">
                          <td className="py-3 px-3 text-slate-900">SISA YANG HARUS DIBAYAR</td>
                          <td className="py-3 px-3 text-right text-rose-600">
                            {formatRupiah(Math.max(0, Number(selectedInvoice.total_amount) - Number(selectedInvoice.total_paid)))}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div className="p-3 bg-blue-50/70 border border-blue-200/80 rounded-xl text-xs text-blue-900 space-y-1">
                    <div className="font-bold">Informasi Pembayaran Bank:</div>
                    <div>Bank BCA: <strong>1234-567-890</strong> a/n Eighteen Custom Apparel</div>
                    <div>Bank Mandiri: <strong>987-00-1234567-8</strong> a/n Eighteen Custom Apparel</div>
                  </div>
                </div>

                <div className="flex items-center justify-between pt-2">
                  <button
                    onClick={() => window.print()}
                    className="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition"
                  >
                    <Printer className="w-4 h-4" />
                    <span>Cetak Faktur PDF</span>
                  </button>
                  <button
                    onClick={() => setDetailModalOpen(false)}
                    className="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition"
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
