{{--
    Reusable CRUD Modal Component
    
    Usage:
    <x-crud-modal id="createModal" title="Tambah Data" size="md">
        <form id="createForm">...</form>
    </x-crud-modal>
    
    Props:
    - id: unique modal identifier (required)
    - title: modal title text (required)
    - size: sm (400px), md (600px), lg (800px), xl (1000px) — default: md
    - formId: optional form ID for submit button binding
--}}

@props([
    'id',
    'title',
    'size' => 'md',
    'formId' => null,
])

@php
    $maxWidthClass = match($size) {
        'sm' => 'max-w-[400px]',
        'md' => 'max-w-[600px]',
        'lg' => 'max-w-[800px]',
        'xl' => 'max-w-[1000px]',
        default => 'max-w-[600px]',
    };
@endphp

<!-- Modal Backdrop -->
<template x-teleport="body">
<div x-show="{{ $id }}Open"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="{{ $id }}Open = false">

    <!-- Modal Panel -->
    <div @click.outside="{{ $id }}Open = false"
         class="bg-white rounded-2xl shadow-2xl w-[95%] {{ $maxWidthClass }} overflow-hidden transform transition-all border border-gray-100 flex flex-col"
         style="max-height: 90vh;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="scale-95 translate-y-4 opacity-0"
         x-transition:enter-end="scale-100 translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="scale-100 translate-y-0 opacity-100"
         x-transition:leave-end="scale-95 translate-y-4 opacity-0">

        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex-shrink-0">
            <h3 class="text-lg font-bold text-[#0F172A] tracking-tight" x-text="{{ $id }}Title || '{{ $title }}'">{{ $title }}</h3>
            <button @click="{{ $id }}Open = false" type="button"
                    class="flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all duration-200">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
            <!-- Loading Spinner -->
            <div x-show="{{ $id }}Loading" class="flex flex-col items-center justify-center py-12">
                <div class="h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#1E90FF] animate-spin"></div>
                <p class="mt-4 text-sm text-gray-500 font-medium">Memuat data...</p>
            </div>

            <!-- Content Slot -->
            <div x-show="!{{ $id }}Loading">
                {{ $slot }}
            </div>
        </div>

        <!-- Modal Footer (optional submit button) -->
        @if($formId)
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex-shrink-0">
            <button @click="{{ $id }}Open = false" type="button"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button"
                    @click="submitForm_{{ $id }}()"
                    :disabled="{{ $id }}Submitting"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg hover:bg-blue-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <i x-show="{{ $id }}Submitting" class="fa-solid fa-spinner fa-spin mr-2 text-xs"></i>
                <span x-text="{{ $id }}Submitting ? 'Menyimpan...' : 'Simpan'">Simpan</span>
            </button>
        </div>
        @endif
    </div>
</div>
</template>
