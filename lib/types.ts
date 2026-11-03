export type RoleName = 
  | 'Super Admin' 
  | 'Owner' 
  | 'Admin' 
  | 'Sales' 
  | 'Production' 
  | 'Produksi' 
  | 'HRD' 
  | 'Finance' 
  | 'Keuangan';

export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  role_id: number;
  role_name?: string;
  is_active: boolean;
  created_at?: string;
}

export interface AuthSession {
  user: {
    id: number;
    name: string;
    email: string;
    role_id: number;
    role_name: string;
  };
}

export interface Customer {
  id: number;
  company_name: string;
  contact_person?: string;
  phone: string;
  email?: string;
  address?: string;
  city?: string;
  notes?: string;
}

export interface Package {
  id: number;
  name: string;
  description?: string;
  is_active: boolean;
}

export interface Material {
  id: number;
  name: string;
  description?: string;
}

export interface Order {
  id: number;
  kode_order: string;
  customer_id: number;
  customer_name?: string;
  customer_phone?: string;
  nama_team: string;
  bahan: string;
  tanggal_masuk: string;
  deadline: string;
  package_id: number;
  package_name?: string;
  qty_esti: number;
  qty_tink: number;
  total_tagihan: number;
  dp: number;
  sisa_tagihan: number;
  status_pembayaran: string;
  status_produksi: string;
  source: string;
  lokasi_produksi: string;
  estimasi_selesai?: string;
  designer_id?: number;
  designer_name?: string;
  operator_print_id?: number;
  operator_print_name?: string;
  penjahit_id?: number;
  penjahit_name?: string;
  qc_id?: number;
  qc_name?: string;
  catatan?: string;
  notes_html?: string;
  print_status?: boolean;
  press_status?: boolean;
  pola_status?: boolean;
  jahit_status?: boolean;
  finish_status?: boolean;
  sisa_hari?: number;
  sisa_hari_text?: string;
  deadline_tier?: 'blue' | 'yellow' | 'orange' | 'red';
  progress_percent?: number;
  completed_stages_count?: number;
}

export interface Office {
  id: number;
  name: string;
  address?: string;
  latitude: number;
  longitude: number;
  radius_meter: number;
  is_active: boolean;
}

export interface WorkShift {
  id: number;
  name: string;
  start_time: string;
  end_time: string;
  late_tolerance_minutes: number;
  is_active: boolean;
}

export interface Attendance {
  id: number;
  employee_id: number;
  employee_code?: string;
  employee_name?: string;
  office_id?: number;
  office_name?: string;
  work_shift_id?: number;
  work_shift_name?: string;
  check_in_time: string;
  check_in_distance?: number;
  check_in_photo?: string;
  check_out_time?: string;
  check_out_distance?: number;
  check_out_photo?: string;
  attendance_status: 'Hadir' | 'Terlambat' | 'Izin' | 'Sakit' | 'Alpha';
  notes?: string;
}

