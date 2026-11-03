import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';

export async function POST(req: NextRequest) {
  try {
    const data = await req.json();

    const {
      nama_pemesan,
      no_wa,
      email,
      nama_tim,
      paket_id,
      estimasi_qty,
      deadline,
      bahan,
      catatan_pesanan,
    } = data;

    if (!nama_pemesan || !no_wa || !paket_id || !estimasi_qty || !deadline) {
      return NextResponse.json(
        {
          success: false,
          message: 'Nama pemesan, No. WhatsApp, Paket, Estimasi Qty, dan Deadline wajib diisi.',
        },
        { status: 422 }
      );
    }

    // 1. Find or create customer
    let customer = await queryOne<any>(
      'SELECT id, company_name, phone FROM customers WHERE phone = ? LIMIT 1',
      [no_wa]
    );

    if (!customer) {
      const custResult: any = await query(
        `INSERT INTO customers (company_name, contact_person, phone, email, created_at, updated_at)
         VALUES (?, ?, ?, ?, NOW(), NOW())`,
        [nama_tim || nama_pemesan, nama_pemesan, no_wa, email || null]
      );
      customer = { id: custResult.insertId, company_name: nama_tim || nama_pemesan, phone: no_wa };
    }

    // 2. Generate Auto Code: ORD-YYYYMM-XXXX
    const now = new Date();
    const yearMonth = `${now.getFullYear()}${String(now.getMonth() + 1).padStart(2, '0')}`;
    const prefix = `ORD-${yearMonth}-`;

    const countRow = await queryOne<any>(
      'SELECT COUNT(*) as total FROM orders WHERE kode_order LIKE ?',
      [`${prefix}%`]
    );
    const nextSeq = (countRow?.total || 0) + 1;
    const kodeOrder = `${prefix}${String(nextSeq).padStart(4, '0')}`;

    const qty = Number(estimasi_qty) || 1;
    const totalTagihan = qty * 120000; // default estimated price

    // 3. Insert Order
    const insertResult: any = await query(
      `INSERT INTO orders (
        kode_order, customer_id, nama_team, bahan, tanggal_masuk, deadline,
        package_id, qty_esti, qty_tink, total_tagihan, dp, sisa_tagihan,
        status_pembayaran, status_produksi, source, lokasi_produksi, catatan, created_at, updated_at
      ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, 0, ?, 0, ?, 'Belum DP', 'Antrian', 'Landing Page', 'Kantor Produksi', ?, NOW(), NOW())`,
      [
        kodeOrder,
        customer.id,
        nama_tim || nama_pemesan,
        bahan || 'Dryfit',
        deadline,
        paket_id,
        qty,
        totalTagihan,
        totalTagihan,
        catatan_pesanan || '',
      ]
    );

    const orderId = insertResult.insertId;

    // 4. Initialize production progress
    await query(
      `INSERT INTO production_progress (order_id, print_status, press_status, pola_status, jahit_status, finish_status, created_at, updated_at)
       VALUES (?, 0, 0, 0, 0, 0, NOW(), NOW())`,
      [orderId]
    );

    // Dynamic Admin WhatsApp Number
    const waSetting = await queryOne<any>('SELECT value FROM settings WHERE `key` = "whatsapp_number"');
    const adminPhone = waSetting?.value || '6281234567890';
    const waMessage = encodeURIComponent(
      `Halo Admin Eighteen, saya telah mengisi formulir custom order dengan Nomor Pesanan: *${kodeOrder}* untuk Tim: *${nama_tim || nama_pemesan}*. Mohon konfirmasinya. Terima kasih.`
    );
    const whatsappUrl = `https://wa.me/${adminPhone}?text=${waMessage}`;

    return NextResponse.json({
      success: true,
      message: 'Pesanan custom berhasil dikirim!',
      order_number: kodeOrder,
      whatsapp_url: whatsappUrl,
      redirect_url: `/custom-order/success?order_number=${kodeOrder}`,
    });
  } catch (error: any) {
    console.error('Custom order error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

