import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const payments = await query<any>(
      `SELECT p.*, i.invoice_number, i.total_amount, o.kode_order, o.nama_team, c.company_name as customer_name
       FROM payments p
       JOIN invoices i ON p.invoice_id = i.id
       JOIN orders o ON i.order_id = o.id
       JOIN customers c ON o.customer_id = c.id
       ORDER BY p.id DESC`
    );

    return NextResponse.json({ success: true, data: payments });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { invoice_id, amount, payment_method, reference_number, notes } = await req.json();

    const invoice = await queryOne<any>('SELECT * FROM invoices WHERE id = ?', [invoice_id]);
    if (!invoice) return NextResponse.json({ success: false, message: 'Invoice tidak ditemukan' }, { status: 404 });

    const payAmount = Number(amount) || 0;

    await query(
      `INSERT INTO payments (invoice_id, payment_date, amount, payment_method, reference_number, notes, created_at, updated_at)
       VALUES (?, NOW(), ?, ?, ?, ?, NOW(), NOW())`,
      [invoice_id, payAmount, payment_method || 'cash', reference_number || '', notes || '']
    );

    // Recalculate invoice status
    const [sumRow] = await query<any>('SELECT COALESCE(SUM(amount), 0) as total_paid FROM payments WHERE invoice_id = ?', [invoice_id]);
    const totalPaid = Number(sumRow?.total_paid || 0);

    let newStatus = 'unpaid';
    if (totalPaid >= Number(invoice.total_amount)) {
      newStatus = 'paid';
    } else if (totalPaid > 0) {
      newStatus = 'partially_paid';
    }

    await query('UPDATE invoices SET status = ?, updated_at = NOW() WHERE id = ?', [newStatus, invoice_id]);

    // Also update order status_pembayaran
    const orderStatus = newStatus === 'paid' ? 'Lunas' : totalPaid > 0 ? 'DP' : 'Belum DP';
    await query(
      'UPDATE orders SET dp = ?, sisa_tagihan = GREATEST(0, total_tagihan - ?), status_pembayaran = ?, updated_at = NOW() WHERE id = ?',
      [totalPaid, totalPaid, orderStatus, invoice.order_id]
    );

    return NextResponse.json({
      success: true,
      message: 'Pembayaran berhasil dicatat.',
      invoice_status: newStatus,
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

