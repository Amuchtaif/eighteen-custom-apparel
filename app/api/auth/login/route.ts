import { NextRequest, NextResponse } from 'next/server';
import bcrypt from 'bcryptjs';
import { queryOne } from '@/lib/db';
import { signToken, getDashboardRedirect } from '@/lib/auth';

export async function POST(req: NextRequest) {
  try {
    const { email, password } = await req.json();

    if (!email || !password) {
      return NextResponse.json(
        { success: false, message: 'Email dan password wajib diisi.' },
        { status: 400 }
      );
    }

    // Find user with role
    const user = await queryOne<any>(
      `SELECT u.*, r.name as role_name 
       FROM users u 
       JOIN roles r ON u.role_id = r.id 
       WHERE u.email = ? AND u.is_active = 1`,
      [email]
    );

    if (!user) {
      return NextResponse.json(
        { success: false, message: 'Email atau password salah.' },
        { status: 401 }
      );
    }

    // Check customer restriction (task-customer-login-refaktor.md: customer login is completely disabled)
    const roleLower = (user.role_name || '').toLowerCase();
    if (roleLower === 'customer') {
      return NextResponse.json(
        { success: false, message: 'Akses tidak diizinkan. Pelanggan tidak memiliki akses login.' },
        { status: 403 }
      );
    }

    // Verify password
    const isMatch = await bcrypt.compare(password, user.password);
    if (!isMatch) {
      return NextResponse.json(
        { success: false, message: 'Email atau password salah.' },
        { status: 401 }
      );
    }

    const payload = {
      id: user.id,
      name: user.name,
      email: user.email,
      role_id: user.role_id,
      role_name: user.role_name,
    };

    const token = signToken(payload);
    const redirectUrl = getDashboardRedirect(user.role_name);

    const response = NextResponse.json({
      success: true,
      message: 'Login berhasil.',
      user: payload,
      redirectUrl,
    });

    response.cookies.set('auth_token', token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'lax',
      path: '/',
      maxAge: 7 * 24 * 60 * 60, // 7 days
    });

    return response;
  } catch (error: any) {
    console.error('Login error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Terjadi kesalahan pada server.' },
      { status: 500 }
    );
  }
}

