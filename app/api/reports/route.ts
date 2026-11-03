import { NextRequest, NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    // Monthly sales revenue
    const monthlySales = await query<any>(
      `SELECT DATE_FORMAT(tanggal_masuk, '%Y-%m') as month, 
              COUNT(*) as total_orders, 
              SUM(total_tagihan) as revenue,
              SUM(qty_esti) as total_qty
       FROM orders
       WHERE tanggal_masuk IS NOT NULL
       GROUP BY DATE_FORMAT(tanggal_masuk, '%Y-%m')
       ORDER BY month DESC LIMIT 6`
    );

    // Production stage distribution
    const stages = await query<any>(
      `SELECT status_produksi as stage, COUNT(*) as count, SUM(qty_esti) as qty
       FROM orders
       GROUP BY status_produksi`
    );

    // Attendance recap
    const attendanceRecap = await query<any>(
      `SELECT attendance_status, COUNT(*) as count
       FROM attendances
       WHERE check_in_time >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
       GROUP BY attendance_status`
    );

    return NextResponse.json({
      success: true,
      monthly_sales: monthlySales.reverse(),
      stages_distribution: stages,
      attendance_recap: attendanceRecap,
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

