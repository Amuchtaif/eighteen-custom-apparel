import { NextRequest, NextResponse } from 'next/server';
import { query, queryOne } from '@/lib/db';
import { getSessionFromRequest } from '@/lib/auth';
import { calculateDistanceHaversine } from '@/lib/utils';

export async function POST(req: NextRequest) {
  try {
    const session = getSessionFromRequest(req);
    if (!session) {
      return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });
    }

    const { latitude, longitude, action, notes, office_id, selfie_data } = await req.json();

    if (latitude === undefined || longitude === undefined || !action) {
      return NextResponse.json(
        { success: false, message: 'Koordinat GPS dan aksi absensi wajib disertakan.' },
        { status: 422 }
      );
    }

    // Find employee linked to this user
    let employee = await queryOne<any>(
      `SELECT e.*, o.name as office_name, o.latitude as office_lat, o.longitude as office_lng, o.radius_meter,
              ws.name as shift_name, ws.start_time, ws.end_time, ws.late_tolerance_minutes
       FROM employees e
       LEFT JOIN offices o ON e.office_id = o.id
       LEFT JOIN work_shifts ws ON e.work_shift_id = ws.id
       WHERE e.user_id = ?`,
      [session.user.id]
    );

    if (!employee) {
      // If user is super admin or admin without employee record, pick or create default
      let defaultOffice = await queryOne<any>('SELECT * FROM offices WHERE is_active = 1 LIMIT 1');
      let defaultShift = await queryOne<any>('SELECT * FROM work_shifts WHERE is_active = 1 LIMIT 1');

      const empResult: any = await query(
        `INSERT INTO employees (user_id, employee_code, role_id, office_id, work_shift_id, join_date, status, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, NOW(), 'active', NOW(), NOW())`,
        [
          session.user.id,
          `EMP-DEV-${session.user.id}`,
          session.user.role_id,
          defaultOffice?.id || 1,
          defaultShift?.id || 1,
        ]
      );
      employee = await queryOne<any>(
        `SELECT e.*, o.name as office_name, o.latitude as office_lat, o.longitude as office_lng, o.radius_meter,
                ws.name as shift_name, ws.start_time, ws.end_time, ws.late_tolerance_minutes
         FROM employees e
         LEFT JOIN offices o ON e.office_id = o.id
         LEFT JOIN work_shifts ws ON e.work_shift_id = ws.id
         WHERE e.id = ?`,
        [empResult.insertId]
      );
    }

    // Determine target office
    let targetOffice = employee;
    if (office_id && office_id !== employee.office_id) {
      const customOffice = await queryOne<any>('SELECT * FROM offices WHERE id = ?', [office_id]);
      if (customOffice) {
        targetOffice = {
          ...employee,
          office_lat: customOffice.latitude,
          office_lng: customOffice.longitude,
          radius_meter: customOffice.radius_meter,
          office_name: customOffice.name,
        };
      }
    }

    const officeLat = Number(targetOffice.office_lat || -6.2);
    const officeLng = Number(targetOffice.office_lng || 106.8);
    const radius = Number(targetOffice.radius_meter || 100);

    const distance = calculateDistanceHaversine(
      Number(latitude),
      Number(longitude),
      officeLat,
      officeLng
    );

    // Geofencing verification
    if (distance > radius) {
      return NextResponse.json(
        {
          success: false,
          message: `Di luar radius kantor ${targetOffice.office_name || 'Eighteen'} (${distance} meter). Maksimal radius: ${radius} meter.`,
          distance,
          radius,
        },
        { status: 422 }
      );
    }

    const today = new Date().toISOString().split('T')[0];

    if (action === 'check_in') {
      const existing = await queryOne<any>(
        'SELECT id FROM attendances WHERE employee_id = ? AND DATE(check_in_time) = ?',
        [employee.id, today]
      );

      if (existing) {
        return NextResponse.json(
          { success: false, message: 'Anda sudah melakukan absen masuk hari ini.' },
          { status: 400 }
        );
      }

      // Check late status against shift start time + tolerance
      let status = 'Hadir';
      if (employee.start_time) {
        const now = new Date();
        const [shiftHour, shiftMin] = employee.start_time.split(':').map(Number);
        const shiftStartDate = new Date();
        shiftStartDate.setHours(shiftHour, shiftMin, 0, 0);

        const toleranceMinutes = Number(employee.late_tolerance_minutes || 15);
        shiftStartDate.setMinutes(shiftStartDate.getMinutes() + toleranceMinutes);

        if (now > shiftStartDate) {
          status = 'Terlambat';
        }
      }

      await query(
        `INSERT INTO attendances (
          employee_id, office_id, work_shift_id, check_in_time, check_in_latitude, check_in_longitude,
          check_in_distance, check_in_photo, attendance_status, notes, created_at, updated_at
        ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
        [
          employee.id,
          employee.office_id,
          employee.work_shift_id,
          latitude,
          longitude,
          distance,
          selfie_data ? 'selfie_captured' : null,
          status,
          notes || '',
        ]
      );

      return NextResponse.json({
        success: true,
        message: `Absen masuk berhasil tercatat (${status}). Jarak: ${distance}m.`,
        status,
        distance,
      });
    } else {
      // check_out
      const todayAttendance = await queryOne<any>(
        'SELECT id, check_out_time FROM attendances WHERE employee_id = ? AND DATE(check_in_time) = ? ORDER BY id DESC LIMIT 1',
        [employee.id, today]
      );

      if (!todayAttendance) {
        return NextResponse.json(
          { success: false, message: 'Anda belum melakukan absen masuk hari ini.' },
          { status: 400 }
        );
      }

      if (todayAttendance.check_out_time) {
        return NextResponse.json(
          { success: false, message: 'Anda sudah melakukan absen keluar hari ini.' },
          { status: 400 }
        );
      }

      await query(
        `UPDATE attendances SET
          check_out_time = NOW(),
          check_out_latitude = ?,
          check_out_longitude = ?,
          check_out_distance = ?,
          check_out_photo = ?,
          updated_at = NOW()
         WHERE id = ?`,
        [latitude, longitude, distance, selfie_data ? 'selfie_out_captured' : null, todayAttendance.id]
      );

      return NextResponse.json({
        success: true,
        message: `Absen keluar berhasil dicatat. Jarak: ${distance}m. Selamat beristirahat!`,
        distance,
      });
    }
  } catch (error: any) {
    console.error('Attendance error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

