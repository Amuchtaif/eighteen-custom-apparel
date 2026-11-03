import { NextRequest, NextResponse } from 'next/server';
import { queryOne, query } from '@/lib/db';
import { formatDate } from '@/lib/utils';

export async function GET(req: NextRequest) {
  try {
    const { searchParams } = new URL(req.url);
    const code = searchParams.get('code');

    if (!code || !code.trim()) {
      return NextResponse.json(
        { success: false, message: 'Silakan masukkan nomor pesanan.' },
        { status: 400 }
      );
    }

    const order = await queryOne<any>(
      `SELECT o.*, c.company_name, pp.print_status, pp.press_status, pp.pola_status, pp.jahit_status, pp.finish_status
       FROM orders o
       LEFT JOIN customers c ON o.customer_id = c.id
       LEFT JOIN production_progress pp ON o.id = pp.order_id
       WHERE o.kode_order = ?`,
      [code.trim()]
    );

    if (!order) {
      return NextResponse.json(
        { success: false, message: 'Pesanan tidak ditemukan. Periksa kembali nomor pesanan Anda.' },
        { status: 404 }
      );
    }

    const stageDefinitions = [
      { name: 'Antrian', label: 'Antrian Desain & Bahan', active: true },
      { name: 'Print', label: 'Printing Sublimasi', active: Boolean(order.print_status) },
      { name: 'Press', label: 'Press Pemanas Kain', active: Boolean(order.press_status) },
      { name: 'Pola', label: 'Pemotongan Pola', active: Boolean(order.pola_status) },
      { name: 'Jahit', label: 'Penjahitan Jersey', active: Boolean(order.jahit_status) },
      { name: 'Finish', label: 'Finishing & QC', active: Boolean(order.finish_status) },
      { name: 'Siap Kirim', label: 'Packing & Siap Kirim', active: order.status_produksi === 'Siap Kirim' || order.status_produksi === 'Selesai' },
      { name: 'Selesai', label: 'Pesanan Diterima', active: order.status_produksi === 'Selesai' },
    ];

    const isCompleted = order.status_produksi === 'Selesai';
    const stages = stageDefinitions.map((s, idx) => {
      let status = 'pending';
      if (isCompleted || s.active) {
        status = 'completed';
      } else if (order.status_produksi === s.name) {
        status = 'active';
      }
      return {
        name: s.name,
        label: s.label,
        status,
      };
    });

    return NextResponse.json({
      success: true,
      order: {
        order_number: order.kode_order,
        team_name: order.nama_team,
        status: order.status_produksi,
        bahan: order.bahan,
        qty: order.qty_esti,
        date: formatDate(order.tanggal_masuk),
        deadline: formatDate(order.deadline),
      },
      stages,
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

