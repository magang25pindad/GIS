<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Phone Monitoring System - Selamat datang {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Map Section -->
            <div class="lg:col-span-2 bg-white/95 backdrop-blur-md rounded-2xl p-6 shadow-lg">
                <div class="flex justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-indigo-500"></i>
                        Peta Gedung & Status Telepon
                    </h2>
                    <div class="flex gap-2">
                        <button onclick="openAddModal()" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:shadow transition text-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Device
                        </button>
                        <button onclick="refreshMap()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:shadow transition text-sm">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </button>
                    </div>
                </div>
                <div id="map" class="h-96 rounded-xl overflow-hidden shadow-inner"></div>
                <div class="flex gap-4 mt-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full shadow-lg"></div>
                        <span class="text-gray-700 font-medium">Online</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full shadow-lg"></div>
                        <span class="text-gray-700 font-medium">Offline</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full shadow-lg"></div>
                        <span class="text-gray-700 font-medium">Partial</span>
                    </div>
                </div>
            </div>

            <!-- Device Status List -->
            <div class="bg-white/95 backdrop-blur-md rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-list text-indigo-500 mr-2"></i>Status Device
                </h3>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($devices as $device)
                    <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-indigo-50 cursor-pointer">
                        <div class="w-3 h-3 bg-{{ $device->status == 'online' ? 'green' : ($device->status == 'offline' ? 'red' : 'yellow') }}-500 rounded-full shadow {{ $device->status == 'online' ? 'animate-pulse' : '' }}"></div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800">{{ $device->name }}</div>
                            <div class="text-sm text-gray-600">{{ $device->ip }}</div>
                        </div>
                        <div class="text-right text-xs text-gray-500">
                            <div>{{ $device->uptime ?? '99.9%' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Device Management Table -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-table text-blue-600 mr-2"></i>Manajemen Device
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Gedung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Device (Koordinat)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($devices as $index => $device)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $device->name }}</td>
                            <td class="px-6 py-4 text-sm font-mono">{{ $device->ip }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-{{ $device->status == 'online' ? 'green' : ($device->status == 'offline' ? 'red' : 'yellow') }}-500 rounded-full"></div>
                                    {{ ucfirst($device->status) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono">
                                {{ number_format($device->latitude, 6) }}, {{ number_format($device->longitude, 6) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex gap-2">
                                    <button onclick="editDevice({{ $device->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus device ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <button onclick="focusOnDevice({{ $device->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="deviceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <div class="flex justify-between mb-6">
                    <h3 id="modal-title" class="text-lg font-semibold text-gray-900">Tambah Device Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>

                <form id="deviceForm" method="POST" action="{{ route('devices.store') }}">
                    @csrf
                    <input type="hidden" id="device_id" name="device_id">
                    <div class="space-y-4">
                        <div>
                            <label for="deviceName" class="block text-sm font-medium">Nama Gedung</label>
                            <input id="deviceName" name="name" type="text" required class="form-input">
                        </div>
                        <div>
                            <label for="deviceIP" class="block text-sm font-medium">IP Address</label>
                            <input id="deviceIP" name="ip" type="text" required pattern="^(\d{1,3}\.){3}\d{1,3}$" class="form-input">
                        </div>
                        <div>
                            <label for="deviceStatus" class="block text-sm font-medium">Status</label>
                            <select id="deviceStatus" name="status" required class="form-select">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                                <option value="partial">Partial</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="deviceLatitude" class="block text-sm font-medium">Latitude</label>
                                <input id="deviceLatitude" name="latitude" type="number" step="any" readonly required class="form-input bg-gray-50">
                            </div>
                            <div>
                                <label for="deviceLongitude" class="block text-sm font-medium">Longitude</label>
                                <input id="deviceLongitude" name="longitude" type="number" step="any" readonly required class="form-input bg-gray-50">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Klik pada peta untuk memilih lokasi device.
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <button type="button" onclick="closeModal()" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite('resources/js/device-map.js')


    <!-- Data Inject for JS -->
    <script>
        window.devices = @json($devices);
        window.routes = {
            store: "{{ route('devices.store') }}",
            update: "{{ route('devices.update', ':id') }}",
            destroy: "{{ route('devices.destroy', ':id') }}",
            csrf: "{{ csrf_token() }}",
        };
    </script>

    @push('scripts')
        <!-- Leaflet & Draw -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

        <!-- Custom JS -->
        @vite(['resources/js/device-map.js'])

        <style>
            .temp-marker {
                animation: bounce 1s infinite;
            }

            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-10px);
                }
                60% {
                    transform: translateY(-5px);
                }
            }
        </style>
    @endpush
</x-app-layout>
