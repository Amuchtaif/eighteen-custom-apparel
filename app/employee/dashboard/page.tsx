'use client';

import React, { useState, useEffect } from 'react';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import { 
  Clock, 
  MapPin, 
  CheckCircle2, 
  AlertCircle, 
  Camera, 
  LogOut, 
  Navigation,
  ShieldCheck,
  Calendar,
  Sparkles
} from 'lucide-react';

export default function EmployeeDashboardPage() {
  const [sessionUser, setSessionUser] = useState<any>(null);
  const [coords, setCoords] = useState<{ lat: number; lng: number } | null>(null);
  const [geoError, setGeoError] = useState('');
  const [loadingAction, setLoadingAction] = useState(false);
  const [message, setMessage] = useState<{ text: string; type: 'success' | 'error' } | null>(null);
  const [recentAttendances, setRecentAttendances] = useState<any[]>([]);

  useEffect(() => {
    fetch('/api/auth/me')
      .then((res) => res.json())
      .then((data) => {
        if (data.user) setSessionUser(data.user);
      })
      .catch((e) => console.error(e));

    fetch('/api/attendances')
      .then((res) => res.json())
      .then((data) => {
        if (data.data) setRecentAttendances(data.data.slice(0, 5));
      })
      .catch((e) => console.error(e));

    // Request GPS location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          setCoords({ lat: pos.coords.latitude, lng: pos.coords.longitude });
          setGeoError('');
        },
        (err) => {
          setGeoError('Izin akses lokasi (GPS) diperlukan untuk validasi absensi.');
        },
        { enableHighAccuracy: true }
      );
    } else {
      setGeoError('Perangkat Anda tidak mendukung geolokasi GPS.');
    }
  }, []);

  const handleClock = async (action: 'check_in' | 'check_out') => {
    if (!coords) {
      setMessage({ text: 'Menunggu koordinat GPS. Pastikan izin lokasi aktif.', type: 'error' });
      return;
    }

    setLoadingAction(true);
    setMessage(null);

    try {
      const res = await fetch('/api/attendance/clock', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          latitude: coords.lat,
          longitude: coords.lng,
          action,
          notes: 'Absen via Employee Web Portal',
          selfie_data: true,
        }),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Gagal melakukan absensi.');
      }

      setMessage({ text: data.message, type: 'success' });
      // Refresh attendance logs
      const updated = await fetch('/api/attendances').then((r) => r.json());
      if (updated.data) setRecentAttendances(updated.data.slice(0, 5));
    } catch (err: any) {
      setMessage({ text: err.message, type: 'error' });
    } finally {
      setLoadingAction(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 flex">
      <Sidebar userRole={sessionUser?.role_name || 'Staff'} userName={sessionUser?.name || 'Staff'} />

      <div className="flex-1 ml-64 flex flex-col min-w-0">
        <Navbar
          title="Employee Portal"
          subtitle="Dashboard kehadiran berbasis GPS & shift kerja"
          userName={sessionUser?.name}
          userRole={sessionUser?.role_name}
        />

        <main className="flex-1 p-8 mt-16 max-w-4xl w-full mx-auto space-y-6">
          {/* Welcome Card */}
          <div className="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-600/20 relative overflow-hidden">
            <div className="relative z-10">
              <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold mb-3">
                <Sparkles className="w-3.5 h-3.5 text-amber-300" />
                Shift Reguler • 08:00 - 17:00 (Toleransi 15 Menit)
              </span>
              <h1 className="text-2xl font-black">Halo, {sessionUser?.name || 'Karyawan'}!</h1>
              <p className="text-xs text-blue-100 mt-1 max-w-lg">
                Silakan lakukan validasi absensi harian menggunakan GPS dan kamera selfie. Pastikan Anda berada dalam radius kantor Eighteen.
              </p>
            </div>
          </div>

          {/* Feedback Message */}
          {message && (
            <div
              className={`p-4 rounded-2xl border text-xs font-semibold flex items-center gap-3 animate-fadeIn ${
                message.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border-rose-200'
              }`}
            >
              {message.type === 'success' ? (
                <CheckCircle2 className="w-5 h-5 text-emerald-600 flex-shrink-0" />
              ) : (
                <AlertCircle className="w-5 h-5 text-rose-600 flex-shrink-0" />
              )}
              <span>{message.text}</span>
            </div>
          )}

          {/* Clock In / Out Card */}
          <div className="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm">
            <h2 className="text-base font-black text-slate-800 mb-2">Presensi Kehadiran Hari Ini</h2>
            <p className="text-xs text-slate-500 mb-6">
              Validasi posisi lokasi saat ini terhadap geofencing kantor.
            </p>

            {/* GPS Status Indicator */}
            <div className="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between mb-6">
              <div className="flex items-center gap-3">
                <div
                  className={`w-10 h-10 rounded-xl flex items-center justify-center ${
                    coords ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'
                  }`}
                >
                  <Navigation className="w-5 h-5 animate-pulse" />
                </div>
                <div>
                  <h4 className="text-xs font-bold text-slate-800">
                    {coords ? 'Sinyal GPS Terkoneksi' : 'Mencari Koordinat GPS...'}
                  </h4>
                  <p className="text-[11px] text-slate-500 font-mono mt-0.5">
                    {coords ? `Lat: ${coords.lat.toFixed(6)}, Lng: ${coords.lng.toFixed(6)}` : geoError || 'Harap izinkan akses lokasi browser'}
                  </p>
                </div>
              </div>

              {coords && (
                <span className="text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                  Akurat
                </span>
              )}
            </div>

            {/* Action Buttons */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <button
                type="button"
                onClick={() => handleClock('check_in')}
                disabled={loadingAction || !coords}
                className="py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-3 disabled:opacity-50"
              >
                <Clock className="w-5 h-5" />
                <span>{loadingAction ? 'Memproses...' : 'Absen Masuk (Clock In)'}</span>
              </button>

              <button
                type="button"
                onClick={() => handleClock('check_out')}
                disabled={loadingAction || !coords}
                className="py-4 px-6 rounded-2xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm transition shadow-lg shadow-slate-800/30 flex items-center justify-center gap-3 disabled:opacity-50"
              >
                <LogOut className="w-5 h-5 text-amber-400" />
                <span>{loadingAction ? 'Memproses...' : 'Absen Keluar (Clock Out)'}</span>
              </button>
            </div>
          </div>

          {/* Recent Attendance Logs */}
          <div className="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm">
            <h3 className="text-sm font-black text-slate-800 mb-4">Riwayat Kehadiran Terbaru</h3>
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs">
                <thead>
                  <tr className="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <th className="pb-3">Tanggal & Waktu</th>
                    <th className="pb-3 text-center">Status</th>
                    <th className="pb-3 text-center">Jarak Masuk</th>
                    <th className="pb-3 text-center">Jam Keluar</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {recentAttendances.map((item) => (
                    <tr key={item.id} className="hover:bg-slate-50 transition">
                      <td className="py-3 font-semibold text-slate-800">{item.check_in_time}</td>
                      <td className="py-3 text-center">
                        <span
                          className={`inline-flex px-2 py-0.5 rounded text-[10px] font-bold border ${
                            item.attendance_status === 'Hadir'
                              ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                              : 'bg-amber-50 text-amber-700 border-amber-200'
                          }`}
                        >
                          {item.attendance_status}
                        </span>
                      </td>
                      <td className="py-3 text-center font-mono text-slate-600">
                        {item.check_in_distance ? `${item.check_in_distance} m` : '-'}
                      </td>
                      <td className="py-3 text-center font-semibold text-slate-700">
                        {item.check_out_time || 'Belum Clock Out'}
                      </td>
                    </tr>
                  ))}
                  {recentAttendances.length === 0 && (
                    <tr>
                      <td colSpan={4} className="py-6 text-center text-slate-400">
                        Belum ada riwayat absensi.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}

