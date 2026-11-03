'use client';

import React, { useState } from 'react';
import {
  DndContext,
  closestCorners,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragEndEvent,
  DragStartEvent,
  DragOverlay,
  useDroppable,
} from '@dnd-kit/core';
import {
  SortableContext,
  verticalListSortingStrategy,
  useSortable,
} from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { Order } from '@/lib/types';
import { calculateDeadlineInfo, formatRupiah } from '@/lib/utils';
import { Clock, ShieldAlert, Sparkles, MapPin, CheckCircle2 } from 'lucide-react';

interface KanbanBoardProps {
  initialOrders: Order[];
  onStageChange: (orderId: number, newStage: string) => Promise<void>;
}

const STAGES = [
  { id: 'Antrian', label: 'Antrian Desain', color: 'border-slate-300 bg-slate-100/60' },
  { id: 'Print', label: 'Printing Sublim', color: 'border-blue-300 bg-blue-50/50' },
  { id: 'Press', label: 'Pemanas / Press', color: 'border-purple-300 bg-purple-50/50' },
  { id: 'Pola', label: 'Pemotongan Pola', color: 'border-orange-300 bg-orange-50/50' },
  { id: 'Jahit', label: 'Penjahitan', color: 'border-yellow-300 bg-yellow-50/50' },
  { id: 'Finish', label: 'Finishing & QC', color: 'border-emerald-300 bg-emerald-50/50' },
  { id: 'Siap Kirim', label: 'Siap Dikirim', color: 'border-teal-300 bg-teal-50/50' },
  { id: 'Selesai', label: 'Pesanan Selesai', color: 'border-slate-300 bg-slate-100/60' },
];

function KanbanCard({ order, isOverlay = false }: { order: Order; isOverlay?: boolean }) {
  const deadlineInfo = calculateDeadlineInfo(order.deadline);
  const isPremium = order.package_name?.toLowerCase().includes('premium');

  return (
    <div
      className={`p-3.5 bg-white rounded-xl border border-slate-200/80 shadow-sm hover:shadow-md transition cursor-grab active:cursor-grabbing ${
        isOverlay ? 'shadow-xl ring-2 ring-blue-500 scale-105 rotate-1' : ''
      }`}
    >
      <div className="flex items-center justify-between gap-2 mb-1.5">
        <span className="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
          {order.kode_order}
        </span>
        {isPremium && (
          <span className="inline-flex items-center gap-1 text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
            <Sparkles className="w-3 h-3 text-amber-500 fill-amber-500" />
            Premium
          </span>
        )}
      </div>

      <h4 className="text-sm font-bold text-slate-800 truncate mb-1">
        {order.nama_team}
      </h4>

      <p className="text-xs text-slate-500 truncate mb-2">
        {order.customer_name || 'Customer'} • <span className="font-medium text-slate-700">{order.bahan}</span>
      </p>

      {/* Badges info */}
      <div className="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-100 text-[11px]">
        {/* Deadline Badge */}
        <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded border text-[10px] font-bold ${deadlineInfo.badgeClass}`}>
          <Clock className="w-3 h-3" />
          {deadlineInfo.text}
        </span>

        {/* Location Badge */}
        {order.lokasi_produksi && (
          <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px]">
            <MapPin className="w-2.5 h-2.5 text-slate-400" />
            {order.lokasi_produksi}
          </span>
        )}

        <span className="ml-auto text-xs font-bold text-blue-700">
          {order.qty_esti} pcs
        </span>
      </div>
    </div>
  );
}

function SortableItem({ order }: { order: Order }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: order.id.toString(), data: { order } });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.3 : 1,
  };

  return (
    <div ref={setNodeRef} style={style} {...attributes} {...listeners}>
      <KanbanCard order={order} />
    </div>
  );
}

function KanbanColumn({
  stage,
  orders,
}: {
  stage: (typeof STAGES)[0];
  orders: Order[];
}) {
  const { setNodeRef, isOver } = useDroppable({
    id: stage.id,
  });

  return (
    <div
      ref={setNodeRef}
      className={`w-72 flex-shrink-0 flex flex-col rounded-2xl border transition-colors ${stage.color} ${
        isOver ? 'ring-2 ring-blue-500 bg-blue-100/40' : ''
      }`}
    >
      {/* Column Header */}
      <div className="p-3.5 border-b border-slate-200/60 flex items-center justify-between">
        <div className="flex items-center gap-2">
          <h3 className="font-bold text-xs text-slate-800 uppercase tracking-wider">{stage.label}</h3>
          <span className="px-2 py-0.5 text-xs font-extrabold rounded-full bg-white text-slate-700 shadow-sm border border-slate-200">
            {orders.length}
          </span>
        </div>
      </div>

      {/* Cards List */}
      <div className="p-2.5 flex-1 space-y-2.5 overflow-y-auto max-h-[calc(100vh-250px)] min-h-[140px]">
        <SortableContext
          items={orders.map((o) => o.id.toString())}
          strategy={verticalListSortingStrategy}
        >
          {orders.map((order) => (
            <SortableItem key={order.id} order={order} />
          ))}
        </SortableContext>
        {orders.length === 0 && (
          <div className="h-24 flex items-center justify-center border border-dashed border-slate-300 rounded-xl text-[11px] text-slate-400 font-medium">
            Tidak ada pesanan
          </div>
        )}
      </div>
    </div>
  );
}

export default function KanbanBoard({ initialOrders, onStageChange }: KanbanBoardProps) {
  const [orders, setOrders] = useState<Order[]>(initialOrders);
  const [activeOrder, setActiveOrder] = useState<Order | null>(null);

  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(KeyboardSensor)
  );

  const handleDragStart = (event: DragStartEvent) => {
    const { active } = event;
    const found = orders.find((o) => o.id.toString() === active.id);
    if (found) setActiveOrder(found);
  };

  const handleDragEnd = async (event: DragEndEvent) => {
    const { active, over } = event;
    setActiveOrder(null);

    if (!over) return;

    const activeId = Number(active.id);
    const order = orders.find((o) => o.id === activeId);
    if (!order) return;

    let targetStage = over.id.toString();

    // If dropped over another card instead of column directly
    const overOrder = orders.find((o) => o.id.toString() === over.id);
    if (overOrder) {
      targetStage = overOrder.status_produksi;
    }

    if (order.status_produksi !== targetStage) {
      // Optimistic update
      setOrders((prev) =>
        prev.map((o) => (o.id === activeId ? { ...o, status_produksi: targetStage } : o))
      );
      await onStageChange(activeId, targetStage);
    }
  };

  return (
    <DndContext
      sensors={sensors}
      collisionDetection={closestCorners}
      onDragStart={handleDragStart}
      onDragEnd={handleDragEnd}
    >
      <div className="flex gap-4 overflow-x-auto pb-6 pt-2">
        {STAGES.map((stage) => {
          const stageOrders = orders.filter((o) => {
            if (stage.id === 'Antrian') {
              return ['Antrian', 'Menunggu Produksi'].includes(o.status_produksi);
            }
            return o.status_produksi === stage.id;
          });
          return <KanbanColumn key={stage.id} stage={stage} orders={stageOrders} />;
        })}
      </div>

      <DragOverlay>
        {activeOrder ? <KanbanCard order={activeOrder} isOverlay /> : null}
      </DragOverlay>
    </DndContext>
  );
}
