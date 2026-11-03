import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const invoices = await query<any>(
      `SELECT i.*, o.kode_order, o.nama_team, c.company_name as customer_name,
              COALESCE(SUM(p.amount), 0) as total_paid
       FROM invoices i
       JOIN orders o ON i.order_id = o.id
       JOIN customers c ON o.customer_id = c.id
       LEFT JOIN payments p ON i.id = p.invoice_id
       GROUP BY i.id
       ORDER BY i.id DESC`
    );

    return NextResponse.json({ success: true, data: invoices });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });

    const { order_id } = await req.json();

    const order = await queryOne<any>('SELECT * FROM orders WHERE id = ?', [order_id]);
    if (!order) return NextResponse.json({ success: false, message: 'Order tidak ditemukan' }, { status: 404 });

    // Check existing
    const existing = await queryOne<any>('SELECT * FROM invoices WHERE order_id = ?', [order_id]);
    if (existing) {
      return NextResponse.json({ success: true, message: 'Invoice sudah ada.', data: existing });
    }

    const now = new Date();
    const yearMonth = `${now.getFullYear()}${String(now.getMonth() + 1).padStart(2, '0')}`;
    const prefix = `INV-${yearMonth}-`;

    const countRow = await queryOne<any>('SELECT COUNT(*) as total FROM invoices WHERE invoice_number LIKE ?', [`${prefix}%`]);
    const nextSeq = (countRow?.total || 0) + 1;
    const invoiceNumber = `${prefix}${String(nextSeq).padStart(4, '0')}`;

    const dueDate = new Date();
    dueDate.setDate(dueDate.getDate() + 14);

    const res: any = await query(
      `INSERT INTO invoices (order_id, invoice_number, invoice_date, due_date, total_amount, status, created_at, updated_at)
       VALUES (?, ?, NOW(), ?, ?, 'unpaid', NOW(), NOW())`,
      [order_id, invoiceNumber, dueDate.toISOString().split('T')[0], order.total_tagihan]
    );

    return NextResponse.json({
      success: true,
      message: 'Faktur invoice berhasil diterbitkan.',
      data: { id: res.insertId, invoice_number: invoiceNumber },
    });
  } catch (error: any) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

