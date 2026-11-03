import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const orderId = params.id;
    const order = await queryOne<any>(
      'SELECT id, kode_order, nama_team, catatan FROM orders WHERE id = ?',
      [orderId]
    );

    if (!order) {
      return NextResponse.json({ success: false, message: 'Pesanan tidak ditemukan' }, { status: 404 });
    }

    return NextResponse.json({
      success: true,
      order_id: order.id,
      kode_order: order.kode_order,
      nama_team: order.nama_team,
      notes_html: order.catatan || '',
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function PUT(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const orderId = params.id;
    const { notes_html } = await req.json();

    await query('UPDATE orders SET catatan = ?, updated_at = NOW() WHERE id = ?', [
      notes_html || '',
      orderId,
    ]);

    return NextResponse.json({
      success: true,
      message: 'Catatan produksi berhasil disimpan.',
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

