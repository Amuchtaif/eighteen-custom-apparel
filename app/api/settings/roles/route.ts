import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const roles = await query('SELECT * FROM roles ORDER BY id ASC');
    return NextResponse.json({ success: true, data: roles });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { name, description, is_active } = await req.json();
    if (!name) return NextResponse.json({ success: false, message: 'Nama role wajib diisi.' }, { status: 422 });

    const res: any = await query(
      'INSERT INTO roles (name, description, is_active, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())',
      [name, description || '', is_active !== false ? 1 : 0]
    );

    return NextResponse.json({
      success: true,
      message: 'Role berhasil ditambahkan.',
      data: { id: res.insertId, name, description },
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function PUT(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { id, name, description, is_active } = await req.json();
    await query(
      'UPDATE roles SET name = ?, description = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
      [name, description || '', is_active ? 1 : 0, id]
    );
    return NextResponse.json({ success: true, message: 'Role berhasil diperbarui.' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function DELETE(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { searchParams } = new URL(req.url);
    const id = searchParams.get('id');

    // Prevent deletion if in use
    const [userCount] = await query<any>('SELECT COUNT(*) as c FROM users WHERE role_id = ?', [id]);
    const [empCount] = await query<any>('SELECT COUNT(*) as c FROM employees WHERE role_id = ?', [id]);

    if ((userCount?.c || 0) > 0 || (empCount?.c || 0) > 0) {
      return NextResponse.json(
        { success: false, message: 'Role sedang digunakan oleh pengguna/karyawan dan tidak dapat dihapus.' },
        { status: 422 }
      );
    }

    await query('DELETE FROM roles WHERE id = ?', [id]);
    return NextResponse.json({ success: true, message: 'Role berhasil dihapus.' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

