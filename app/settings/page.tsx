'use client';

import React, { useState, useEffect } from 'react';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import Modal from '@/components/Modal';
import { 
  Settings, 
  ShieldCheck, 
  Briefcase, 
  Building2, 
  MapPin, 
  Clock, 
  Plus, 
  Edit3, 
  Trash2, 
  CheckCircle2, 
  AlertCircle, 
  Check 
} from 'lucide-react';

type TabType = 'roles' | 'positions' | 'departments' | 'offices' | 'shifts';

export default function SettingsPage() {
  const [activeTab, setActiveTab] = useState<TabType>('roles');
  const [loading, setLoading] = useState(true);

  // Data lists
  const [roles, setRoles] = useState<any[]>([]);
  const [positions, setPositions] = useState<any[]>([]);
  const [departments, setDepartments] = useState<any[]>([]);
  const [offices, setOffices] = useState<any[]>([]);
  const [shifts, setShifts] = useState<any[]>([]);

  // Modal State
  const [modalOpen, setModalOpen] = useState(false);
  const [editingItem, setEditingItem] = useState<any | null>(null);

  // Generic form state
  const [formData, setFormData] = useState<any>({});

  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' } | null>(null);

  const showToast = (message: string, type: 'success' | 'error' = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  };

  const loadData = async () => {
    setLoading(true);
    try {
      const [r, p, d, o, s] = await Promise.all([
        fetch('/api/settings/roles').then((res) => res.json()),
        fetch('/api/settings/positions').then((res) => res.json()),
        fetch('/api/settings/departments').then((res) => res.json()),
        fetch('/api/settings/offices').then((res) => res.json()),
        fetch('/api/settings/work-shifts').then((res) => res.json()),
      ]);

      if (r.success) setRoles(r.data || []);
      if (p.success) setPositions(p.data || []);
      if (d.success) setDepartments(d.data || []);
      if (o.success) setOffices(o.data || []);
      if (s.success) setShifts(s.data || []);
    } catch (err: any) {
      showToast('Gagal memuat pengaturan data', 'error');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const openCreateModal = () => {
    setEditingItem(null);
    if (activeTab === 'roles') {
      setFormData({ name: '', description: '', is_active: true });
    } else if (activeTab === 'positions') {
      setFormData({ name: '', department_id: departments[0]?.id || '', is_active: true });
    } else if (activeTab === 'departments') {
      setFormData({ name: '', description: '', is_active: true });
    } else if (activeTab === 'offices') {
      setFormData({
        name: '',
        address: '',
        latitude: -6.2,
        longitude: 106.816666,
        radius_meter: 100,
        is_active: true,
      });
    } else if (activeTab === 'shifts') {
      setFormData({
        name: '',
        start_time: '08:00',
        end_time: '17:00',
        late_tolerance_minutes: 15,
        is_active: true,
      });
    }
    setModalOpen(true);
  };

  const openEditModal = (item: any) => {
    setEditingItem(item);
    setFormData({ ...item });
    setModalOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      let endpoint = '';
      if (activeTab === 'roles') endpoint = '/api/settings/roles';
      else if (activeTab === 'positions') endpoint = '/api/settings/positions';
      else if (activeTab === 'departments') endpoint = '/api/settings/departments';
      else if (activeTab === 'offices') endpoint = '/api/settings/offices';
      else if (activeTab === 'shifts') endpoint = '/api/settings/work-shifts';

      const method = editingItem ? 'PUT' : 'POST';
      const body = editingItem ? { ...formData, id: editingItem.id } : formData;

      const res = await fetch(endpoint, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal menyimpan perubahan');
      }

      showToast(data.message || 'Berhasil disimpan', 'success');
      setModalOpen(false);
      loadData();
    } catch (err: any) {
      showToast(err.message, 'error');
    }
  };

  const handleDelete = async (id: number, name: string) => {
    if (!confirm(`Hapus data ${name}?`)) return;

    try {
      let endpoint = '';
      if (activeTab === 'roles') endpoint = `/api/settings/roles?id=${id}`;
      else if (activeTab === 'positions') endpoint = `/api/settings/positions?id=${id}`;
      else if (activeTab === 'departments') endpoint = `/api/settings/departments?id=${id}`;
      else if (activeTab === 'offices') endpoint = `/api/settings/offices?id=${id}`;
      else if (activeTab === 'shifts') endpoint = `/api/settings/work-shifts?id=${id}`;

      const res = await fetch(endpoint, { method: 'DELETE' });
      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal menghapus');
      }

      showToast(data.message || 'Berhasil dihapus', 'success');
      loadData();
    } catch (err: any) {
      showToast(err.message, 'error');
    }
  };

  const tabs: { key: TabType; label: string; icon: any }[] = [
    { key: 'roles', label: 'Hak Akses / Roles', icon: ShieldCheck },
    { key: 'positions', label: 'Jabatan / Posisi', icon: Briefcase },
    { key: 'departments', label: 'Departemen / Divisi', icon: Building2 },
    { key: 'offices', label: 'Kantor & Radius GPS', icon: MapPin },
    { key: 'shifts', label: 'Jadwal Shift Kerja', icon: Clock },
  ];

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="Super Admin" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Master Data & Pengaturan Sistem"
          subtitle="Konfigurasi role RBAC, master jabatan, divisi produksi, geolokasi kantor, dan shift absensi"
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

          {/* Tab Navigation */}
          <div className="flex items-center gap-2 overflow-x-auto pb-1 border-b border-slate-200">
            {tabs.map((tab) => {
              const Icon = tab.icon;
              const isActive = activeTab === tab.key;
              return (
                <button
                  key={tab.key}
                  onClick={() => setActiveTab(tab.key)}
                  className={`flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap ${
                    isActive
                      ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30'
                      : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
                  }`}
                >
                  <Icon className="w-4 h-4" />
                  <span>{tab.label}</span>
                </button>
              );
            })}
          </div>

          {/* Action Header for Active Tab */}
          <div className="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
              <h3 className="text-sm font-black text-slate-800">
                Kelola Data {tabs.find((t) => t.key === activeTab)?.label}
              </h3>
              <p className="text-xs text-slate-500 mt-0.5">
                Konfigurasi entitas master untuk relasi pegawai, hak akses, dan operasional workshop.
              </p>
            </div>

            <button
              onClick={openCreateModal}
              className="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md shadow-blue-500/20"
            >
              <Plus className="w-4 h-4" />
              <span>Tambah Data</span>
            </button>
          </div>

          {/* Content Area */}
          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            {loading ? (
              <div className="p-12 text-center text-xs text-slate-400">Memuat data master...</div>
            ) : (
              <div className="overflow-x-auto">
                {activeTab === 'roles' && (
                  <table className="w-full text-left text-xs">
                    <thead className="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px]">
                      <tr>
                        <th className="py-3 px-4">Nama Role</th>
                        <th className="py-3 px-4">Deskripsi</th>
                        <th className="py-3 px-4">Status</th>
                        <th className="py-3 px-4 text-right">Aksi</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 font-medium">
                      {roles.map((r) => (
                        <tr key={r.id} className="hover:bg-slate-50 transition">
                          <td className="py-3 px-4 font-bold text-slate-900">{r.name}</td>
                          <td className="py-3 px-4 text-slate-500">{r.description || '-'}</td>
                          <td className="py-3 px-4">
                            <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                              Aktif
                            </span>
                          </td>
                          <td className="py-3 px-4 text-right">
                            <div className="flex items-center justify-end gap-1.5">
                              <button onClick={() => openEditModal(r)} className="p-1.5 text-slate-400 hover:text-blue-600">
                                <Edit3 className="w-3.5 h-3.5" />
                              </button>
                              <button onClick={() => handleDelete(r.id, r.name)} className="p-1.5 text-slate-400 hover:text-rose-600">
                                <Trash2 className="w-3.5 h-3.5" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                )}

                {activeTab === 'positions' && (
                  <table className="w-full text-left text-xs">
                    <thead className="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px]">
                      <tr>
                        <th className="py-3 px-4">Nama Jabatan</th>
                        <th className="py-3 px-4">Divisi / Departemen</th>
                        <th className="py-3 px-4 text-right">Aksi</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 font-medium">
                      {positions.map((p) => (
                        <tr key={p.id} className="hover:bg-slate-50 transition">
                          <td className="py-3 px-4 font-bold text-slate-900">{p.name}</td>
                          <td className="py-3 px-4 text-slate-600">{p.department_name || '-'}</td>
                          <td className="py-3 px-4 text-right">
                            <div className="flex items-center justify-end gap-1.5">
                              <button onClick={() => openEditModal(p)} className="p-1.5 text-slate-400 hover:text-blue-600">
                                <Edit3 className="w-3.5 h-3.5" />
                              </button>
                              <button onClick={() => handleDelete(p.id, p.name)} className="p-1.5 text-slate-400 hover:text-rose-600">
                                <Trash2 className="w-3.5 h-3.5" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                )}

                {activeTab === 'departments' && (
                  <table className="w-full text-left text-xs">
                    <thead className="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px]">
                      <tr>
                        <th className="py-3 px-4">Nama Departemen</th>
                        <th className="py-3 px-4">Keterangan</th>
                        <th className="py-3 px-4 text-right">Aksi</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 font-medium">
                      {departments.map((d) => (
                        <tr key={d.id} className="hover:bg-slate-50 transition">
                          <td className="py-3 px-4 font-bold text-slate-900">{d.name}</td>
                          <td className="py-3 px-4 text-slate-500">{d.description || '-'}</td>
                          <td className="py-3 px-4 text-right">
                            <div className="flex items-center justify-end gap-1.5">
                              <button onClick={() => openEditModal(d)} className="p-1.5 text-slate-400 hover:text-blue-600">
                                <Edit3 className="w-3.5 h-3.5" />
                              </button>
                              <button onClick={() => handleDelete(d.id, d.name)} className="p-1.5 text-slate-400 hover:text-rose-600">
                                <Trash2 className="w-3.5 h-3.5" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                )}

                {activeTab === 'offices' && (
                  <table className="w-full text-left text-xs">
                    <thead className="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px]">
                      <tr>
                        <th className="py-3 px-4">Nama Kantor</th>
                        <th className="py-3 px-4">Alamat</th>
                        <th className="py-3 px-4">Koordinat GPS</th>
                        <th className="py-3 px-4">Radius Absen</th>
                        <th className="py-3 px-4 text-right">Aksi</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 font-medium">
                      {offices.map((o) => (
                        <tr key={o.id} className="hover:bg-slate-50 transition">
                          <td className="py-3 px-4 font-bold text-slate-900">{o.name}</td>
                          <td className="py-3 px-4 text-slate-500">{o.address || '-'}</td>
                          <td className="py-3 px-4 font-mono text-[11px] text-blue-700">
                            {o.latitude}, {o.longitude}
                          </td>
                          <td className="py-3 px-4">
                            <span className="font-semibold text-emerald-700">{o.radius_meter || 100} meter</span>
                          </td>
                          <td className="py-3 px-4 text-right">
                            <div className="flex items-center justify-end gap-1.5">
                              <button onClick={() => openEditModal(o)} className="p-1.5 text-slate-400 hover:text-blue-600">
                                <Edit3 className="w-3.5 h-3.5" />
                              </button>
                              <button onClick={() => handleDelete(o.id, o.name)} className="p-1.5 text-slate-400 hover:text-rose-600">
                                <Trash2 className="w-3.5 h-3.5" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                )}

                {activeTab === 'shifts' && (
                  <table className="w-full text-left text-xs">
                    <thead className="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px]">
                      <tr>
                        <th className="py-3 px-4">Nama Shift</th>
                        <th className="py-3 px-4">Jam Mulai</th>
                        <th className="py-3 px-4">Jam Selesai</th>
                        <th className="py-3 px-4">Toleransi Keterlambatan</th>
                        <th className="py-3 px-4 text-right">Aksi</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 font-medium">
                      {shifts.map((s) => (
                        <tr key={s.id} className="hover:bg-slate-50 transition">
                          <td className="py-3 px-4 font-bold text-slate-900">{s.name}</td>
                          <td className="py-3 px-4 font-bold text-blue-700">{s.start_time?.slice(0, 5)} WIB</td>
                          <td className="py-3 px-4 font-bold text-blue-700">{s.end_time?.slice(0, 5)} WIB</td>
                          <td className="py-3 px-4">
                            <span className="font-semibold text-amber-700">{s.late_tolerance_minutes || 15} Menit</span>
                          </td>
                          <td className="py-3 px-4 text-right">
                            <div className="flex items-center justify-end gap-1.5">
                              <button onClick={() => openEditModal(s)} className="p-1.5 text-slate-400 hover:text-blue-600">
                                <Edit3 className="w-3.5 h-3.5" />
                              </button>
                              <button onClick={() => handleDelete(s.id, s.name)} className="p-1.5 text-slate-400 hover:text-rose-600">
                                <Trash2 className="w-3.5 h-3.5" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                )}
              </div>
            )}
          </div>

          {/* Generic Modal CRUD */}
          <Modal
            isOpen={modalOpen}
            onClose={() => setModalOpen(false)}
            title={editingItem ? `Edit Data: ${editingItem.name || ''}` : `Tambah ${tabs.find((t) => t.key === activeTab)?.label}`}
            size="md"
          >
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">Nama *</label>
                <input
                  type="text"
                  required
                  value={formData.name || ''}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  placeholder="Nama entitas"
                />
              </div>

              {activeTab === 'roles' && (
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Deskripsi Hak Akses</label>
                  <input
                    type="text"
                    value={formData.description || ''}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    placeholder="Contoh: Akses checklist produksi dan absensi"
                  />
                </div>
              )}

              {activeTab === 'positions' && (
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Pilih Departemen</label>
                  <select
                    value={formData.department_id || ''}
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
              )}

              {activeTab === 'departments' && (
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Keterangan Divisi</label>
                  <input
                    type="text"
                    value={formData.description || ''}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                    className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    placeholder="Keterangan divisi"
                  />
                </div>
              )}

              {activeTab === 'offices' && (
                <>
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">Alamat Kantor / Workshop</label>
                    <textarea
                      rows={2}
                      value={formData.address || ''}
                      onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                      className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    />
                  </div>
                  <div className="grid grid-cols-2 gap-3">
                    <div>
                      <label className="block text-xs font-bold text-slate-700 mb-1">Latitude *</label>
                      <input
                        type="number"
                        step="any"
                        required
                        value={formData.latitude ?? ''}
                        onChange={(e) => setFormData({ ...formData, latitude: parseFloat(e.target.value) })}
                        className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-mono"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-slate-700 mb-1">Longitude *</label>
                      <input
                        type="number"
                        step="any"
                        required
                        value={formData.longitude ?? ''}
                        onChange={(e) => setFormData({ ...formData, longitude: parseFloat(e.target.value) })}
                        className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-mono"
                      />
                    </div>
                  </div>
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">Radius Toleransi Geofence (Meter)</label>
                    <input
                      type="number"
                      value={formData.radius_meter ?? 100}
                      onChange={(e) => setFormData({ ...formData, radius_meter: parseInt(e.target.value, 10) })}
                      className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs"
                    />
                  </div>
                </>
              )}

              {activeTab === 'shifts' && (
                <>
                  <div className="grid grid-cols-2 gap-3">
                    <div>
                      <label className="block text-xs font-bold text-slate-700 mb-1">Jam Mulai *</label>
                      <input
                        type="time"
                        required
                        value={formData.start_time || '08:00'}
                        onChange={(e) => setFormData({ ...formData, start_time: e.target.value })}
                        className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-bold"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-slate-700 mb-1">Jam Selesai *</label>
                      <input
                        type="time"
                        required
                        value={formData.end_time || '17:00'}
                        onChange={(e) => setFormData({ ...formData, end_time: e.target.value })}
                        className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-bold"
                      />
                    </div>
                  </div>
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">Toleransi Keterlambatan (Menit)</label>
                    <input
                      type="number"
                      value={formData.late_tolerance_minutes ?? 15}
                      onChange={(e) => setFormData({ ...formData, late_tolerance_minutes: parseInt(e.target.value, 10) })}
                      className="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs"
                    />
                  </div>
                </>
              )}

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
                  Simpan Data
                </button>
              </div>
            </form>
          </Modal>
        </main>
      </div>
    </div>
  );
}
