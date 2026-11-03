import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';

export async function POST(req: NextRequest, { params }: { params: { id: string } }) {
  try {
    const session = getSessionFromRequest(req);
    const userId = session?.user?.id || 1;
    const orderId = Number(params.id);

    const body = await req.json();
    const stage = (body.stage || '').toLowerCase(); // print, press, pola, jahit, finish
    const status = Boolean(body.status);

    const validStages = ['print', 'press', 'pola', 'jahit', 'finish'];
    if (!validStages.includes(stage)) {
      return NextResponse.json(
        { success: false, message: 'Tahapan produksi tidak valid.' },
        { status: 400 }
      );
    }

    // Ensure progress record exists
    let progress = await queryOne<any>(
      'SELECT * FROM production_progress WHERE order_id = ?',
      [orderId]
    );

    if (!progress) {
      await query(
        `INSERT INTO production_progress (order_id, print_status, press_status, pola_status, jahit_status, finish_status, created_at, updated_at)
         VALUES (?, 0, 0, 0, 0, 0, NOW(), NOW())`,
        [orderId]
      );
      progress = await queryOne<any>(
        'SELECT * FROM production_progress WHERE order_id = ?',
        [orderId]
      );
    }

    const columnStatus = `${stage}_status`;
    const columnAt = `${stage}_at`;
    const atValue = status ? new Date().toISOString().slice(0, 19).replace('T', ' ') : null;

    // Update progress table
    await query(
      `UPDATE production_progress SET ${columnStatus} = ?, ${columnAt} = ?, updated_at = NOW() WHERE order_id = ?`,
      [status ? 1 : 0, atValue, orderId]
    );

    // Fetch updated progress
    const updatedProgress = await queryOne<any>(
      'SELECT * FROM production_progress WHERE order_id = ?',
      [orderId]
    );

    // Determine new status_produksi
    let newStatus = 'Antrian';
    if (updatedProgress.finish_status) {
      newStatus = 'Selesai';
    } else if (updatedProgress.jahit_status) {
      newStatus = 'Finish';
    } else if (updatedProgress.pola_status) {
      newStatus = 'Jahit';
    } else if (updatedProgress.press_status) {
      newStatus = 'Pola';
    } else if (updatedProgress.print_status) {
      newStatus = 'Press';
    }

    await query(
      'UPDATE orders SET status_produksi = ?, updated_at = NOW() WHERE id = ?',
      [newStatus, orderId]
    );

    // Log to production_progress_histories
    const stageNames: Record<string, string> = {
      print: 'Print',
      press: 'Press',
      pola: 'Pola',
      jahit: 'Jahit',
      finish: 'Finish',
    };
    const stageLabel = stageNames[stage] || stage;

    await query(
      `INSERT INTO production_progress_histories (order_id, user_id, stage, status, notes, created_at, updated_at)
       VALUES (?, ?, ?, ?, ?, NOW(), NOW())`,
      [
        orderId,
        userId,
        stageLabel,
        status ? 1 : 0,
        `Tahapan ${stageLabel} diubah menjadi ${status ? 'Selesai' : 'Belum Selesai'}`,
      ]
    );

    // Calculate percentage
    const stages = [
      updatedProgress.print_status,
      updatedProgress.press_status,
      updatedProgress.pola_status,
      updatedProgress.jahit_status,
      updatedProgress.finish_status,
    ];
    const completedCount = stages.filter(Boolean).length;
    const percentage = Math.round((completedCount / 5) * 100);

    return NextResponse.json({
      success: true,
      message: `Tahapan ${stageLabel} berhasil diperbarui.`,
      progress: updatedProgress,
      order_status: newStatus,
      percentage,
      completed_count: completedCount,
      total_count: 5,
    });
  } catch (error: any) {
    console.error('Toggle progress error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

