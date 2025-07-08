<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Selamat datang {{ Auth::user()->name }} - di Peta Monitoring Telepon
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4">
                        {{ __("Lokasi indikator telepon.") }}
                    </div>

                    {{-- Kontainer Peta --}}
                    <div id="map" class="w-full h-96 rounded-md shadow"></div>
                    
                <div class="mt-6 flex items-center gap-8">
                    Keterangan:
                <div class="flex items-center gap-2">
                    <!-- Lingkaran hijau SVG -->
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="10" cy="10" r="8" fill="#22C55E" stroke="#16A34A" stroke-width="2"/>
                    </svg>
                    <span class="text-gray-700 font-semibold">Online</span>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Lingkaran merah SVG -->
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="10" cy="10" r="8" fill="#EF4444" stroke="#B91C1C" stroke-width="2"/>
                    </svg>
                    <span class="text-gray-700 font-semibold">Offline</span>
                </div>
                </div>

                </div>
            </div>
        </div>
    </div>

    {{-- CDN Leaflet Draw CSS dan JS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    {{-- Inisialisasi Peta dan Leaflet Draw --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([-8.173358, 112.684885], 17);

        // Tile Layer
        L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}&key=YOUR_API_KEY', {
                maxZoom: 20,
                attribution: 'Map data © Google'
            }).addTo(map);

        // Dummy data node telepon
        const nodes = [
            { name: 'Gedung A', coords: [-8.173500, 112.684700], status: 'online' },
            { name: 'Gedung B', coords: [-8.173100, 112.684200], status: 'offline' },
            { name: 'Gedung C', coords: [-8.172800, 112.685000], status: 'online' },
        ];

        nodes.forEach(function(node) {
            const color = node.status === 'online' ? 'green' : 'red';

            const marker = L.circleMarker(node.coords, {
                radius: 10,
                color: color,
                fillColor: color,
                fillOpacity: 0.8
            }).addTo(map);

            marker.bindPopup(`${node.name}<br>Status: <strong>${node.status}</strong>`);
        });

        // FeatureGroup gambar
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems
            },
            draw: {
                polygon: true,
                polyline: true,
                rectangle: true,
                circle: false,
                marker: true
            }
        });
        map.addControl(drawControl);

        map.on('draw:created', function (e) {
            var layer = e.layer;
            drawnItems.addLayer(layer);

            var geojson = layer.toGeoJSON();
            console.log('GeoJSON hasil gambar:', JSON.stringify(geojson));
        });
    });
</script>

</x-app-layout>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Monitoring Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 2rem;
            color: #667eea;
        }

        .logo h1 {
            color: #333;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-online {
            background: #d4edda;
            color: #155724;
        }

        .status-offline {
            background: #f8d7da;
            color: #721c24;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.online { background: linear-gradient(45deg, #28a745, #20c997); }
        .stat-icon.offline { background: linear-gradient(45deg, #dc3545, #fd7e14); }
        .stat-icon.total { background: linear-gradient(45deg, #007bff, #6610f2); }
        .stat-icon.response { background: linear-gradient(45deg, #ffc107, #e83e8c); }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .map-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .map-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .refresh-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        #map {
            height: 500px;
            border-radius: 12px;
            overflow: hidden;
        }

        .phone-list {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            max-height: 600px;
            overflow-y: auto;
        }

        .phone-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .phone-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }

        .phone-status {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
            position: relative;
        }

        .phone-status.online {
            background: #28a745;
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.2);
        }

        .phone-status.offline {
            background: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.2);
        }

        .phone-status.online::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 6px;
            height: 6px;
            background: white;
            border-radius: 50%;
        }

        .phone-info {
            flex: 1;
        }

        .phone-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .phone-details {
            font-size: 0.85rem;
            color: #666;
        }

        .phone-meta {
            text-align: right;
            font-size: 0.8rem;
            color: #888;
        }

        .activities-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .activity-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .activity-icon.alert {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
        }

        .activity-icon.recovery {
            background: linear-gradient(45deg, #28a745, #20c997);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .activity-desc {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #888;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .blinking {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }

        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .legend {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-phone-alt"></i>
                <h1>Phone Monitoring System</h1>
            </div>
            <div class="status-badge status-online">
                <i class="fas fa-circle"></i>
                <span>System Online</span>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon total">
                        <i class="fas fa-phone"></i>
                    </div>
                </div>
                <div class="stat-value">24</div>
                <div class="stat-label">Total Telepon</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon online">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value">21</div>
                <div class="stat-label">Online</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon offline">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-value pulse">3</div>
                <div class="stat-label">Offline</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon response">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">45ms</div>
                <div class="stat-label">Avg Response</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Map Section -->
            <div class="map-container">
                <div class="map-header">
                    <h2 class="map-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Peta Gedung & Status
                    </h2>
                    <button class="refresh-btn" onclick="refreshMap()">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </button>
                </div>
                <div id="map"></div>
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #28a745;"></div>
                        <span>Semua Online</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #ffc107;"></div>
                        <span>Sebagian Offline</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #dc3545;"></div>
                        <span>Semua Offline</span>
                    </div>
                </div>
            </div>

            <!-- Phone List -->
            <div class="phone-list">
                <h3 style="margin-bottom: 1rem; color: #333;">
                    <i class="fas fa-list"></i>
                    Status Telepon
                </h3>
                
                <div class="phone-item" onclick="showPhoneDetails('TEL001')">
                    <div class="phone-status online"></div>
                    <div class="phone-info">
                        <div class="phone-name">Reception Phone</div>
                        <div class="phone-details">192.168.1.101 • Ext: 101</div>
                        <div class="phone-details">Gedung Utama - Lantai 1</div>
                    </div>
                    <div class="phone-meta">
                        <div>42ms</div>
                        <div>99.8%</div>
                    </div>
                </div>

                <div class="phone-item" onclick="showPhoneDetails('TEL002')">
                    <div class="phone-status online"></div>
                    <div class="phone-info">
                        <div class="phone-name">IT Department</div>
                        <div class="phone-details">192.168.1.102 • Ext: 201</div>
                        <div class="phone-details">Gedung Utama - Lantai 2</div>
                    </div>
                    <div class="phone-meta">
                        <div>38ms</div>
                        <div>99.9%</div>
                    </div>
                </div>

                <div class="phone-item" onclick="showPhoneDetails('PBX001')">
                    <div class="phone-status offline blinking"></div>
                    <div class="phone-info">
                        <div class="phone-name">PBX Server</div>
                        <div class="phone-details">192.168.1.100 • Main PBX</div>
                        <div class="phone-details">Gedung Utama - Server Room</div>
                    </div>
                    <div class="phone-meta">
                        <div>Timeout</div>
                        <div>95.2%</div>
                    </div>
                </div>

                <div class="phone-item" onclick="showPhoneDetails('TEL003')">
                    <div class="phone-status online"></div>
                    <div class="phone-info">
                        <div class="phone-name">HR Department</div>
                        <div class="phone-details">192.168.1.201 • Ext: 301</div>
                        <div class="phone-details">Gedung Annex - Lantai 1</div>
                    </div>
                    <div class="phone-meta">
                        <div>52ms</div>
                        <div>98.5%</div>
                    </div>
                </div>

                <div class="phone-item" onclick="showPhoneDetails('TEL004')">
                    <div class="phone-status offline blinking"></div>
                    <div class="phone-info">
                        <div class="phone-name">Security Post</div>
                        <div class="phone-details">192.168.1.301 • Ext: 401</div>
                        <div class="phone-details">Warehouse - Security</div>
                    </div>
                    <div class="phone-meta">
                        <div>Timeout</div>
                        <div>92.1%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Panel -->
        <div class="activities-panel">
            <h3 style="margin-bottom: 1rem; color: #333;">
                <i class="fas fa-bell"></i>
                Aktivitas Terbaru
            </h3>
            
            <div class="activity-item">
                <div class="activity-icon alert">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">PBX Server Offline</div>
                    <div class="activity-desc">Server PBX utama tidak merespons. Notifikasi Telegram terkirim.</div>
                    <div class="activity-time">2 menit yang lalu</div>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon alert">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Security Post Offline</div>
                    <div class="activity-desc">Telepon pos keamanan tidak dapat dihubungi.</div>
                    <div class="activity-time">5 menit yang lalu</div>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon recovery">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Maintenance Completed</div>
                    <div class="activity-desc">Maintenance terjadwal pada Gedung Annex selesai.</div>
                    <div class="activity-time">1 jam yang lalu</div>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon recovery">
                    <i class="fas fa-wifi"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">All Systems Restored</div>
                    <div class="activity-desc">Semua telepon kembali online setelah gangguan jaringan.</div>
                    <div class="activity-time">3 jam yang lalu</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map
       var map = L.map('map').setView([-8.173358, 112.684885], 17);

      L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}&key=YOUR_API_KEY', {
                maxZoom: 20,
                attribution: 'Map data © Google'
            }).addTo(map);

        // Sample building data
        var buildings = [
            {
                id: 1,
                name: "Gedung Utama",
                lat: -8.173500,
                lng: 112.684700,
                phones: [
                    { name: "Reception Phone", status: "online" },
                    { name: "IT Department", status: "online" },
                    { name: "PBX Server", status: "offline" }
                ]
            },
            {
                id: 2,
                name: "Gedung Annex",
                lat: -8.173200,
                lng: 112.684700,                phones: [
                    { name: "HR Department", status: "online" }
                ]
            },
            {
                id: 3,
                name: "Warehouse",
                lat: -8.173000,
                lng: 112.684700,
                phones: [
                    { name: "Security Post", status: "offline" }
                ]
            }
        ];

        // Add markers to map
        buildings.forEach(function(building) {
            var onlineCount = building.phones.filter(p => p.status === 'online').length;
            var totalCount = building.phones.length;
            var statusColor;
            
            if (onlineCount === totalCount) {
                statusColor = '#28a745'; // Green - all online
            } else if (onlineCount > 0) {
                statusColor = '#ffc107'; // Yellow - partial online
            } else {
                statusColor = '#dc3545'; // Red - all offline
            }

            var marker = L.circleMarker([building.lat, building.lng], {
                color: statusColor,
                fillColor: statusColor,
                fillOpacity: 0.8,
                radius: 15,
                weight: 3
            }).addTo(map);

            var popupContent = `
                <div style="font-family: Arial, sans-serif;">
                    <h4 style="margin: 0 0 10px 0; color: #333;">${building.name}</h4>
                    <p style="margin: 0 0 10px 0; color: #666;">Status: ${onlineCount}/${totalCount} Online</p>
                    <div style="max-height: 150px; overflow-y: auto;">
                        ${building.phones.map(phone => `
                            <div style="display: flex; align-items: center; margin: 5px 0;">
                                <span style="
                                    width: 8px; 
                                    height: 8px; 
                                    border-radius: 50%; 
                                    background: ${phone.status === 'online' ? '#28a745' : '#dc3545'};
                                    margin-right: 8px;
                                    display: inline-block;
                                "></span>
                                <span style="font-size: 14px;">${phone.name}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
        });

        // Functions
        function refreshMap() {
            // Simulate refresh
            location.reload();
        }

        function showPhoneDetails(phoneId) {
            alert(`Detail telepon ${phoneId} akan ditampilkan di modal atau halaman detail`);
        }

        // Auto refresh every 30 seconds
        setInterval(function() {
            console.log('Auto refresh...');
            // In real implementation, this would update the data via AJAX
        }, 30000);

        // Update last refresh time
        setInterval(function() {
            var now = new Date();
            var timeStr = now.toLocaleTimeString('id-ID');
            // Update timestamp in UI if needed
        }, 1000);
    </script>
</body>
</html>

 <!-- manage.blade.php----------------------------------------------------------------------------- -->

 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet CRUD System - Building & Phone Management</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            background: white/95;
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #feca57 0%, #ff9ff3 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%);
        }
        
        #map {
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.1);
        }
        
        .legend {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .status-list {
            max-height: 400px;
            overflow-y: auto;
            space-y: 10px;
        }
        
        .status-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255,255,255,0.5);
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 10px;
        }
        
        .status-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }
        
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .online { background: #10b981; }
        .offline { background: #ef4444; }
        .partial { background: #f59e0b; }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #374151;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .nodes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .nodes-table th, .nodes-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .nodes-table th {
            background: rgba(102, 126, 234, 0.1);
            font-weight: 600;
        }
        
        .nodes-table tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse { animation: pulse 2s infinite; }
        
        @media (max-width: 768px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="margin: 0; color: #374151; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-building"></i>
                Sistem Manajemen Gedung & Telepon
            </h1>
            <p style="margin: 10px 0 0 0; color: #6b7280;">Kelola lokasi gedung dan monitor status telepon secara real-time</p>
        </div>

        <!-- Main Content -->
        <div class="main-grid">
            <!-- Map Section -->
            <div class="card">
                <div class="card-header">
                    <h2 style="margin: 0; color: #374151; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-map-marker-alt"></i>
                        Peta Gedung & Status Telepon
                    </h2>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn" onclick="openAddModal()">
                            <i class="fas fa-plus"></i>
                            Tambah Node
                        </button>
                        <button class="btn" onclick="refreshMap()">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                    </div>
                </div>
                
                <div id="map"></div>
                
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-dot online animate-pulse"></div>
                        <span>Online</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot offline"></div>
                        <span>Offline</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot partial"></div>
                        <span>Partial</span>
                    </div>
                </div>
            </div>

            <!-- Status List -->
            <div class="card">
                <h3 style="margin: 0 0 20px 0; color: #374151; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-list"></i>
                    Status Real-time
                </h3>
                
                <div class="status-list" id="phone-status-list">
                    <!-- Status items will be populated here -->
                </div>
            </div>
        </div>

        <!-- Nodes Management Table -->
        <div class="card">
            <h3 style="margin: 0 0 20px 0; color: #374151; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-table"></i>
                Manajemen Node
            </h3>
            
            <table class="nodes-table" id="nodes-table">
                <thead>
                    <tr>
                        <th>Nama Gedung</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Koordinat</th>
                        <th>Uptime</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="nodes-table-body">
                    <!-- Table rows will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="nodeModal" class="modal">
        <div class="modal-content">
            <h3 id="modal-title" style="margin: 0 0 20px 0; color: #374151;">Tambah Node Baru</h3>
            <form id="nodeForm">
                <div class="form-group">
                    <label for="nodeName">Nama Gedung:</label>
                    <input type="text" id="nodeName" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="nodeIP">IP Address:</label>
                    <input type="text" id="nodeIP" name="ip" required pattern="^(\d{1,3}\.){3}\d{1,3}$">
                </div>
                
                <div class="form-group">
                    <label for="nodeStatus">Status:</label>
                    <select id="nodeStatus" name="status" required>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                        <option value="partial">Partial</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nodeLatitude">Latitude:</label>
                    <input type="number" id="nodeLatitude" name="latitude" step="any" required>
                </div>
                
                <div class="form-group">
                    <label for="nodeLongitude">Longitude:</label>
                    <input type="number" id="nodeLongitude" name="longitude" step="any" required>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-danger" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    
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
                    <div style="font-family: sans-serif; padding: 10px; min-width: 200px;">
                        <h4 style="margin: 0 0 10px 0; color: #374151;">${node.name}</h4>
                        <div style="display: grid; gap: 5px; font-size: 13px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>Status:</span>
                                <span style="font-weight: bold; color: ${color};">${node.status}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>IP:</span>
                                <span style="font-family: monospace;">${node.ip}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Last Ping:</span>
                                <span>${node.lastPing}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Uptime:</span>
                                <span style="font-weight: bold;">${node.uptime}</span>
                            </div>
                        </div>
                        <div style="margin-top: 10px; display: flex; gap: 5px;">
                            <button onclick="editNode(${node.id})" style="padding: 5px 10px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteNode(${node.id})" style="padding: 5px 10px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-trash"></i> Hapus
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
                statusItem.className = 'status-item';
                statusItem.onclick = () => focusOnNode(node.id);
                
                const statusClass = node.status === 'online' ? 'online animate-pulse' : 
                                  node.status === 'offline' ? 'offline' : 'partial';
                
                statusItem.innerHTML = `
                    <div class="status-dot ${statusClass}"></div>
                    <div style="flex: 1;">
                        <div style="font-weight: bold; color: #374151;">${node.name}</div>
                        <div style="font-size: 12px; color: #6b7280;">${node.ip}</div>
                        <div style="font-size: 11px; color: #9ca3af;">Last ping: ${node.lastPing}</div>
                    </div>
                    <div style="text-align: right; font-size: 11px; color: #6b7280;">
                        <div style="font-weight: bold;">${node.status === 'offline' ? 'Timeout' : '42ms'}</div>
                        <div>${node.uptime}</div>
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
                row.innerHTML = `
                    <td>${node.name}</td>
                    <td style="font-family: monospace;">${node.ip}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center; gap: 5px;">
                            <div class="status-dot ${node.status}" style="width: 8px; height: 8px;"></div>
                            ${node.status}
                        </span>
                    </td>
                    <td style="font-family: monospace; font-size: 12px;">${node.coords[0].toFixed(4)}, ${node.coords[1].toFixed(4)}</td>
                    <td>${node.uptime}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-small" onclick="editNode(${node.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-small" onclick="deleteNode(${node.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-success btn-small" onclick="focusOnNode(${node.id})">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // CRUD Operations

        // Create node
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
            
            // Show success message
            alert('Node berhasil ditambahkan!');
        }

        // Read/Find node
        function findNode(id) {
            return nodes.find(node => node.id === id);
        }

        // Update node
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

        // Delete node
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
            document.getElementById('nodeModal').style.display = 'block';
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
                document.getElementById('nodeModal').style.display = 'block';
            }
        }

        function closeModal() {
            document.getElementById('nodeModal').style.display = 'none';
            editingNodeId = null;
        }

        // Focus on node
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

        // Update all displays
        function updateAll() {
            updateMapMarkers();
            updateStatusList();
            updateNodesTable();
        }

        // Refresh map
        function refreshMap() {
            // Simulate status updates
            nodes.forEach(node => {
                if (Math.random() > 0.8) { // 20% chance to change status
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
</body>
</html>