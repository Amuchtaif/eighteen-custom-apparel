import { NextRequest, NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const orders = await query<any>(
      `SELECT id, kode_order, nama_team, bahan, deadline, qty_esti, status_produksi, lokasi_produksi
       FROM orders
       WHERE deadline IS NOT NULL
       ORDER BY deadline ASC`
    );

    const events = orders.map((o) => {
      let color = '#0284c7'; // default blue
      if (o.status_produksi === 'Selesai') {
        color = '#10b981'; // emerald
      } else if (o.status_produksi === 'Jahit') {
        color = '#d97706'; // amber
      } else if (o.status_produksi === 'Pola') {
        color = '#ea580c'; // orange
      } else if (new Date(o.deadline) < new Date() && o.status_produksi !== 'Selesai') {
        color = '#ef4444'; // red overdue
      }

      return {
        id: o.id.toString(),
        title: `${o.kode_order} • ${o.nama_team} (${o.qty_esti} pcs)`,
        start: o.deadline,
        allDay: true,
        backgroundColor: color,
        borderColor: color,
        extendedProps: {
          order_id: o.id,
          kode_order: o.kode_order,
          nama_team: o.nama_team,
          status_produksi: o.status_produksi,
          qty_esti: o.qty_esti,
          lokasi_produksi: o.lokasi_produksi,
        },
      };
    });

    return NextResponse.json({ success: true, events });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

