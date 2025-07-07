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