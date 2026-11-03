import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';
import { calculateDeadlineInfo } from '@/lib/utils';

export async function GET(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) {
      return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });
    }

    const { searchParams } = new URL(req.url);
    const search = searchParams.get('search') || '';
    const tanggalMasuk = searchParams.get('tanggal_masuk') || '';
    const deadline = searchParams.get('deadline') || '';
    const packageId = searchParams.get('package_id') || '';
    const statusProduksi = searchParams.get('status_produksi') || '';
    const bahan = searchParams.get('bahan') || '';
    const customerId = searchParams.get('customer_id') || '';
    const lokasiProduksi = searchParams.get('lokasi_produksi') || '';

    // Build WHERE clause
    const conditions: string[] = ['1=1'];
    const params: any[] = [];

    if (search) {
      conditions.push('(o.kode_order LIKE ? OR o.nama_team LIKE ? OR c.company_name LIKE ?)');
      const wild = `%${search}%`;
      params.push(wild, wild, wild);
    }
    if (tanggalMasuk) {
      conditions.push('DATE(o.tanggal_masuk) = ?');
      params.push(tanggalMasuk);
    }
    if (deadline) {
      conditions.push('DATE(o.deadline) = ?');
      params.push(deadline);
    }
    if (packageId) {
      conditions.push('o.package_id = ?');
      params.push(packageId);
    }
    if (statusProduksi) {
      conditions.push('o.status_produksi = ?');
      params.push(statusProduksi);
    }
    if (bahan) {
      conditions.push('o.bahan LIKE ?');
      params.push(`%${bahan}%`);
    }
    if (customerId) {
      conditions.push('o.customer_id = ?');
      params.push(customerId);
    }
    if (lokasiProduksi) {
      conditions.push('o.lokasi_produksi = ?');
      params.push(lokasiProduksi);
    }

    const whereSql = conditions.join(' AND ');

    // Fetch Orders
    const ordersSql = `
      SELECT 
        o.*,
        c.company_name as customer_name,
        c.phone as customer_phone,
        p.name as package_name,
        pp.print_status, pp.print_at,
        pp.press_status, pp.press_at,
        pp.pola_status, pp.pola_at,
        pp.jahit_status, pp.jahit_at,
        pp.finish_status, pp.finish_at,
        u_des.name as designer_name,
        u_prt.name as operator_print_name,
        u_jht.name as penjahit_name,
        u_qc.name as qc_name
      FROM orders o
      LEFT JOIN customers c ON o.customer_id = c.id
      LEFT JOIN packages p ON o.package_id = p.id
      LEFT JOIN production_progress pp ON o.id = pp.order_id
      LEFT JOIN users u_des ON o.designer_id = u_des.id
      LEFT JOIN users u_prt ON o.operator_print_id = u_prt.id
      LEFT JOIN users u_jht ON o.penjahit_id = u_jht.id
      LEFT JOIN users u_qc ON o.qc_id = u_qc.id
      WHERE ${whereSql}
      ORDER BY o.id DESC
    `;

    const rawOrders = await query<any>(ordersSql, params);

    // Compute deadline info, progress percentages
    const processedOrders = rawOrders.map((o) => {
      const deadlineInfo = calculateDeadlineInfo(o.deadline);
      const stages = [o.print_status, o.press_status, o.pola_status, o.jahit_status, o.finish_status];
      const completedCount = stages.filter(Boolean).length;
      const progressPercent = Math.round((completedCount / 5) * 100);

      return {
        ...o,
        print_status: Boolean(o.print_status),
        press_status: Boolean(o.press_status),
        pola_status: Boolean(o.pola_status),
        jahit_status: Boolean(o.jahit_status),
        finish_status: Boolean(o.finish_status),
        sisa_hari: deadlineInfo.daysLeft,
        sisa_hari_text: deadlineInfo.text,
        deadline_tier: deadlineInfo.tier,
        completed_stages_count: completedCount,
        progress_percent: progressPercent,
      };
    });

    // Summary Widgets calculation (TASK.md: 6 KPI metrics)
    const today = new Date().toISOString().split('T')[0];

    const [qtyRow] = await query<any>('SELECT COALESCE(SUM(qty_esti), 0) as total_qty FROM orders');
    const [activeRow] = await query<any>(`SELECT COUNT(*) as total_active FROM orders WHERE status_produksi != 'Selesai'`);
    const [inProdRow] = await query<any>(
      `SELECT COUNT(*) as sedang_produksi FROM orders WHERE status_produksi IN ('Print', 'Press', 'Pola', 'Jahit', 'Finish', 'Siap Kirim', 'Proses Produksi')`
    );
    const [doneRow] = await query<any>(`SELECT COUNT(*) as selesai FROM orders WHERE status_produksi = 'Selesai'`);
    const [lateRow] = await query<any>(
      `SELECT COUNT(*) as terlambat FROM orders WHERE status_produksi != 'Selesai' AND DATE(deadline) < ?`,
      [today]
    );
    const [premiumRow] = await query<any>(
      `SELECT COUNT(*) as premium FROM orders o JOIN packages p ON o.package_id = p.id WHERE p.name LIKE '%Premium%'`
    );

    const summary = {
      total_qty: Number(qtyRow?.total_qty || 0),
      total_active: Number(activeRow?.total_active || 0),
      sedang_produksi: Number(inProdRow?.sedang_produksi || 0),
      selesai: Number(doneRow?.selesai || 0),
      terlambat: Number(lateRow?.terlambat || 0),
      premium: Number(premiumRow?.premium || 0),
    };

    // Metadata for dropdowns
    const packages = await query('SELECT id, name FROM packages WHERE is_active = 1 ORDER BY name');
    const customers = await query('SELECT id, company_name FROM customers ORDER BY company_name');
    const materials = await query('SELECT id, name FROM materials ORDER BY name');
    const staffUsers = await query('SELECT id, name, role_id FROM users WHERE is_active = 1 ORDER BY name');

    return NextResponse.json({
      success: true,
      orders: processedOrders,
      summary,
      meta: {
        packages,
        customers,
        materials,
        locations: ['Kantor Produksi', 'Studio Desain'],
        staffUsers,
      },
    });
  } catch (error: any) {
    console.error('Fetch orders error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) {
      return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });
    }

    const data = await req.json();

    // Generate Auto Code: ORD-YYYYMM-XXXX
    const now = new Date();
    const yearMonth = `${now.getFullYear()}${String(now.getMonth() + 1).padStart(2, '0')}`;
    const prefix = `ORD-${yearMonth}-`;

    const countRow = await queryOne<any>(
      'SELECT COUNT(*) as total FROM orders WHERE kode_order LIKE ?',
      [`${prefix}%`]
    );
    const nextSeq = (countRow?.total || 0) + 1;
    const kodeOrder = `${prefix}${String(nextSeq).padStart(4, '0')}`;

    const totalTagihan = Number(data.total_tagihan) || (Number(data.qty_esti) || 1) * 100000;
    const dp = Number(data.dp) || 0;
    const sisaTagihan = Math.max(0, totalTagihan - dp);
    const statusPembayaran = dp >= totalTagihan ? 'Lunas' : dp > 0 ? 'DP' : 'Belum DP';

    const insertResult: any = await query(
      `INSERT INTO orders (
        kode_order, customer_id, nama_team, bahan, tanggal_masuk, deadline,
        package_id, qty_esti, qty_tink, total_tagihan, dp, sisa_tagihan,
        status_pembayaran, status_produksi, source, lokasi_produksi,
        designer_id, operator_print_id, penjahit_id, qc_id, catatan, created_at, updated_at
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
      [
        kodeOrder,
        data.customer_id,
        data.nama_team,
        data.bahan || 'Dryfit',
        data.tanggal_masuk || now.toISOString().split('T')[0],
        data.deadline,
        data.package_id,
        data.qty_esti || 1,
        0,
        totalTagihan,
        dp,
        sisaTagihan,
        statusPembayaran,
        'Antrian',
        'Backoffice',
        data.lokasi_produksi || 'Kantor Produksi',
        data.designer_id || null,
        data.operator_print_id || null,
        data.penjahit_id || null,
        data.qc_id || null,
        data.catatan || '',
      ]
    );

    const orderId = insertResult.insertId;

    // Initialize production progress
    await query(
      `INSERT INTO production_progress (order_id, print_status, press_status, pola_status, jahit_status, finish_status, created_at, updated_at)
       VALUES (?, 0, 0, 0, 0, 0, NOW(), NOW())`,
      [orderId]
    );

    // Initial audit log
    await query(
      `INSERT INTO production_progress_histories (order_id, user_id, stage, status, notes, created_at, updated_at)
       VALUES (?, ?, 'Antrian', 1, 'Pesanan dibuat di sistem produksi', NOW(), NOW())`,
      [orderId, session.user.id]
    );

    return NextResponse.json({
      success: true,
      message: 'Pesanan produksi berhasil dibuat.',
      order_id: orderId,
      kode_order: kodeOrder,
    });
  } catch (error: any) {
    console.error('Create order error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

