import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const orderId = params.id;
    const order = await queryOne<any>(
      `SELECT o.*, c.company_name as customer_name, c.phone as customer_phone, p.name as package_name,
              pp.print_status, pp.press_status, pp.pola_status, pp.jahit_status, pp.finish_status
       FROM orders o
       LEFT JOIN customers c ON o.customer_id = c.id
       LEFT JOIN packages p ON o.package_id = p.id
       LEFT JOIN production_progress pp ON o.id = pp.order_id
       WHERE o.id = ?`,
      [orderId]
    );

    if (!order) {
      return NextResponse.json({ success: false, message: 'Pesanan tidak ditemukan' }, { status: 404 });
    }

    return NextResponse.json({ success: true, order });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function PUT(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const orderId = params.id;
    const data = await req.json();

    const totalTagihan = Number(data.total_tagihan) || 0;
    const dp = Number(data.dp) || 0;
    const sisaTagihan = Math.max(0, totalTagihan - dp);
    const statusPembayaran = dp >= totalTagihan ? 'Lunas' : dp > 0 ? 'DP' : 'Belum DP';

    await query(
      `UPDATE orders SET
        nama_team = ?,
        bahan = ?,
        deadline = ?,
        package_id = ?,
        qty_esti = ?,
        total_tagihan = ?,
        dp = ?,
        sisa_tagihan = ?,
        status_pembayaran = ?,
        lokasi_produksi = ?,
        designer_id = ?,
        operator_print_id = ?,
        penjahit_id = ?,
        qc_id = ?,
        catatan = ?,
        updated_at = NOW()
       WHERE id = ?`,
      [
        data.nama_team,
        data.bahan,
        data.deadline,
        data.package_id,
        data.qty_esti,
        totalTagihan,
        dp,
        sisaTagihan,
        statusPembayaran,
        data.lokasi_produksi,
        data.designer_id || null,
        data.operator_print_id || null,
        data.penjahit_id || null,
        data.qc_id || null,
        data.catatan || '',
        orderId,
      ]
    );

    return NextResponse.json({ success: true, message: 'Pesanan berhasil diperbarui.' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function DELETE(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const orderId = params.id;

    // Delete child rows first
    await query('DELETE FROM production_progress_histories WHERE order_id = ?', [orderId]);
    await query('DELETE FROM production_progress WHERE order_id = ?', [orderId]);
    await query('DELETE FROM order_players WHERE order_id = ?', [orderId]);
    await query('DELETE FROM order_attachments WHERE order_id = ?', [orderId]);
    await query('DELETE FROM orders WHERE id = ?', [orderId]);

    return NextResponse.json({ success: true, message: 'Pesanan berhasil dihapus.' });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

