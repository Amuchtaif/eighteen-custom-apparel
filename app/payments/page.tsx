'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import Modal from '@/components/Modal';
import { 
  CreditCard, 
  Plus, 
  Search, 
  CheckCircle2, 
  AlertCircle, 
  Receipt, 
  ArrowUpRight, 
  Building, 
  Calendar 
} from 'lucide-react';
import { formatRupiah, formatDate } from '@/lib/utils';

export default function PaymentsPage() {
  const [payments, setPayments] = useState<any[]>([]);
  const [invoices, setInvoices] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  // Modal
  const [modalOpen, setModalOpen] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [formData, setFormData] = useState({
    invoice_id: '',
    amount: '',
    payment_method: 'transfer_bca',
    reference_number: '',
    notes: '',
  });

  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' } | null>(null);

  const showToast = (message: string, type: 'success' | 'error' = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  };

  const fetchPayments = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/payments');
      const data = await res.json();
      if (data.success) {
        setPayments(data.data || []);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  const fetchInvoices = async () => {
    try {
      const res = await fetch('/api/invoices');
      const data = await res.json();
      if (data.success) {
        setInvoices(data.data || []);
      }
    } catch (e) {
      console.error(e);
    }
  };

  useEffect(() => {
    fetchPayments();
    fetchInvoices();
  }, []);

  const handleCreatePayment = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      const res = await fetch('/api/payments', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal menyimpan pembayaran');
      }

      showToast(data.message, 'success');
      setModalOpen(false);
      setFormData({
        invoice_id: '',
        amount: '',
        payment_method: 'transfer_bca',
        reference_number: '',
        notes: '',
      });
      fetchPayments();
      fetchInvoices();
    } catch (err: any) {
      showToast(err.message, 'error');
    } finally {
      setSubmitting(false);
    }
  };

  const filteredPayments = payments.filter((p) => {
    const q = search.toLowerCase();
    return (
      p.invoice_number?.toLowerCase().includes(q) ||
      p.kode_order?.toLowerCase().includes(q) ||
      p.nama_team?.toLowerCase().includes(q) ||
      p.reference_number?.toLowerCase().includes(q)
    );
  });

  const totalCollected = payments.reduce((acc, p) => acc + Number(p.amount || 0), 0);

  const selectedInvoiceObj = invoices.find((i) => i.id.toString() === formData.invoice_id);
  const remainingOnSelectedInvoice = selectedInvoiceObj
    ? Math.max(0, Number(selectedInvoiceObj.total_amount) - Number(selectedInvoiceObj.total_paid))
    : 0;

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Finance Admin" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Pencatatan Pembayaran"
          subtitle="Histori transaksi kas masuk, termin pelunasan DP, dan rekonsiliasi bank"
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

          {/* Quick Stats Banner */}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kas Masuk Diterima</div>
                <div className="text-2xl font-black text-emerald-600 mt-1">{formatRupiah(totalCollected)}</div>
              </div>
              <div className="p-3 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                <CreditCard className="w-6 h-6" />
              </div>
            </div>

            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi Masuk</div>
                <div className="text-2xl font-black text-slate-800 mt-1">{payments.length} Pembayaran</div>
              </div>
              <div className="p-3 rounded-xl bg-blue-50 text-blue-600 border border-blue-200">
                <ArrowUpRight className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Action Bar */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="relative flex-1 max-w-md">
              <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input
                type="text"
                placeholder="Cari no faktur, kode order, nama tim, no referensi..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
              />
            </div>

            <button
              onClick={() => setModalOpen(true)}
              className="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-md shadow-emerald-500/20"
            >
              <Plus className="w-4 h-4" />
              <span>Catat Pembayaran Masuk</span>
            </button>
          </div>

          {/* Table */}
          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs">
                <thead className="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                  <tr>
                    <th className="py-3.5 px-4">Tanggal Masuk</th>
                    <th className="py-3.5 px-4">Faktur & Order</th>
                    <th className="py-3.5 px-4">Klien / Tim</th>
                    <th className="py-3.5 px-4">Metode Bayar</th>
                    <th className="py-3.5 px-4">No. Ref / Bukti</th>
                    <th className="py-3.5 px-4 text-right">Nominal Bayar</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium text-slate-700">
                  {loading ? (
                    <tr>
                      <td colSpan={6} className="p-8 text-center text-slate-400">
                        Memuat data pembayaran...
                      </td>
                    </tr>
                  ) : filteredPayments.length === 0 ? (
                    <tr>
                      <td colSpan={6} className="p-8 text-center text-slate-400">
                        Belum ada transaksi pembayaran yang tercatat.
                      </td>
                    </tr>
                  ) : (
                    filteredPayments.map((p) => (
                      <tr key={p.id} className="hover:bg-slate-50/80 transition">
                        <td className="py-3.5 px-4">
                          <div className="font-bold text-slate-900">{formatDate(p.payment_date)}</div>
                          <div className="text-[11px] text-slate-400">
                            {new Date(p.payment_date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                          </div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-mono font-bold text-blue-700">{p.invoice_number}</div>
                          <div className="text-[11px] text-slate-400 font-mono">{p.kode_order}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-black text-slate-900">{p.nama_team}</div>
                          <div className="text-[11px] text-slate-500">{p.customer_name || 'Pelanggan'}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          <span className="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                            {p.payment_method?.replace('_', ' ') || 'Cash'}
                          </span>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-mono text-slate-700">{p.reference_number || '-'}</div>
                          {p.notes && <div className="text-[11px] text-slate-400">{p.notes}</div>}
                        </td>
                        <td className="py-3.5 px-4 text-right">
                          <div className="font-black text-base text-emerald-600">{formatRupiah(p.amount)}</div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Modal Catat Pembayaran */}
          <Modal
            isOpen={modalOpen}
            onClose={() => setModalOpen(false)}
            title="Catat Pembayaran Masuk"
            size="md"
          >
            <form onSubmit={handleCreatePayment} className="space-y-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Pilih Faktur Invoice *
                </label>
                <select
                  required
                  value={formData.invoice_id}
                  onChange={(e) => setFormData({ ...formData, invoice_id: e.target.value })}
                  className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >
                  <option value="">Pilih Faktur Yang Tersedia</option>
                  {invoices
                    .filter((inv) => inv.status !== 'paid')
                    .map((inv) => (
                      <option key={inv.id} value={inv.id}>
                        {inv.invoice_number} ({inv.nama_team}) - Tagihan: {formatRupiah(inv.total_amount)}
                      </option>
                    ))}
                </select>
              </div>

              {selectedInvoiceObj && (
                <div className="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 space-y-1">
                  <div>Tagihan Total: <strong>{formatRupiah(selectedInvoiceObj.total_amount)}</strong></div>
                  <div>Sudah Dibayar: <strong>{formatRupiah(selectedInvoiceObj.total_paid)}</strong></div>
                  <div>Sisa Tagihan Belum Dibayar: <strong className="text-rose-600">{formatRupiah(remainingOnSelectedInvoice)}</strong></div>
                </div>
              )}

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Nominal Pembayaran Masuk (Rp) *
                </label>
                <input
                  type="number"
                  required
                  min="1"
                  value={formData.amount}
                  onChange={(e) => setFormData({ ...formData, amount: e.target.value })}
                  className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-bold"
                  placeholder="Contoh: 1500000"
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Metode Pembayaran</label>
                  <select
                    value={formData.payment_method}
                    onChange={(e) => setFormData({ ...formData, payment_method: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  >
                    <option value="transfer_bca">Transfer BCA</option>
                    <option value="transfer_mandiri">Transfer Mandiri</option>
                    <option value="transfer_bri">Transfer BRI</option>
                    <option value="qris">QRIS</option>
                    <option value="cash">Tunai / Cash</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">No. Referensi Bank / Resi</label>
                  <input
                    type="text"
                    value={formData.reference_number}
                    onChange={(e) => setFormData({ ...formData, reference_number: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-mono"
                    placeholder="Contoh: BCA-9823101"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan</label>
                <input
                  type="text"
                  value={formData.notes}
                  onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                  className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  placeholder="Catatan pelunasan, diskon atau keterangan khusus..."
                />
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
                  className="px-5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-md shadow-emerald-500/20 disabled:opacity-50"
                >
                  {submitting ? 'Menyimpan...' : 'Simpan Pembayaran'}
                </button>
              </div>
            </form>
          </Modal>
        </main>
      </div>
    </div>
  );
}
