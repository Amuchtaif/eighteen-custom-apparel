export function formatRupiah(value: number | string): string {
  const num = typeof value === 'string' ? parseFloat(value) || 0 : value;
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(num);
}

export function formatDate(dateString?: string | null): string {
  if (!dateString) return '-';
  try {
    const d = new Date(dateString);
    if (isNaN(d.getTime())) return dateString;
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
    }).format(d);
  } catch {
    return dateString;
  }
}

export interface DeadlineCalculation {
  daysLeft: number;
  text: string;
  label: string;
  tier: 'blue' | 'yellow' | 'orange' | 'red';
  badgeClass: string;
}

export function calculateDeadlineInfo(deadlineString?: string | null): DeadlineCalculation {
  if (!deadlineString) {
    return {
      daysLeft: 999,
      text: '-',
      label: '-',
      tier: 'blue',
      badgeClass: 'bg-blue-50 text-blue-700 border-blue-200',
    };
  }

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const deadline = new Date(deadlineString);
  deadline.setHours(0, 0, 0, 0);

  const diffTime = deadline.getTime() - today.getTime();
  const daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  let text = '';
  let tier: 'blue' | 'yellow' | 'orange' | 'red' = 'blue';
  let badgeClass = '';

  if (daysLeft < 0) {
    tier = 'red';
    text = `Terlambat ${Math.abs(daysLeft)} Hari`;
    badgeClass = 'bg-red-50 text-red-700 border-red-200 animate-pulse';
  } else if (daysLeft === 0) {
    tier = 'red';
    text = 'Hari Ini';
    badgeClass = 'bg-red-50 text-red-700 border-red-200 font-bold';
  } else if (daysLeft === 1) {
    tier = 'orange';
    text = 'Besok';
    badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
  } else if (daysLeft <= 3) {
    tier = 'orange';
    text = `${daysLeft} Hari Lagi`;
    badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
  } else if (daysLeft <= 7) {
    tier = 'yellow';
    text = `${daysLeft} Hari Lagi`;
    badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
  } else {
    tier = 'blue';
    text = `${daysLeft} Hari Lagi`;
    badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
  }

  return { daysLeft, text, label: text, tier, badgeClass };
}

export function calculateDistanceHaversine(
  lat1: number,
  lon1: number,
  lat2: number,
  lon2: number
): number {
  const R = 6371000; // in meters
  const dLat = ((lat2 - lat1) * Math.PI) / 180;
  const dLon = ((lon2 - lon1) * Math.PI) / 180;
  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos((lat1 * Math.PI) / 180) *
      Math.cos((lat2 * Math.PI) / 180) *
      Math.sin(dLon / 2) *
      Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return Math.round(R * c);
}
