import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    const userId = session?.user?.id || 1;

    const { order_id, stage } = await req.json();

    if (!order_id || !stage) {
      return NextResponse.json({ success: false, message: 'Invalid payload' }, { status: 400 });
    }

    // Update order status
    await query('UPDATE orders SET status_produksi = ?, updated_at = NOW() WHERE id = ?', [
      stage,
      order_id,
    ]);

    // Update progress flags based on stage
    const stageMapping: Record<string, string[]> = {
      Antrian: [],
      Print: ['print_status'],
      Press: ['print_status', 'press_status'],
      Pola: ['print_status', 'press_status', 'pola_status'],
      Jahit: ['print_status', 'press_status', 'pola_status', 'jahit_status'],
      Finish: ['print_status', 'press_status', 'pola_status', 'jahit_status', 'finish_status'],
      'Siap Kirim': ['print_status', 'press_status', 'pola_status', 'jahit_status', 'finish_status'],
      Selesai: ['print_status', 'press_status', 'pola_status', 'jahit_status', 'finish_status'],
    };

    const activeFlags = stageMapping[stage] || [];

    // Ensure progress row exists
    await query(
      `INSERT INTO production_progress (order_id, print_status, press_status, pola_status, jahit_status, finish_status, created_at, updated_at)
       VALUES (?, 0, 0, 0, 0, 0, NOW(), NOW())
       ON DUPLICATE KEY UPDATE updated_at = NOW()`,
      [order_id]
    );

    const updateFields = [
      `print_status = ${activeFlags.includes('print_status') ? 1 : 0}`,
      `press_status = ${activeFlags.includes('press_status') ? 1 : 0}`,
      `pola_status = ${activeFlags.includes('pola_status') ? 1 : 0}`,
      `jahit_status = ${activeFlags.includes('jahit_status') ? 1 : 0}`,
      `finish_status = ${activeFlags.includes('finish_status') ? 1 : 0}`,
    ];

    await query(
      `UPDATE production_progress SET ${updateFields.join(', ')}, updated_at = NOW() WHERE order_id = ?`,
      [order_id]
    );

    // Audit log
    await query(
      `INSERT INTO production_progress_histories (order_id, user_id, stage, status, notes, created_at, updated_at)
       VALUES (?, ?, ?, 1, ?, NOW(), NOW())`,
      [order_id, userId, stage, `Pesanan dipindahkan ke tahapan ${stage} via Kanban Board`]
    );

    return NextResponse.json({
      success: true,
      message: `Pesanan berhasil dipindahkan ke tahapan ${stage}.`,
    });
  } catch (error: any) {
    console.error('Kanban update error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

