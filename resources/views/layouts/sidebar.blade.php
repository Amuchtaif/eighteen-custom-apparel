<!-- Sidebar (Shared Desktop & Mobile) -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-[#F3F4F6] text-[#2d2e34] transition duration-300 ease-in-out md:sticky md:top-0 md:h-screen md:translate-x-0 md:flex border-r border-gray-200">
    <!-- Brand Logo -->
    <div class="flex h-16 items-center px-6 border-b border-gray-200 space-x-3 flex-shrink-0 bg-white relative overflow-hidden">
        <!-- Brand Accent Line -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#E12015] to-[#c91a10]"></div>
        
        <!-- Logo Container -->
        <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center p-1.5 flex-shrink-0 shadow-sm transition-all duration-300 hover:scale-105">
            <img src="{{ asset('assets/images/android-chrome-192x192.png') }}" alt="Logo" class="h-full w-full object-contain">
        </div>
        
        <!-- Brand Info -->
        <div>
            <span class="text-sm font-extrabold tracking-wider text-slate-800 block leading-tight">EIGHTEEN</span>
            <span class="text-[9px] uppercase font-bold tracking-widest text-[#E12015] block mt-0.5">ADMINISTRATOR</span>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto no-scrollbar px-4 py-6 space-y-6">
        <!-- Group 1: General (Admin Only) -->
        @if(in_array(auth()->user()->role->name ?? '', config('roles.admin_roles', [])))
        <div>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Dasbor
                </a>
                <a href="{{ route('reports.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('reports.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Laporan & Analisis
                </a>
            </div>
        </div>
        @endif

        <!-- Group 2:Sales -->
        @if(in_array(auth()->user()->role->name ?? '', array_merge(config('roles.admin_roles', []), ['Sales'])))
        <div>
            <h3 class="px-3.5 text-[10px] font-black uppercase tracking-wider text-[#8e919b]">Penjualan</h3>
            <div class="mt-2 space-y-1">
                <a href="{{ route('customers.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('customers.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-users w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('customers.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Pelanggan
                </a>
                <a href="{{ route('orders.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('orders.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-cart-shopping w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('orders.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Pesanan
                </a>
            </div>
        </div>
        @endif

        <!-- Group 3: Master Data -->
        @if(in_array(auth()->user()->role->name ?? '', array_merge(config('roles.admin_roles', []), ['Production'])))
        <div>
            <h3 class="px-3.5 text-[10px] font-black uppercase tracking-wider text-[#8e919b]">Master Data</h3>
            <div class="mt-2 space-y-1">
                <a href="{{ route('packages.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('packages.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-box w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('packages.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Master Paket
                </a>
            </div>
        </div>
        @endif

        <!-- Group 4: Human Resources -->
        @if(in_array(auth()->user()->role->name ?? '', array_merge(config('roles.admin_roles', []), ['HRD'])))
        <div>
            <h3 class="px-3.5 text-[10px] font-black uppercase tracking-wider text-[#8e919b]">SDM & Kehadiran</h3>
            <div class="mt-2 space-y-1">
                <a href="{{ route('employees.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('employees.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-user-tie w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('employees.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Karyawan
                </a>
                <a href="{{ route('offices.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('offices.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-building w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('offices.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Lokasi Kantor
                </a>
                <a href="{{ route('work-shifts.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('work-shifts.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-clock w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('work-shifts.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Shift Kerja
                </a>
                <a href="{{ route('attendances.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('attendances.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-clipboard-user w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('attendances.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Absensi
                </a>
            </div>
        </div>
        @endif

        <!-- Group 5: Finance -->
        @if(in_array(auth()->user()->role->name ?? '', array_merge(config('roles.admin_roles', []), ['Finance'])))
        <div>
            <h3 class="px-3.5 text-[10px] font-black uppercase tracking-wider text-[#8e919b]">Keuangan</h3>
            <div class="mt-2 space-y-1">
                <a href="{{ route('invoices.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-file-invoice w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('invoices.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Faktur
                </a>
                <a href="{{ route('payments.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-credit-card w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('payments.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Pembayaran
                </a>
            </div>
        </div>
        @endif

        <!-- Group 6: Settings -->
        <div>
            <h3 class="px-3.5 text-[10px] font-black uppercase tracking-wider text-[#8e919b]">Pengaturan</h3>
            <div class="mt-2 space-y-1">
                <a href="{{ route('settings.edit') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('settings.edit') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-gear w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('settings.edit') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Pengaturan Akun
                </a>
                @if(in_array(auth()->user()->role->name ?? '', array_merge(config('roles.admin_roles', []), ['HRD'])))
                <a href="{{ route('settings.roles.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('settings.roles.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-user-shield w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('settings.roles.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Master Role
                </a>
                <a href="{{ route('settings.positions.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('settings.positions.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-briefcase w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('settings.positions.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Master Jabatan
                </a>
                <a href="{{ route('settings.departments.index') }}" class="group relative flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('settings.departments.*') ? 'bg-[#ECF0F6] text-[#2d2e34] border-r-4 border-[#E12015] rounded-r-none' : 'text-[#8e919b] hover:bg-[#ECF0F6] hover:text-[#2d2e34]' }}">
                    <i class="fa-solid fa-sitemap w-5 text-center mr-3 text-sm flex-shrink-0 {{ request()->routeIs('settings.departments.*') ? 'text-[#2d2e34]' : 'text-[#8e919b] group-hover:text-[#2d2e34]' }}"></i>
                    Master Departemen
                </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- User Profile Widget -->
    <div class="p-4 border-t border-gray-200 flex items-center justify-between bg-[#F3F4F6]">
        <div class="flex items-center space-x-3 min-w-0">
            <div class="h-9 w-9 rounded-full bg-[#3B82F6] flex items-center justify-center font-bold text-white text-xs uppercase flex-shrink-0">
                {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
            </div>
            <div class="min-w-0">
                <span class="text-xs font-bold text-[#2d2e34] block truncate leading-none">{{ auth()->user()->name ?? 'ANDI MUCHTAIF' }}</span>
                <span class="text-[10px] text-[#8e919b] font-medium block leading-none mt-1">{{ auth()->user()->role->name ?? 'Pemilik' }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit" class="flex items-center justify-center h-8 w-8 rounded-xl bg-red-50 hover:bg-red-100 text-[#E12015] hover:text-[#c91a10] transition-all duration-200 shadow-sm border border-red-100" title="Keluar Panel">
                <i class="fa-solid fa-right-from-bracket text-xs"></i>
            </button>
        </form>
    </div>
</aside>
