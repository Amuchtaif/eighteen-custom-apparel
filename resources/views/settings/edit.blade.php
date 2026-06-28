@extends('layouts.admin')

@section('title', 'Pengaturan Akun - Eighteen ERP')

@section('content')
<div class="space-y-8">
    <!-- Header Page -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Pengaturan Akun</h1>
        <p class="text-sm text-[#64748B] mt-1">Ubah rincian profil Anda dan ganti kata sandi akun.</p>
    </div>



    <!-- Profile Edit Form -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#E2E8F0] bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-base font-bold text-[#0F172A]">Informasi Profil</h2>
            <p class="text-xs text-[#64748B] mt-0.5">Perbarui nama lengkap, email, dan nomor telepon aktif Anda.</p>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
            </div>

            <!-- Phone Field -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="text" name="phone" id="phone" required value="{{ old('phone', $user->phone) }}"
                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
            </div>

            <!-- WhatsApp Website Field -->
            <div>
                <label for="whatsapp_number" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Nomor WhatsApp Website (untuk Pesanan Baru) <span class="text-red-500">*</span></label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" required value="{{ old('whatsapp_number', $whatsappNumber) }}"
                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all"
                       placeholder="Contoh: 6281234567890">
                <span class="text-xs text-[#64748B] mt-1 block">Gunakan kode negara (misal 62) di awal nomor tanpa tanda + atau spasi.</span>
            </div>

            <hr class="border-[#E2E8F0] my-6" />

            <!-- Password Reset Section -->
            <div>
                <h3 class="text-sm font-bold text-[#0F172A]">Ganti Kata Sandi</h3>
                <p class="text-xs text-[#64748B] mt-0.5 mb-4">Biarkan kosong jika Anda tidak ingin mengubah kata sandi akun Anda.</p>
            </div>

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" id="current_password"
                       class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all"
                       placeholder="••••••••">
            </div>

            <!-- New Password Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Kata Sandi Baru</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all"
                           placeholder="••••••••">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all"
                           placeholder="••••••••">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg hover:bg-blue-600 transition-colors shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
