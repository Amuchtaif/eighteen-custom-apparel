import { NextRequest, NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET() {
  try {
    const data = await query('SELECT * FROM work_shifts ORDER BY id ASC');
    return NextResponse.json({ success: true, data });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { name, start_time, end_time, late_tolerance_minutes, is_active } = await req.json();
    if (!name || !start_time || !end_time) {
      return NextResponse.json({ success: false, message: 'Nama shift, jam mulai, dan jam selesai wajib diisi.' }, { status: 422 });
    }

    const res: any = await query(
      `INSERT INTO work_shifts (name, start_time, end_time, late_tolerance_minutes, is_active, created_at, updated_at)
       VALUES (?, ?, ?, ?, ?, NOW(), NOW())`,
      [name, start_time, end_time, late_tolerance_minutes || 15, is_active !== false ? 1 : 0]
    );

    return NextResponse.json({ success: true, message: 'Shift kerja berhasil ditambahkan.', data: { id: res.insertId } });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function PUT(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { id, name, start_time, end_time, late_tolerance_minutes, is_active } = await req.json();
    await query(
      `UPDATE work_shifts SET name = ?, start_time = ?, end_time = ?, late_tolerance_minutes = ?, is_active = ?, updated_at = NOW() WHERE id = ?`,
      [name, start_time, end_time, late_tolerance_minutes || 15, is_active ? 1 : 0, id]
    );
    return NextResponse.json({ success: true, message: 'Shift kerja berhasil diperbarui.' });
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

    await query('DELETE FROM work_shifts WHERE id = ?', [id]);
    return NextResponse.json({ success: true, message: 'Shift kerja berhasil dihapus.' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

