import { NextRequest, NextResponse } from 'next/server';
import bcrypt from 'bcryptjs';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const employee = await queryOne<any>(
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
       WHERE e.id = ?`,
      [params.id]
    );

    if (!employee) {
      return NextResponse.json({ success: false, message: 'Karyawan tidak ditemukan' }, { status: 404 });
    }

    return NextResponse.json({ success: true, data: employee });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function PUT(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const empId = params.id;
    const data = await req.json();

    const emp = await queryOne<any>('SELECT user_id FROM employees WHERE id = ?', [empId]);
    if (!emp) {
      return NextResponse.json({ success: false, message: 'Karyawan tidak ditemukan' }, { status: 404 });
    }

    // Update user info
    if (data.password) {
      const hashedPassword = await bcrypt.hash(data.password, 10);
      await query(
        'UPDATE users SET name = ?, email = ?, phone = ?, password = ?, role_id = ?, updated_at = NOW() WHERE id = ?',
        [data.name, data.email, data.phone || '', hashedPassword, data.role_id, emp.user_id]
      );
    } else {
      await query(
        'UPDATE users SET name = ?, email = ?, phone = ?, role_id = ?, updated_at = NOW() WHERE id = ?',
        [data.name, data.email, data.phone || '', data.role_id, emp.user_id]
      );
    }

    // Update employee record
    await query(
      `UPDATE employees SET
        role_id = ?,
        position_id = ?,
        department_id = ?,
        office_id = ?,
        work_shift_id = ?,
        status = ?,
        updated_at = NOW()
       WHERE id = ?`,
      [
        data.role_id,
        data.position_id,
        data.department_id,
        data.office_id,
        data.work_shift_id,
        data.status || 'active',
        empId,
      ]
    );

    return NextResponse.json({ success: true, message: 'Data karyawan berhasil diperbarui' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function DELETE(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const empId = params.id;
    await query('UPDATE employees SET status = "inactive", updated_at = NOW() WHERE id = ?', [empId]);

    return NextResponse.json({ success: true, message: 'Status karyawan dinonaktifkan' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

