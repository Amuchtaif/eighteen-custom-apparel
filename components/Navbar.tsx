'use client';

import React from 'react';
import Link from 'next/link';
import { Search, Bell, ExternalLink, ShieldCheck } from 'lucide-react';

interface NavbarProps {
  title?: string;
  subtitle?: string;
  userName?: string;
  userRole?: string;
}

export default function Navbar({
  title = 'Sistem Produksi Eighteen',
  subtitle = 'Kelola pesanan apparel & operasional konveksi',
  userName = 'Admin',
  userRole = 'Staff',
}: NavbarProps) {
  return (
    <header className="h-16 bg-white border-b border-slate-200 fixed top-0 right-0 left-64 z-20 px-8 flex items-center justify-between">
      <div>
        <h2 className="text-base font-bold text-slate-800 leading-tight">{title}</h2>
        <p className="text-xs text-slate-500 font-medium">{subtitle}</p>
      </div>

      <div className="flex items-center gap-4">
        {/* Shortcut to public tracking */}
        <Link
          href="/track-order"
          target="_blank"
          className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition"
        >
          <ExternalLink className="w-3.5 h-3.5 text-blue-600" />
          <span>Tracking Publik</span>
        </Link>

        {/* Shortcut to public order */}
        <Link
          href="/custom-order"
          target="_blank"
          className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition border border-blue-200"
        >
          <span>+ Form Order Baru</span>
        </Link>

        <div className="h-6 w-px bg-slate-200" />

        {/* Role indicator badge */}
        <div className="flex items-center gap-2">
          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
            <ShieldCheck className="w-3.5 h-3.5 text-blue-600" />
            {userRole}
          </span>
        </div>
      </div>
    </header>
  );
}

