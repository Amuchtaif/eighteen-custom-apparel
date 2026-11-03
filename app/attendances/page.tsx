'use client';

import React, { useState, useEffect } from 'react';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import Modal from '@/components/Modal';
import { 
  Clock, 
  Search, 
  Calendar, 
  MapPin, 
  CheckCircle2, 
  AlertTriangle, 
  User, 
  Camera, 
  Filter, 
  RefreshCw 
} from 'lucide-react';
import { formatDate } from '@/lib/utils';

export default function AttendancesPage() {
  const [attendances, setAttendances] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [dateFilter, setDateFilter] = useState('');
  const [selectedPhoto, setSelectedPhoto] = useState<string | null>(null);

  const fetchAttendances = async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (search) params.append('search', search);
      if (dateFilter) params.append('date', dateFilter);

      const res = await fetch(`/api/attendances?${params.toString()}`);
      const data = await res.json();
      if (data.success) {
        setAttendances(data.data || []);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchAttendances();
  }, [dateFilter]);

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    fetchAttendances();
  };

  // Quick stats
  const totalRecords = attendances.length;
  const onTimeCount = attendances.filter((a) => a.status === 'present' || a.status === 'tepat_waktu').length;
  const lateCount = attendances.filter((a) => a.status === 'late' || a.status === 'terlambat').length;

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole="Super Admin" userName="HRD Manager" />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Rekap Absensi & Kehadiran"
          subtitle="Monitoring kehadiran staf, validasi GPS radius kantor, dan bukti foto selfie"
        />

        <main className="flex-1 p-8 mt-16 max-w-7xl w-full mx-auto space-y-6">
          {/* Quick Metrics */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kehadiran Tercatat</div>
                <div className="text-2xl font-black text-slate-800 mt-1">{totalRecords} Log</div>
              </div>
              <div className="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-200">
                <Clock className="w-5 h-5" />
              </div>
            </div>

            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Tepat Waktu</div>
                <div className="text-2xl font-black text-emerald-600 mt-1">{onTimeCount} Log</div>
              </div>
              <div className="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                <CheckCircle2 className="w-5 h-5" />
              </div>
            </div>

            <div className="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold text-slate-500 uppercase tracking-wider">Terlambat</div>
                <div className="text-2xl font-black text-amber-600 mt-1">{lateCount} Log</div>
              </div>
              <div className="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-200">
                <AlertTriangle className="w-5 h-5" />
              </div>
            </div>
          </div>

          {/* Filter Bar */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <form onSubmit={handleSearchSubmit} className="flex-1 flex items-center gap-2 max-w-lg">
              <div className="relative flex-1">
                <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input
                  type="text"
                  placeholder="Cari nama atau NIP staf..."
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                />
              </div>
              <button
                type="submit"
                className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm"
              >
                Cari
              </button>
            </form>

            <div className="flex items-center gap-3">
              <div className="flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                <Calendar className="w-3.5 h-3.5 text-slate-500" />
                <input
                  type="date"
                  value={dateFilter}
                  onChange={(e) => setDateFilter(e.target.value)}
                  className="bg-transparent text-xs text-slate-700 focus:outline-none"
                />
                {dateFilter && (
                  <button
                    onClick={() => setDateFilter('')}
                    className="text-xs text-slate-400 hover:text-slate-600 font-bold ml-1"
                  >
                    ×
                  </button>
                )}
              </div>

              <button
                onClick={fetchAttendances}
                disabled={loading}
                className="p-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 transition"
                title="Muat ulang"
              >
                <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
              </button>
            </div>
          </div>

          {/* Table */}
          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs">
                <thead className="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                  <tr>
                    <th className="py-3.5 px-4">Karyawan</th>
                    <th className="py-3.5 px-4">Shift & Kantor</th>
                    <th className="py-3.5 px-4">Jam Masuk</th>
                    <th className="py-3.5 px-4">Jam Pulang</th>
                    <th className="py-3.5 px-4">Jarak GPS</th>
                    <th className="py-3.5 px-4">Status</th>
                    <th className="py-3.5 px-4 text-center">Foto Selfie</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium text-slate-700">
                  {loading ? (
                    <tr>
                      <td colSpan={7} className="p-8 text-center text-slate-400">
                        Memuat data absensi...
                      </td>
                    </tr>
                  ) : attendances.length === 0 ? (
                    <tr>
                      <td colSpan={7} className="p-8 text-center text-slate-400">
                        Tidak ada catatan absensi yang sesuai filter.
                      </td>
                    </tr>
                  ) : (
                    attendances.map((item) => (
                      <tr key={item.id} className="hover:bg-slate-50/80 transition">
                        <td className="py-3.5 px-4">
                          <div className="font-black text-slate-900">{item.employee_name}</div>
                          <div className="text-[11px] font-mono text-slate-500">{item.employee_code}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-semibold text-slate-800">{item.shift_name || 'Reguler'}</div>
                          <div className="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                            <MapPin className="w-3 h-3 text-slate-400" />
                            <span>{item.office_name || 'Workshop Utama'}</span>
                          </div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-bold text-slate-900">
                            {item.check_in_time ? new Date(item.check_in_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-'}
                          </div>
                          <div className="text-[11px] text-slate-400">
                            {item.check_in_time ? formatDate(item.check_in_time) : ''}
                          </div>
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-bold text-slate-900">
                            {item.check_out_time ? new Date(item.check_out_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-'}
                          </div>
                          <div className="text-[11px] text-slate-400">
                            {item.check_out_time ? formatDate(item.check_out_time) : 'Belum checkout'}
                          </div>
                        </td>
                        <td className="py-3.5 px-4">
                          {item.distance_meters !== null && item.distance_meters !== undefined ? (
                            <span
                              className={`font-semibold ${
                                item.distance_meters <= 100 ? 'text-emerald-700' : 'text-amber-700'
                              }`}
                            >
                              {Math.round(item.distance_meters)} m
                            </span>
                          ) : (
                            <span className="text-slate-400">-</span>
                          )}
                        </td>
                        <td className="py-3.5 px-4">
                          <span
                            className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                              item.status === 'present' || item.status === 'tepat_waktu'
                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                : item.status === 'late' || item.status === 'terlambat'
                                ? 'bg-amber-50 text-amber-700 border border-amber-200'
                                : 'bg-slate-100 text-slate-600 border border-slate-200'
                            }`}
                          >
                            {item.status === 'present' || item.status === 'tepat_waktu'
                              ? 'Tepat Waktu'
                              : item.status === 'late' || item.status === 'terlambat'
                              ? 'Terlambat'
                              : item.status || 'Hadir'}
                          </span>
                        </td>
                        <td className="py-3.5 px-4 text-center">
                          {item.check_in_photo ? (
                            <button
                              type="button"
                              onClick={() => setSelectedPhoto(item.check_in_photo)}
                              className="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold hover:bg-blue-100 transition border border-blue-200"
                            >
                              <Camera className="w-3.5 h-3.5" />
                              <span>Lihat</span>
                            </button>
                          ) : (
                            <span className="text-slate-400 text-[11px]">-</span>
                          )}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Photo Modal */}
          {selectedPhoto && (
            <Modal
              isOpen={Boolean(selectedPhoto)}
              onClose={() => setSelectedPhoto(null)}
              title="Foto Bukti Absensi Selfie"
              size="md"
            >
              <div className="flex flex-col items-center justify-center space-y-4">
                <img
                  src={selectedPhoto}
                  alt="Bukti Kehadiran"
                  className="rounded-2xl max-h-[420px] w-auto border border-slate-200 object-cover shadow-sm"
                />
                <button
                  type="button"
                  onClick={() => setSelectedPhoto(null)}
                  className="px-5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition"
                >
                  Tutup
                </button>
              </div>
            </Modal>
          )}
        </main>
      </div>
    </div>
  );
}

