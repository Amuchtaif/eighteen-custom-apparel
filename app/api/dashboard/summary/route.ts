import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) {
      return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });
    }

    const today = new Date().toISOString().split('T')[0];

    const [revRow] = await query<any>('SELECT COALESCE(SUM(total_tagihan), 0) as total_revenue FROM orders');
    const [ordersRow] = await query<any>('SELECT COUNT(*) as total_orders FROM orders');
    const [activeRow] = await query<any>(`SELECT COUNT(*) as active_orders FROM orders WHERE status_produksi != 'Selesai'`);
    const [doneRow] = await query<any>(`SELECT COUNT(*) as completed_orders FROM orders WHERE status_produksi = 'Selesai'`);
    const [lateRow] = await query<any>(
      `SELECT COUNT(*) as delayed_orders FROM orders WHERE status_produksi != 'Selesai' AND DATE(deadline) < ?`,
      [today]
    );
    const [attRow] = await query<any>(
      `SELECT COUNT(*) as today_attendance FROM attendances WHERE DATE(check_in_time) = ?`,
      [today]
    );

    const recentOrders = await query<any>(
      `SELECT o.id, o.kode_order, o.nama_team, o.bahan, o.qty_esti, o.deadline, o.status_produksi, o.total_tagihan,
              c.company_name as customer_name
       FROM orders o
       LEFT JOIN customers c ON o.customer_id = c.id
       ORDER BY o.id DESC LIMIT 6`
    );

    const stagesBreakdown = await query<any>(
      `SELECT status_produksi as stage, COUNT(*) as count
       FROM orders
       GROUP BY status_produksi`
    );

    return NextResponse.json({
      success: true,
      stats: {
        total_revenue: Number(revRow?.total_revenue || 0),
        total_orders: Number(ordersRow?.total_orders || 0),
        active_orders: Number(activeRow?.active_orders || 0),
        completed_orders: Number(doneRow?.completed_orders || 0),
        delayed_orders: Number(lateRow?.delayed_orders || 0),
        today_attendance: Number(attRow?.today_attendance || 0),
      },
      recent_orders: recentOrders,
      stages_breakdown: stagesBreakdown,
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

