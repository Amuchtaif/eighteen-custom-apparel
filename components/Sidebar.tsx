'use client';

import React from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { 
  LayoutDashboard, 
  UserCheck, 
  ShoppingBag, 
  Kanban, 
  CalendarDays, 
  Users, 
  Clock, 
  Receipt, 
  CreditCard, 
  BarChart3, 
  Settings, 
  LogOut,
  Shirt,
  Search
} from 'lucide-react';

interface SidebarProps {
  userRole?: string;
  userName?: string;
}

export default function Sidebar({ userRole = 'Super Admin', userName = 'User' }: SidebarProps) {
  const pathname = usePathname();
  const router = useRouter();

  const handleLogout = async () => {
    try {
      await fetch('/api/auth/logout', { method: 'POST' });
      router.push('/login');
      router.refresh();
    } catch (e) {
      console.error(e);
    }
  };

  const isOwnerOrAdmin = ['owner', 'super admin', 'admin'].includes(userRole.toLowerCase());
  const isFinance = ['finance', 'keuangan', 'super admin', 'owner'].includes(userRole.toLowerCase());
  const isHRD = ['hrd', 'super admin', 'owner'].includes(userRole.toLowerCase());

  const navItems = [
    {
      group: 'Menu Utama',
      items: [
        ...(isOwnerOrAdmin
          ? [{ href: '/dashboard', label: 'Executive Dashboard', icon: LayoutDashboard }]
          : []),
        { href: '/employee/dashboard', label: 'Employee Dashboard', icon: UserCheck },
      ],
    },
    {
      group: 'Produksi & Order',
      items: [
        { href: '/orders', label: 'Pesanan Produksi', icon: ShoppingBag },
        { href: '/orders/kanban', label: 'Kanban Alur Produksi', icon: Kanban },
        { href: '/orders/calendar', label: 'Kalender Deadline', icon: CalendarDays },
      ],
    },
    {
      group: 'SDM & Absensi',
      items: [
        ...(isHRD ? [{ href: '/employees', label: 'Data Karyawan', icon: Users }] : []),
        { href: '/attendances', label: 'Rekap Absensi', icon: Clock },
      ],
    },
    {
      group: 'Keuangan',
      items: [
        ...(isFinance
          ? [
              { href: '/invoices', label: 'Faktur / Invoice', icon: Receipt },
              { href: '/payments', label: 'Pencatatan Bayar', icon: CreditCard },
            ]
          : []),
      ],
    },
    {
      group: 'Sistem',
      items: [
        ...(isOwnerOrAdmin
          ? [
              { href: '/reports', label: 'Laporan Bisnis', icon: BarChart3 },
              { href: '/settings', label: 'Master Data & Shift', icon: Settings },
            ]
          : []),
      ],
    },
  ];

  return (
    <aside className="w-64 bg-slate-900 text-slate-300 flex flex-col h-screen fixed left-0 top-0 z-30 select-none border-r border-slate-800">
      {/* Brand Header */}
      <div className="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950/40">
        <Link href="/dashboard" className="flex items-center gap-3">
          <div className="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black shadow-md shadow-blue-500/20">
            <Shirt className="w-5 h-5 text-white" />
          </div>
          <div>
            <h1 className="font-extrabold text-white text-base leading-tight tracking-tight">EIGHTEEN</h1>
            <p className="text-[11px] text-blue-400 font-medium">Custom Apparel ERP</p>
          </div>
        </Link>
      </div>

      {/* Navigation Groups */}
      <div className="flex-1 overflow-y-auto px-4 py-4 space-y-6">
        {navItems.map((group, idx) => {
          if (group.items.length === 0) return null;
          return (
            <div key={idx}>
              <p className="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                {group.group}
              </p>
              <div className="space-y-1">
                {group.items.map((item) => {
                  const isActive = pathname === item.href || (item.href !== '/dashboard' && item.href !== '/employee/dashboard' && pathname.startsWith(item.href));
                  const Icon = item.icon;
                  return (
                    <Link
                      key={item.href}
                      href={item.href}
                      className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-all ${
                        isActive
                          ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30'
                          : 'text-slate-400 hover:text-white hover:bg-slate-800/60'
                      }`}
                    >
                      <Icon className={`w-4 h-4 ${isActive ? 'text-white' : 'text-slate-400'}`} />
                      <span>{item.label}</span>
                    </Link>
                  );
                })}
              </div>
            </div>
          );
        })}
      </div>

      {/* User & Logout Footer */}
      <div className="p-4 border-t border-slate-800 bg-slate-950/40">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-3 overflow-hidden">
            <div className="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-blue-400">
              {userName.charAt(0).toUpperCase()}
            </div>
            <div className="truncate">
              <p className="text-xs font-bold text-white truncate">{userName}</p>
              <p className="text-[11px] text-slate-400 truncate">{userRole}</p>
            </div>
          </div>
          <button
            onClick={handleLogout}
            title="Keluar"
            className="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800 transition"
          >
            <LogOut className="w-4 h-4" />
          </button>
        </div>
      </div>
    </aside>
  );
}
