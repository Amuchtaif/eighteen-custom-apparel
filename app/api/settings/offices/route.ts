import { NextRequest, NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET() {
  try {
    const data = await query('SELECT * FROM offices ORDER BY id ASC');
    return NextResponse.json({ success: true, data });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { name, address, latitude, longitude, radius_meter, is_active } = await req.json();
    if (!name || latitude === undefined || longitude === undefined) {
      return NextResponse.json({ success: false, message: 'Nama, latitude, dan longitude kantor wajib diisi.' }, { status: 422 });
    }

    const res: any = await query(
      `INSERT INTO offices (name, address, latitude, longitude, radius_meter, is_active, created_at, updated_at)
       VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())`,
      [name, address || '', latitude, longitude, radius_meter || 100, is_active !== false ? 1 : 0]
    );

    return NextResponse.json({ success: true, message: 'Lokasi kantor berhasil ditambahkan.', data: { id: res.insertId } });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function PUT(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { id, name, address, latitude, longitude, radius_meter, is_active } = await req.json();
    await query(
      `UPDATE offices SET name = ?, address = ?, latitude = ?, longitude = ?, radius_meter = ?, is_active = ?, updated_at = NOW() WHERE id = ?`,
      [name, address || '', latitude, longitude, radius_meter || 100, is_active ? 1 : 0, id]
    );
    return NextResponse.json({ success: true, message: 'Lokasi kantor berhasil diperbarui.' });
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

    await query('DELETE FROM offices WHERE id = ?', [id]);
    return NextResponse.json({ success: true, message: 'Lokasi kantor berhasil dihapus.' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

