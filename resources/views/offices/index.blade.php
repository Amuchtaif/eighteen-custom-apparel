@extends('layouts.admin')

@section('title', 'Lokasi Kantor - Eighteen ERP')

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush


@section('content')
<div class="space-y-8" x-data="officeManager()">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Lokasi Kantor</h1>
            <p class="text-sm text-[#64748B] mt-1">Kelola lokasi kantor dan geofencing absensi pegawai.</p>
        </div>
        <div>
            <button @click="openCreate()" type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[#1E90FF] rounded-lg shadow-sm hover:bg-blue-600 transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Lokasi
            </button>
        </div>
    </div>

    <!-- Listing Card -->
    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-sm overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E2E8F0] text-left">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] w-12 text-center">No</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Nama Lokasi</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B]">Alamat</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Radius</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-center">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#64748B] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-sm">
                    @forelse ($offices as $office)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-[#64748B]">{{ ($offices->currentPage() - 1) * $offices->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-[#0F172A]">{{ $office->name }}</td>
                            <td class="px-6 py-4 text-[#64748B] whitespace-normal min-w-[200px]">{{ $office->address ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-mono font-semibold text-[#64748B]">
                                {{ $office->radius_meter }}m
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($office->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button type="button" 
                                        @click="openEdit({{ $office->id }}, '{{ addslashes($office->name) }}', '{{ addslashes($office->address) }}', '{{ $office->latitude }}', '{{ $office->longitude }}', {{ $office->radius_meter }}, {{ $office->is_active ? 'true' : 'false' }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-50 text-[#1E90FF] hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                        title="Ubah Lokasi">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <button type="button" 
                                        @click="confirmDelete({{ $office->id }}, '{{ addslashes($office->name) }}')"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"
                                        title="Hapus Lokasi">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-building text-2xl text-gray-400"></i>
                                    <span>Data lokasi kantor belum tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($offices->hasPages())
            <div class="px-6 py-4 border-t border-[#E2E8F0]">
                {{ $offices->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal (Large with Map) -->
    <template x-teleport="body">
        <div x-show="modalOpen" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-[#F8FAFC] rounded-2xl shadow-2xl w-full max-w-6xl h-[90vh] overflow-hidden flex flex-col" @click.away="closeModal()">
                <div class="px-8 py-6 border-b border-[#E2E8F0] bg-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-extrabold text-[#0F172A]" x-text="isEdit ? 'Ubah Lokasi' : 'Tambah Lokasi'"></h3>
                        <p class="text-sm text-[#64748B] mt-1">Konfigurasikan detail lokasi dan radius geofencing.</p>
                    </div>
                    <button @click="closeModal()" class="text-[#64748B] hover:text-[#0F172A] bg-gray-100 hover:bg-gray-200 rounded-full w-10 h-10 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-8 flex flex-col">
                    <form :action="formAction" method="POST" id="office-form" class="flex-1 flex flex-col lg:flex-row gap-8">
                        @csrf
                        <template x-if="isEdit">
                            @method('PUT')
                        </template>

                        <!-- Left: Form -->
                        <div class="w-full lg:w-7/12 space-y-6 bg-white p-6 rounded-2xl border border-[#E2E8F0] shadow-sm font-sans">
                            <div>
                                <label for="name" class="block text-sm font-bold text-[#0F172A] mb-2">Nama Lokasi</label>
                                <input type="text" name="name" id="name" x-model="formData.name" required placeholder="Contoh: Kantor Cabang Bekasi"
                                    class="w-full px-4 py-3 bg-white border border-[#E2E8F0] rounded-xl text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 font-sans">
                                <div>
                                    <label for="latitude" class="block text-sm font-bold text-[#0F172A] mb-2">Latitude</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-xs font-bold text-gray-400">LAT</span>
                                        <input type="text" name="latitude" id="latitude" x-model="formData.latitude" @input="updateMap()" placeholder="-6.175392"
                                            class="w-full pl-12 pr-4 py-3 bg-white border border-[#E2E8F0] rounded-xl text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all font-mono">
                                    </div>
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-bold text-[#0F172A] mb-2">Longitude</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-xs font-bold text-gray-400">LNG</span>
                                        <input type="text" name="longitude" id="longitude" x-model="formData.longitude" @input="updateMap()" placeholder="106.827153"
                                            class="w-full pl-12 pr-4 py-3 bg-white border border-[#E2E8F0] rounded-xl text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#1E90FF] focus:border-transparent transition-all font-mono">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 font-sans">
                                <div class="flex justify-between items-center mb-4">
                                    <label for="radius_meter" class="block text-sm font-bold text-[#0F172A] flex items-center">
                                        Radius Geofence <i class="fa-solid fa-circle-info text-gray-400 ml-2 text-xs" title="Jangkauan area absensi"></i>
                                    </label>
                                    <div class="bg-blue-50 text-[#1E90FF] px-3 py-1 rounded-lg text-xs font-bold">
                                        <span x-text="formData.radius_meter"></span> meter
                                    </div>
                                </div>
                                <input type="range" name="radius_meter" id="radius_meter" x-model="formData.radius_meter" @input="updateMap()" min="50" max="1000" step="10"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#1E90FF]">
                                <div class="flex justify-between text-[10px] text-gray-400 font-bold mt-2">
                                    <span>50M</span>
                                    <span>250M</span>
                                    <span>500M</span>
                                    <span>750M</span>
                                    <span>1000M</span>
                                </div>
                            </div>

                            <hr class="border-gray-100 my-4">

                            <div class="flex items-center space-x-4 font-sans">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="sr-only peer">
                                    <div class="relative w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#00880d]"></div>
                                </label>
                                <div>
                                    <div class="text-sm font-bold text-[#0F172A]">Status Aktif</div>
                                    <div class="text-xs text-[#64748B]">Lokasi nonaktif tidak akan muncul di aplikasi pegawai.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Map -->
                        <div class="w-full lg:w-5/12 bg-white p-6 rounded-2xl border border-[#E2E8F0] shadow-sm flex flex-col h-full min-h-[400px] font-sans">
                            <h4 class="text-xs font-bold text-[#1E90FF] uppercase tracking-wider mb-4 flex items-center"><i class="fa-solid fa-location-dot mr-2"></i> Pratinjau Lokasi</h4>
                            
                            <div class="flex-1 rounded-xl overflow-hidden border-2 border-gray-100 min-h-[300px] relative z-10" id="map-preview"></div>
                            
                            <div class="mt-4 bg-cyan-50 border border-cyan-100 rounded-xl p-4 flex items-start space-x-3">
                                <i class="fa-solid fa-circle-info text-cyan-500 mt-0.5"></i>
                                <div>
                                    <div class="text-xs font-bold text-cyan-900">Petunjuk:</div>
                                    <div class="text-[11px] text-cyan-700 leading-relaxed mt-1">Klik pada peta untuk mengubah koordinat atau geser slider radius untuk melihat jangkauan absensi.</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="px-8 py-5 border-t border-[#E2E8F0] bg-gray-50 flex items-center justify-between">
                    <button type="button" @click="getLocation()" class="text-sm font-bold text-[#0F172A] hover:text-[#1E90FF] transition-colors flex items-center">
                        <i class="fa-solid fa-location-crosshairs mr-2 text-[#1E90FF] animate-pulse"></i> Gunakan Lokasi Saya Saat Ini
                    </button>
                    <div class="flex space-x-4">
                        <button type="button" @click="closeModal()" class="px-6 py-3 bg-white border border-[#E2E8F0] text-[#64748B] font-bold rounded-xl hover:bg-gray-50 transition-colors text-sm">
                            Batal
                        </button>
                        <button type="button" @click="document.getElementById('office-form').submit()" class="px-6 py-3 bg-[#00880d] text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:bg-[#00660a] transition-colors text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
        <div x-show="deleteModalOpen" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6" @click.away="closeDeleteModal()">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#0F172A] mb-2">Hapus Lokasi?</h3>
                <p class="text-sm text-[#64748B] mb-6">Apakah Anda yakin ingin menghapus <span class="font-bold text-[#0F172A]" x-text="deleteItemName"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                
                <form :action="deleteAction" method="POST" class="flex flex-col sm:flex-row gap-3 justify-center">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="closeDeleteModal()" class="px-4 py-2.5 bg-white border border-[#E2E8F0] text-[#64748B] font-semibold rounded-lg hover:bg-gray-50 transition-colors text-sm w-full sm:w-auto">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-red-600 text-white font-bold rounded-lg shadow-sm hover:bg-red-700 transition-colors text-sm w-full sm:w-auto">
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </template>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function officeManager() {
        return {
            modalOpen: false,
            deleteModalOpen: false,
            isEdit: false,
            formAction: '',
            deleteAction: '',
            deleteItemName: '',
            formData: {
                name: '',
                address: '',
                latitude: '-6.200000',
                longitude: '106.816666',
                radius_meter: 100,
                is_active: true
            },
            map: null,
            marker: null,
            circle: null,
            
            initMap() {
                setTimeout(() => {
                    if (!this.map) {
                        this.map = L.map('map-preview').setView([this.formData.latitude, this.formData.longitude], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap'
                        }).addTo(this.map);

                        this.marker = L.marker([this.formData.latitude, this.formData.longitude], { draggable: true }).addTo(this.map);
                        this.circle = L.circle([this.formData.latitude, this.formData.longitude], {
                            radius: this.formData.radius_meter,
                            color: '#1E90FF',
                            fillColor: '#1E90FF',
                            fillOpacity: 0.2
                        }).addTo(this.map);

                        this.marker.on('dragend', (e) => {
                            const position = e.target.getLatLng();
                            this.formData.latitude = position.lat.toFixed(6);
                            this.formData.longitude = position.lng.toFixed(6);
                            this.circle.setLatLng(position);
                            this.map.panTo(position);
                        });

                        this.map.on('click', (e) => {
                            const position = e.latlng;
                            this.formData.latitude = position.lat.toFixed(6);
                            this.formData.longitude = position.lng.toFixed(6);
                            this.marker.setLatLng(position);
                            this.circle.setLatLng(position);
                            this.map.panTo(position);
                        });
                    }
                    this.map.invalidateSize();
                }, 350);
            },

            updateMap() {
                setTimeout(() => {
                    if (this.map && this.marker && this.circle) {
                        this.map.invalidateSize();
                        const lat = parseFloat(this.formData.latitude) || -6.200000;
                        const lng = parseFloat(this.formData.longitude) || 106.816666;
                        const r = parseInt(this.formData.radius_meter) || 100;
                        const pos = new L.LatLng(lat, lng);
                        
                        this.marker.setLatLng(pos);
                        this.circle.setLatLng(pos);
                        this.circle.setRadius(r);
                        this.map.panTo(pos);
                    }
                }, 350);
            },

            getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        this.formData.latitude = position.coords.latitude.toFixed(6);
                        this.formData.longitude = position.coords.longitude.toFixed(6);
                        this.updateMap();
                    }, (err) => {
                        alert('Gagal mengambil lokasi.');
                    });
                } else {
                    alert('Geolocation tidak didukung oleh browser Anda.');
                }
            },
            
            openCreate() {
                this.isEdit = false;
                this.formAction = '{{ route('offices.store') }}';
                this.formData = {
                    name: '',
                    address: '',
                    latitude: '-6.175392',
                    longitude: '106.827153',
                    radius_meter: 100,
                    is_active: true
                };
                this.modalOpen = true;
                this.initMap();
                setTimeout(() => this.updateMap(), 400);
            },
            
            openEdit(id, name, address, lat, lng, radius, isActive) {
                this.isEdit = true;
                this.formAction = `/offices/${id}`;
                this.formData = {
                    name: name,
                    address: address,
                    latitude: lat,
                    longitude: lng,
                    radius_meter: radius,
                    is_active: isActive
                };
                this.modalOpen = true;
                this.initMap();
                setTimeout(() => this.updateMap(), 400);
            },
            
            closeModal() {
                this.modalOpen = false;
            },
            
            confirmDelete(id, name) {
                this.deleteItemName = name;
                this.deleteAction = `/offices/${id}`;
                this.deleteModalOpen = true;
            },
            
            closeDeleteModal() {
                this.deleteModalOpen = false;
            }
        }
    }
</script>
@endsection
