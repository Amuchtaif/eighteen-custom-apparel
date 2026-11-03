import './globals.css';
import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Eighteen Custom Apparel - Order & Production Management',
  description: 'Sistem Manajemen Pesanan dan Alur Produksi Terintegrasi untuk Konveksi & Custom Sportswear',
  icons: {
    icon: [
      { url: '/icon.png' },
      { url: '/favicon.ico' },
    ],
    shortcut: '/favicon.ico',
    apple: '/apple-icon.png',
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="id">
      <body className="min-h-screen bg-slate-50 text-slate-900 antialiased">
        {children}
      </body>
    </html>
  );
}

