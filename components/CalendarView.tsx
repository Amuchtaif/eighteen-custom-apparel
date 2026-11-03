'use client';

import React from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import { Order } from '@/lib/types';

interface CalendarViewProps {
  orders: Order[];
  onOrderClick?: (order: Order) => void;
}

export default function CalendarView({ orders, onOrderClick }: CalendarViewProps) {
  const events = orders
    .filter((o) => o.deadline)
    .map((o) => {
      let color = '#0284c7'; // blue
      if (o.status_produksi === 'Selesai') {
        color = '#10b981'; // green
      } else if (o.status_produksi === 'Jahit') {
        color = '#d97706'; // amber
      } else if (o.status_produksi === 'Pola') {
        color = '#ea580c'; // orange
      } else if (new Date(o.deadline) < new Date() && o.status_produksi !== 'Selesai') {
        color = '#ef4444'; // red overdue
      }

      return {
        id: o.id.toString(),
        title: `${o.kode_order} • ${o.nama_team} (${o.qty_esti} pcs)`,
        start: o.deadline,
        allDay: true,
        backgroundColor: color,
        borderColor: color,
        extendedProps: { order: o },
      };
    });

  return (
    <div className="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
      <FullCalendar
        plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
        initialView="dayGridMonth"
        headerToolbar={{
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek',
        }}
        events={events}
        eventClick={(info) => {
          if (onOrderClick && info.event.extendedProps.order) {
            onOrderClick(info.event.extendedProps.order);
          }
        }}
        height="auto"
        aspectRatio={1.8}
        locale="id"
        buttonText={{
          today: 'Hari Ini',
          month: 'Bulan',
          week: 'Minggu',
        }}
      />
    </div>
  );
}

