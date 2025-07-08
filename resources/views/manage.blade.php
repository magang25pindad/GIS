
<x-app-layout>
        
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Phone Monitoring System - Selamat datang {{ Auth::user()->name }}
        </h2>
    </x-slot>
        <nav class="bg-white shadow-lg border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <i class="fas fa-building text-blue-600 text-xl mr-3"></i>
                            <h1 class="text-xl font-semibold text-gray-900">Building Management</h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="refreshMap()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <i class="fas fa-sync-alt"></i>
                            <span>Refresh</span>
                        </button>
                        <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <i class="fas fa-plus"></i>
                            <span>Add Node</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Sistem Manajemen Gedung & Telepon</h1>
                        <p class="mt-2 text-gray-600">Kelola lokasi gedung dan monitor status telepon secara real-time</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse-custom"></div>
                            <span class="text-sm text-gray-600">Online</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Offline</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Partial</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Map Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                                Peta Gedung & Status Telepon
                            </h2>
                        </div>
                        <div id="map" class="rounded-xl overflow-hidden shadow-inner"></div>
                    </div>
                </div>

                <!-- Status List -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-list text-blue-600 mr-2"></i>
                            Status Real-time
                        </h3>
                        <div id="phone-status-list" class="space-y-3 max-h-96 overflow-y-auto">
                            <!-- Status items will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nodes Management Table -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-table text-blue-600 mr-2"></i>
                    Manajemen Node
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Gedung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Koordinat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uptime</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="nodes-table-body" class="bg-white divide-y divide-gray-200">
                            <!-- Table rows will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div id="nodeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="modal-title" class="text-lg font-semibold text-gray-900">Tambah Node Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="nodeForm">
                    <div class="space-y-4">
                        <div>
                            <label for="nodeName" class="block text-sm font-medium text-gray-700 mb-1">Nama Gedung</label>
                            <input type="text" id="nodeName" name="name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="nodeIP" class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                            <input type="text" id="nodeIP" name="ip" required pattern="^(\d{1,3}\.){3}\d{1,3}$"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="nodeStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="nodeStatus" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                                <option value="partial">Partial</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="nodeLatitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                <input type="number" id="nodeLatitude" name="latitude" step="any" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="nodeLongitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                <input type="number" id="nodeLongitude" name="longitude" step="any" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Global variables
        let map;
        let markers = [];
        let nodes = [
            { 
                id: 1,
                name: 'Gedung A', 
                coords: [-8.173500, 112.684700], 
                status: 'online',
                ip: '192.168.1.101',
                lastPing: '2s ago',
                uptime: '99.8%'
            },
            { 
                id: 2,
                name: 'Gedung B', 
                coords: [-8.173100, 112.684200], 
                status: 'offline',
                ip: '192.168.1.102',
                lastPing: '5m ago',
                uptime: '85.2%'
            },
            { 
                id: 3,
                name: 'Gedung C', 
                coords: [-8.172800, 112.685000], 
                status: 'online',
                ip: '192.168.1.103',
                lastPing: '1s ago',
                uptime: '99.9%'
            }
        ];
        let editingNodeId = null;
        let nextNodeId = 4;

        // Initialize map
        function initMap() {
            map = L.map('map').setView([-8.173358, 112.684885], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 20,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add click event to map for adding nodes
            map.on('click', function(e) {
                if (confirm('Tambah node baru di lokasi ini?')) {
                    document.getElementById('nodeLatitude').value = e.latlng.lat.toFixed(6);
                    document.getElementById('nodeLongitude').value = e.latlng.lng.toFixed(6);
                    openAddModal();
                }
            });

            updateMapMarkers();
        }

        // Update map markers
        function updateMapMarkers() {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            // Add new markers
            nodes.forEach(function(node) {
                const color = getStatusColor(node.status);
                
                const marker = L.circleMarker(node.coords, {
                    radius: 12,
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.8,
                    weight: 3
                }).addTo(map);

                const popupContent = `
                    <div class="p-4 min-w-48">
                        <h4 class="font-semibold text-gray-900 mb-3">${node.name}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium" style="color: ${color};">${node.status}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">IP:</span>
                                <span class="font-mono text-xs">${node.ip}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Ping:</span>
                                <span>${node.lastPing}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Uptime:</span>
                                <span class="font-medium">${node.uptime}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2 mt-4">
                            <button onclick="editNode(${node.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button onclick="deleteNode(${node.id})" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                markers.push(marker);
            });
        }

        // Get status color
        function getStatusColor(status) {
            switch(status) {
                case 'online': return '#10b981';
                case 'offline': return '#ef4444';
                case 'partial': return '#f59e0b';
                default: return '#6b7280';
            }
        }

        // Update status list
        function updateStatusList() {
            const statusList = document.getElementById('phone-status-list');
            statusList.innerHTML = '';

            nodes.forEach(function(node) {
                const statusItem = document.createElement('div');
                statusItem.className = 'flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors';
                statusItem.onclick = () => focusOnNode(node.id);
                
                const statusColorClass = node.status === 'online' ? 'bg-green-500 animate-pulse-custom' : 
                                       node.status === 'offline' ? 'bg-red-500' : 'bg-yellow-500';
                
                statusItem.innerHTML = `
                    <div class="w-3 h-3 ${statusColorClass} rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">${node.name}</div>
                        <div class="text-sm text-gray-500 font-mono">${node.ip}</div>
                        <div class="text-xs text-gray-400">Last ping: ${node.lastPing}</div>
                    </div>
                    <div class="text-right text-sm text-gray-600">
                        <div class="font-medium">${node.status === 'offline' ? 'Timeout' : '42ms'}</div>
                        <div class="text-xs">${node.uptime}</div>
                    </div>
                `;
                
                statusList.appendChild(statusItem);
            });
        }

        // Update nodes table
        function updateNodesTable() {
            const tableBody = document.getElementById('nodes-table-body');
            tableBody.innerHTML = '';

            nodes.forEach(function(node) {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                
                const statusColorClass = node.status === 'online' ? 'bg-green-500' : 
                                       node.status === 'offline' ? 'bg-red-500' : 'bg-yellow-500';
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${node.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">${node.ip}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="inline-flex items-center">
                            <div class="w-2 h-2 ${statusColorClass} rounded-full mr-2"></div>
                            ${node.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">${node.coords[0].toFixed(4)}, ${node.coords[1].toFixed(4)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${node.uptime}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button onclick="editNode(${node.id})" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteNode(${node.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button onclick="focusOnNode(${node.id})" class="text-green-600 hover:text-green-900">
                            <i class="fas fa-search"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // CRUD Operations
        function createNode(nodeData) {
            const newNode = {
                id: nextNodeId++,
                name: nodeData.name,
                coords: [parseFloat(nodeData.latitude), parseFloat(nodeData.longitude)],
                status: nodeData.status,
                ip: nodeData.ip,
                lastPing: nodeData.status === 'online' ? '1s ago' : '5m ago',
                uptime: nodeData.status === 'online' ? '99.9%' : '85.2%'
            };

            nodes.push(newNode);
            updateAll();
            alert('Node berhasil ditambahkan!');
        }

        function findNode(id) {
            return nodes.find(node => node.id === id);
        }

        function updateNode(id, nodeData) {
            const nodeIndex = nodes.findIndex(node => node.id === id);
            if (nodeIndex !== -1) {
                nodes[nodeIndex] = {
                    ...nodes[nodeIndex],
                    name: nodeData.name,
                    coords: [parseFloat(nodeData.latitude), parseFloat(nodeData.longitude)],
                    status: nodeData.status,
                    ip: nodeData.ip,
                    lastPing: nodeData.status === 'online' ? '1s ago' : '5m ago',
                    uptime: nodeData.status === 'online' ? '99.9%' : '85.2%'
                };
                updateAll();
                alert('Node berhasil diperbarui!');
            }
        }

        function deleteNode(id) {
            if (confirm('Yakin ingin menghapus node ini?')) {
                const nodeIndex = nodes.findIndex(node => node.id === id);
                if (nodeIndex !== -1) {
                    nodes.splice(nodeIndex, 1);
                    updateAll();
                    alert('Node berhasil dihapus!');
                }
            }
        }

        // Modal functions
        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Tambah Node Baru';
            document.getElementById('nodeForm').reset();
            editingNodeId = null;
            document.getElementById('nodeModal').classList.remove('hidden');
        }

        function editNode(id) {
            const node = findNode(id);
            if (node) {
                document.getElementById('modal-title').textContent = 'Edit Node';
                document.getElementById('nodeName').value = node.name;
                document.getElementById('nodeIP').value = node.ip;
                document.getElementById('nodeStatus').value = node.status;
                document.getElementById('nodeLatitude').value = node.coords[0];
                document.getElementById('nodeLongitude').value = node.coords[1];
                editingNodeId = id;
                document.getElementById('nodeModal').classList.remove('hidden');
            }
        }

        function closeModal() {
            document.getElementById('nodeModal').classList.add('hidden');
            editingNodeId = null;
        }

        function focusOnNode(id) {
            const node = findNode(id);
            if (node) {
                map.setView(node.coords, 18);
                const marker = markers.find(m => m.getLatLng().lat === node.coords[0] && m.getLatLng().lng === node.coords[1]);
                if (marker) {
                    marker.openPopup();
                }
            }
        }

        function updateAll() {
            updateMapMarkers();
            updateStatusList();
            updateNodesTable();
        }

        function refreshMap() {
            nodes.forEach(node => {
                if (Math.random() > 0.8) {
                    const statuses = ['online', 'offline', 'partial'];
                    node.status = statuses[Math.floor(Math.random() * statuses.length)];
                    node.lastPing = node.status === 'online' ? 
                        Math.floor(Math.random() * 5) + 's ago' : 
                        Math.floor(Math.random() * 10) + 'm ago';
                }
            });
            updateAll();
        }

        // Form submission
        document.getElementById('nodeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const nodeData = {
                name: formData.get('name'),
                ip: formData.get('ip'),
                status: formData.get('status'),
                latitude: formData.get('latitude'),
                longitude: formData.get('longitude')
            };

            if (editingNodeId) {
                updateNode(editingNodeId, nodeData);
            } else {
                createNode(nodeData);
            }

            closeModal();
        });

        // Close modal when clicking outside
        document.getElementById('nodeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            updateStatusList();
            updateNodesTable();

            // Auto refresh every 30 seconds
            setInterval(refreshMap, 30000);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</x-app-layout>