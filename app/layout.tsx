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
    <html lang="id" className="scroll-smooth">
      <body className="min-h-screen bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 antialiased transition-colors duration-200">
        {children}
      </body>
    </html>
  );
}

