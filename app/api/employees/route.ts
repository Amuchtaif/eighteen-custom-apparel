import { NextRequest, NextResponse } from 'next/server';
import bcrypt from 'bcryptjs';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const employees = await query<any>(
      `SELECT e.*, u.name, u.email, u.phone,
              r.name as role_name,
              p.name as position_name,
              d.name as department_name,
              o.name as office_name,
              ws.name as shift_name
       FROM employees e
       JOIN users u ON e.user_id = u.id
       LEFT JOIN roles r ON e.role_id = r.id
       LEFT JOIN positions p ON e.position_id = p.id
       LEFT JOIN departments d ON e.department_id = d.id
       LEFT JOIN offices o ON e.office_id = o.id
       LEFT JOIN work_shifts ws ON e.work_shift_id = ws.id
       ORDER BY e.id DESC`
    );

    return NextResponse.json({ success: true, data: employees });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const data = await req.json();

    // Auto code: EMP-YYYYMM-XXXX
    const now = new Date();
    const yearMonth = `${now.getFullYear()}${String(now.getMonth() + 1).padStart(2, '0')}`;
    const prefix = `EMP-${yearMonth}-`;

    const countRow = await queryOne<any>(
      'SELECT COUNT(*) as total FROM employees WHERE employee_code LIKE ?',
      [`${prefix}%`]
    );
    const nextSeq = (countRow?.total || 0) + 1;
    const employeeCode = `${prefix}${String(nextSeq).padStart(4, '0')}`;

    // Create user if not exists
    let userId = data.user_id;
    if (!userId) {
      const hashedPassword = await bcrypt.hash(data.password || 'password123', 10);
      const userRes: any = await query(
        `INSERT INTO users (name, email, password, phone, role_id, is_active, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())`,
        [data.name, data.email, hashedPassword, data.phone || '', data.role_id]
      );
      userId = userRes.insertId;
    }

    const empRes: any = await query(
      `INSERT INTO employees (
        user_id, employee_code, role_id, position_id, department_id,
        office_id, work_shift_id, join_date, status, created_at, updated_at
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW(), NOW())`,
      [
        userId,
        employeeCode,
        data.role_id,
        data.position_id,
        data.department_id,
        data.office_id,
        data.work_shift_id,
        data.join_date || now.toISOString().split('T')[0],
      ]
    );

    return NextResponse.json({
      success: true,
      message: 'Karyawan berhasil didaftarkan.',
      employee_code: employeeCode,
      id: empRes.insertId,
    });
  } catch (error: any) {
    console.error('Create employee error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

