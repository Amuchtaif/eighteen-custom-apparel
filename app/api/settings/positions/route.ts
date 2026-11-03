import { NextRequest, NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET() {
  try {
    const data = await query('SELECT * FROM positions ORDER BY id ASC');
    return NextResponse.json({ success: true, data });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { name, description, is_active } = await req.json();
    if (!name) return NextResponse.json({ success: false, message: 'Nama jabatan wajib diisi.' }, { status: 422 });

    const res: any = await query(
      'INSERT INTO positions (name, description, is_active, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())',
      [name, description || '', is_active !== false ? 1 : 0]
    );

    return NextResponse.json({ success: true, message: 'Jabatan berhasil ditambahkan.', data: { id: res.insertId } });
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
      'UPDATE positions SET name = ?, description = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
      [name, description || '', is_active ? 1 : 0, id]
    );
    return NextResponse.json({ success: true, message: 'Jabatan berhasil diperbarui.' });
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

    const [empCount] = await query<any>('SELECT COUNT(*) as c FROM employees WHERE position_id = ?', [id]);
    if ((empCount?.c || 0) > 0) {
      return NextResponse.json(
        { success: false, message: 'Jabatan sedang digunakan oleh karyawan aktif.' },
        { status: 422 }
      );
    }

    await query('DELETE FROM positions WHERE id = ?', [id]);
    return NextResponse.json({ success: true, message: 'Jabatan berhasil dihapus.' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

