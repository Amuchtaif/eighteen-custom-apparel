import jwt from 'jsonwebtoken';
import { cookies } from 'next/headers';
import { NextRequest } from 'next/server';
import { AuthSession } from './types';

const JWT_SECRET = process.env.JWT_SECRET || 'super-secret-jwt-key-eighteen-apparel-2026';
const COOKIE_NAME = 'auth_token';

export function signToken(payload: AuthSession['user']): string {
  return jwt.sign(payload, JWT_SECRET, { expiresIn: '7d' });
}

export function verifyToken(token: string): AuthSession['user'] | null {
  try {
    return jwt.verify(token, JWT_SECRET) as AuthSession['user'];
  } catch {
    return null;
  }
}

export async function getSession(): Promise<AuthSession | null> {
  const cookieStore = cookies();
  const token = cookieStore.get(COOKIE_NAME)?.value;
  if (!token) return null;

  const user = verifyToken(token);
  if (!user) return null;

  return { user };
}

export function getSessionFromRequest(req: NextRequest): AuthSession | null {
  const token = req.cookies.get(COOKIE_NAME)?.value;
  if (!token) return null;

  const user = verifyToken(token);
  if (!user) return null;

  return { user };
}

export function getDashboardRedirect(roleName?: string): string {
  if (!roleName) return '/login';
  const r = roleName.toLowerCase();
  if (r === 'owner' || r === 'super admin') {
    return '/dashboard';
  }
  return '/employee/dashboard';
}

export function isAllowedRole(roleName: string, allowedRoles: string[]): boolean {
  const r = roleName.toLowerCase();
  return allowedRoles.some((ar) => ar.toLowerCase() === r);
}
