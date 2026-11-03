'use client';

import React, { useState, useEffect } from 'react';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import Modal from '@/components/Modal';
import { 
  Users, 
  Plus, 
  Search, 
  Edit3, 
  Trash2, 
  CheckCircle2, 
  AlertCircle, 
  Phone, 
  Mail, 
  Building2, 
  Clock, 
  ShieldCheck, 
  BadgeCheck 
} from 'lucide-react';
import { formatDate } from '@/lib/utils';

export default function EmployeesPage() {
  const [employees, setEmployees] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  // Dropdown options
  const [roles, setRoles] = useState<any[]>([]);
  const [positions, setPositions] = useState<any[]>([]);
  const [departments, setDepartments] = useState<any[]>([]);
  const [offices, setOffices] = useState<any[]>([]);
  const [workShifts, setWorkShifts] = useState<any[]>([]);

  // Modal
  const [modalOpen, setModalOpen] = useState(false);
  const [editingEmp, setEditingEmp] = useState<any | null>(null);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    password: '',
    role_id: '',
    position_id: '',
    department_id: '',
    office_id: '',
    work_shift_id: '',
    status: 'active',
  });

  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' } | null>(null);

  const showToast = (message: string, type: 'success' | 'error' = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  };

  const fetchEmployees = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/employees');
      const data = await res.json();
      if (data.success) {
        setEmployees(data.data || []);
      }
    } catch (err: any) {
      showToast('Gagal memuat data karyawan', 'error');
    } finally {
      setLoading(false);
    }
  };

  const fetchDropdowns = async () => {
    try {
      const [rRes, pRes, dRes, oRes, wRes] = await Promise.all([
        fetch('/api/settings/roles').then((r) => r.json()),
        fetch('/api/settings/positions').then((r) => r.json()),
        fetch('/api/settings/departments').then((r) => r.json()),
        fetch('/api/settings/offices').then((r) => r.json()),
        fetch('/api/settings/work-shifts').then((r) => r.json()),
      ]);

      if (rRes.success) setRoles(rRes.data || []);
      if (pRes.success) setPositions(pRes.data || []);
      if (dRes.success) setDepartments(dRes.data || []);
      if (oRes.success) setOffices(oRes.data || []);
      if (wRes.success) setWorkShifts(wRes.data || []);
    } catch (e) {
      console.error('Failed to load master dropdowns', e);
    }
  };

  useEffect(() => {
    fetchEmployees();
    fetchDropdowns();
  }, []);

  const openCreateModal = () => {
    setEditingEmp(null);
    setFormData({
      name: '',
      email: '',
      phone: '',
      password: '',
      role_id: roles[0]?.id || '',
      position_id: positions[0]?.id || '',
      department_id: departments[0]?.id || '',
      office_id: offices[0]?.id || '',
      work_shift_id: workShifts[0]?.id || '',
      status: 'active',
    });
    setModalOpen(true);
  };

  const openEditModal = (emp: any) => {
    setEditingEmp(emp);
    setFormData({
      name: emp.name || '',
      email: emp.email || '',
      phone: emp.phone || '',
      password: '',
      role_id: emp.role_id || '',
      position_id: emp.position_id || '',
      department_id: emp.department_id || '',
      office_id: emp.office_id || '',
      work_shift_id: emp.work_shift_id || '',
      status: emp.status || 'active',
    });
    setModalOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const url = editingEmp ? `/api/employees/${editingEmp.id}` : '/api/employees';
      const method = editingEmp ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal menyimpan data');
      }

      showToast(data.message, 'success');
      setModalOpen(false);
      fetchEmployees();
    } catch (err: any) {
      showToast(err.message, 'error');
    }
  };

  const handleDelete = async (empId: number, name: string) => {
    if (!confirm(`Nonaktifkan karyawan ${name}?`)) return;

    try {
      const res = await fetch(`/api/employees/${empId}`, { method: 'DELETE' });
      const data = await res.json();
      if (!res.ok || !data.success) throw new Error(data.message || 'Gagal memproses');

      showToast(data.message, 'success');
      fetchEmployees();
    } catch (err: any) {
      showToast(err.message, 'error');
    }
  };

  const filtered = employees.filter((e) => {
    const q = search.toLowerCase();
    return (
      e.name?.toLowerCase().includes(q) ||
      e.employee_code?.toLowerCase().includes(q) ||
      e.email?.toLowerCase().includes(q) ||
      e.position_name?.toLowerCase().includes(q)
    );
  });

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="HRD Manager" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Manajemen Karyawan & SDM"
          subtitle="Data staf operasional, divisi produksi, penugasan shift kerja, dan kantor cabang"
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

          {/* Action Bar */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="relative flex-1 max-w-md">
              <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input
                type="text"
                placeholder="Cari nama, NIP/Kode, email, atau jabatan..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
              />
            </div>

            <button
              onClick={openCreateModal}
              className="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md shadow-blue-500/20"
            >
              <Plus className="w-4 h-4" />
              <span>Tambah Karyawan Baru</span>
            </button>
          </div>

          {/* Table */}
          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs">
                <thead className="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                  <tr>
                    <th className="py-3.5 px-4">Karyawan</th>
                    <th className="py-3.5 px-4">Kontak</th>
                    <th className="py-3.5 px-4">Role & Jabatan</th>
                    <th className="py-3.5 px-4">Divisi & Kantor</th>
                    <th className="py-3.5 px-4">Shift Kerja</th>
                    <th className="py-3.5 px-4">Status</th>
                    <th className="py-3.5 px-4 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium text-slate-700">
                  {loading ? (
                    <tr>
                      <td colSpan={7} className="p-8 text-center text-slate-400">
                        Memuat data karyawan...
                      </td>
                    </tr>
                  ) : filtered.length === 0 ? (
                    <tr>
                      <td colSpan={7} className="p-8 text-center text-slate-400">
                        Tidak ada data karyawan yang ditemukan.
                      </td>
                    </tr>
                  ) : (
                    filtered.map((emp) => (
                      <tr key={emp.id} className="hover:bg-slate-50/80 transition">
                        <td className="py-3.5 px-4">
                          <div className="font-black text-slate-900">{emp.name}</div>
                          <div className="text-[11px] font-mono text-blue-600">{emp.employee_code}</div>
                        </td>
                        <td className="py-3.5 px-4 space-y-0.5">
                          <div className="flex items-center gap-1.5 text-slate-600">
                            <Mail className="w-3 h-3 text-slate-400" />
                            <span>{emp.email}</span>
                          </div>
                          {emp.phone && (
                            <div className="flex items-center gap-1.5 text-slate-500">
                              <Phone className="w-3 h-3 text-slate-400" />
                              <span>{emp.phone}</span>
                            </div>
                          )}
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-[11px] border border-blue-200">
                            <ShieldCheck className="w-3 h-3" />
                            {emp.role_name || 'Staff'}
                          </div>
                          <div className="text-[11px] text-slate-500 mt-1">{emp.position_name || '-'}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-semibold text-slate-800">{emp.department_name || '-'}</div>
                          <div className="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                            <Building2 className="w-3 h-3 text-slate-400" />
                            <span>{emp.office_name || '-'}</span>
                          </div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="flex items-center gap-1 text-slate-700 font-semibold">
                            <Clock className="w-3 h-3 text-slate-400" />
                            <span>{emp.shift_name || '-'}</span>
                          </div>
                        </td>
                        <td className="py-3.5 px-4">
                          <span
                            className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                              emp.status === 'active'
                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                : 'bg-slate-100 text-slate-500 border border-slate-200'
                            }`}
                          >
                            {emp.status === 'active' ? 'Aktif' : 'Nonaktif'}
                          </span>
                        </td>
                        <td className="py-3.5 px-4 text-right">
                          <div className="flex items-center justify-end gap-1.5">
                            <button
                              onClick={() => openEditModal(emp)}
                              className="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                              title="Edit Karyawan"
                            >
                              <Edit3 className="w-4 h-4" />
                            </button>
                            <button
                              onClick={() => handleDelete(emp.id, emp.name)}
                              className="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                              title="Nonaktifkan Karyawan"
                            >
                              <Trash2 className="w-4 h-4" />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Modal Form Create / Edit */}
          <Modal
            isOpen={modalOpen}
            onClose={() => setModalOpen(false)}
            title={editingEmp ? `Edit Karyawan: ${editingEmp.name}` : 'Tambah Karyawan Baru'}
            size="lg"
          >
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                  <input
                    type="text"
                    required
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    placeholder="Nama lengkap staf"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Email Login *</label>
                  <input
                    type="email"
                    required
                    value={formData.email}
                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    placeholder="email@eighteencustom.com"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp / HP</label>
                  <input
                    type="tel"
                    value={formData.phone}
                    onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    placeholder="08123456789"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">
                    {editingEmp ? 'Password (kosongkan jika tidak diubah)' : 'Password Akun *'}
                  </label>
                  <input
                    type="password"
                    required={!editingEmp}
                    value={formData.password}
                    onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    placeholder="Minimal 6 karakter"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Hak Akses / Role *</label>
                  <select
                    required
                    value={formData.role_id}
                    onChange={(e) => setFormData({ ...formData, role_id: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  >
                    <option value="">Pilih Role</option>
                    {roles.map((r) => (
                      <option key={r.id} value={r.id}>
                        {r.name}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Jabatan / Posisi</label>
                  <select
                    value={formData.position_id}
                    onChange={(e) => setFormData({ ...formData, position_id: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  >
                    <option value="">Pilih Posisi</option>
                    {positions.map((p) => (
                      <option key={p.id} value={p.id}>
                        {p.name}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Divisi / Departemen</label>
                  <select
                    value={formData.department_id}
                    onChange={(e) => setFormData({ ...formData, department_id: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  >
                    <option value="">Pilih Departemen</option>
                    {departments.map((d) => (
                      <option key={d.id} value={d.id}>
                        {d.name}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Lokasi Kantor / Workshop</label>
                  <select
                    value={formData.office_id}
                    onChange={(e) => setFormData({ ...formData, office_id: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  >
                    <option value="">Pilih Kantor</option>
                    {offices.map((o) => (
                      <option key={o.id} value={o.id}>
                        {o.name}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Jadwal Shift Kerja</label>
                  <select
                    value={formData.work_shift_id}
                    onChange={(e) => setFormData({ ...formData, work_shift_id: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  >
                    <option value="">Pilih Shift</option>
                    {workShifts.map((w) => (
                      <option key={w.id} value={w.id}>
                        {w.name} ({w.start_time?.slice(0, 5)} - {w.end_time?.slice(0, 5)})
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Status Karyawan</label>
                  <select
                    value={formData.status}
                    onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  >
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                  </select>
                </div>
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
                  className="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-md shadow-blue-500/20"
                >
                  Simpan Karyawan
                </button>
              </div>
            </form>
          </Modal>
        </main>
      </div>
    </div>
  );
}

