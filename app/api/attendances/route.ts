import { NextRequest, NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { searchParams } = new URL(req.url);
    const search = searchParams.get('search') || '';
    const date = searchParams.get('date') || '';

    const conditions: string[] = ['1=1'];
    const params: any[] = [];

    if (search) {
      conditions.push('(u.name LIKE ? OR e.employee_code LIKE ?)');
      params.push(`%${search}%`, `%${search}%`);
    }
    if (date) {
      conditions.push('DATE(a.check_in_time) = ?');
      params.push(date);
    }

    const whereSql = conditions.join(' AND ');

    const attendances = await query<any>(
      `SELECT a.*, 
              a.check_in_distance as distance_meters,
              a.attendance_status as status,
              e.employee_code, u.name as employee_name, o.name as office_name, ws.name as shift_name
       FROM attendances a
       JOIN employees e ON a.employee_id = e.id
       JOIN users u ON e.user_id = u.id
       LEFT JOIN offices o ON a.office_id = o.id
       LEFT JOIN work_shifts ws ON a.work_shift_id = ws.id
       WHERE ${whereSql}
       ORDER BY a.check_in_time DESC
       LIMIT 100`,
      params
    );

    return NextResponse.json({ success: true, data: attendances });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}
