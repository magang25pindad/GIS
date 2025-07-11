//  // resources/js/device-map.js


import L from 'leaflet';
import 'leaflet/dist/leaflet.css'; // <- CSS Leaflet

import 'leaflet-draw'; 
import 'leaflet-draw/dist/leaflet.draw.css'; // <- CSS Draw

// Ambil data dari Blade via window
let map;
let markers = [];
let devices = window.devices || [];
let editingDeviceId = null;
let tempMarker = null;

// Initialize map
function initMap() {
    map = L.map('map').setView([-8.173358, 112.684885], 17);

    // Basemap layers
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20,
        attribution: '© OpenStreetMap contributors'
    });

    const googleLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}&key=YOUR_API_KEY', {
        maxZoom: 20,
        attribution: 'Map data © Google'
    });

    osmLayer.addTo(map); // default base layer

    const baseMaps = {
        "OpenStreetMap": osmLayer,
        "Google Satellite": googleLayer
    };

    L.control.layers(baseMaps).addTo(map);

    // Tambahkan fitur drawing
    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
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
        const layer = e.layer;
        drawnItems.addLayer(layer);
        const geojson = layer.toGeoJSON();
        console.log('GeoJSON hasil gambar:', JSON.stringify(geojson));
    });

    // Klik peta untuk menambahkan device baru
    map.on('click', function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        document.getElementById('deviceLatitude').value = lat.toFixed(6);
        document.getElementById('deviceLongitude').value = lng.toFixed(6);

        if (tempMarker) map.removeLayer(tempMarker);

        tempMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'temp-marker',
                html: '<div style="background-color: #ff6b6b; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        }).addTo(map);

        openAddModal();
    });

    updateMapMarkers();
}


function updateMapMarkers() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    devices.forEach(device => {
        const color = getStatusColor(device.status);

        const marker = L.circleMarker([device.latitude, device.longitude], {
            radius: 12,
            color: color,
            fillColor: color,
            fillOpacity: 0.8,
            weight: 3
        }).addTo(map);

        const popupContent = `
            <div style="font-family: sans-serif; padding: 10px; min-width: 200px;">
                <h4 style="margin: 0 0 10px 0; color: #374151;">${device.name}</h4>
                <div style="font-size: 13px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Status:</span>
                        <span style="font-weight: bold; color: ${color};">${device.status}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>IP:</span>
                        <span style="font-family: monospace;">${device.ip}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Koordinat:</span>
                        <span style="font-family: monospace;">${parseFloat(device.latitude).toFixed(4)}, ${parseFloat(device.longitude).toFixed(4)}</span>
                    </div>
                </div>
                <div style="margin-top: 10px; display: flex; gap: 5px;">
                    <button onclick="editDevice(${device.id})" style="padding: 5px 10px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteDevice(${device.id})" style="padding: 5px 10px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent);
        markers.push(marker);
    });
}

function getStatusColor(status) {
    switch (status) {
        case 'online': return '#10b981';
        case 'offline': return '#ef4444';
        case 'partial': return '#f59e0b';
        default: return '#6b7280';
    }
}

window.openAddModal = function () {
    document.getElementById('modal-title').textContent = 'Tambah Device Baru';
    document.getElementById('deviceForm').reset();
    document.getElementById('deviceForm').action = window.routes.store;
    document.getElementById('device_id').value = '';
    editingDeviceId = null;
    document.getElementById('deviceModal').style.display = 'block';
};

window.editDevice = function (id) {
    const device = devices.find(d => d.id === id);
    if (device) {
        document.getElementById('modal-title').textContent = 'Edit Device';
        document.getElementById('deviceName').value = device.name;
        document.getElementById('deviceIP').value = device.ip;
        document.getElementById('deviceStatus').value = device.status;
        document.getElementById('deviceLatitude').value = device.latitude;
        document.getElementById('deviceLongitude').value = device.longitude;
        document.getElementById('device_id').value = device.id;
        document.getElementById('deviceForm').action = window.routes.update.replace(':id', device.id);

        let methodField = document.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            document.getElementById('deviceForm').appendChild(methodField);
        }
        methodField.value = 'PUT';

        editingDeviceId = id;
        document.getElementById('deviceModal').style.display = 'block';
    }
};

window.deleteDevice = function (id) {
    if (confirm('Yakin ingin menghapus device ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.routes.destroy.replace(':id', id);

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = window.routes.csrf;

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
};

window.closeModal = function () {
    document.getElementById('deviceModal').style.display = 'none';
    editingDeviceId = null;
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }
};

window.focusOnDevice = function (id) {
    const device = devices.find(d => d.id === id);
    if (device) {
        map.setView([device.latitude, device.longitude], 18);
        const marker = markers.find(m =>
            Math.abs(m.getLatLng().lat - device.latitude) < 0.0001 &&
            Math.abs(m.getLatLng().lng - device.longitude) < 0.0001
        );
        if (marker) {
            marker.openPopup();
        }
    }
};

window.refreshMap = function () {
    location.reload();
};

document.addEventListener('DOMContentLoaded', function () {
    initMap();

    document.getElementById('deviceModal').addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
});


// import L from 'leaflet';
// import 'leaflet-draw';

// // Anda bisa akses `window.devices`, `window.routes`, dll dari Blade
// let map;
// let markers = [];
// let devices = window.devices || [];
// let editingDeviceId = null;
// let tempMarker = null;

// // isi semua function JS lainnya di sini...

//         // Global variables
//         let map;
//         let markers = [];
//         let devices = @json($devices);
//         let editingDeviceId = null;
//         let tempMarker = null;

//         // Initialize map
//         function initMap() {
//             map = L.map('map').setView([-8.173358, 112.684885], 17);

//             //OpenStreetMap layer
//             const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//                 maxZoom: 20,
//                 attribution: '© OpenStreetMap contributors'
//             });

//             osmLayer.addTo(map);

//             // Add click event to map for adding devices
//             map.on('click', function(e) {
//                 const lat = e.latlng.lat;
//                 const lng = e.latlng.lng;
                
//                 // Fill the form with coordinates
//                 document.getElementById('deviceLatitude').value = lat.toFixed(6);
//                 document.getElementById('deviceLongitude').value = lng.toFixed(6);
                
//                 // Add temporary marker
//                 if (tempMarker) {
//                     map.removeLayer(tempMarker);
//                 }
                
//                 tempMarker = L.marker([lat, lng], {
//                     icon: L.divIcon({
//                         className: 'temp-marker',
//                         html: '<div style="background-color: #ff6b6b; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
//                         iconSize: [20, 20],
//                         iconAnchor: [10, 10]
//                     })
//                 }).addTo(map);
                
//                 // Open modal
//                 openAddModal();
//             });

//             updateMapMarkers();
//         }

//         // Update map markers
//         function updateMapMarkers() {
//             // Clear existing markers
//             markers.forEach(marker => map.removeLayer(marker));
//             markers = [];

//             // Add new markers
//             devices.forEach(function(device) {
//                 const color = getStatusColor(device.status);

//                 const marker = L.circleMarker([device.latitude, device.longitude], {
//                     radius: 12,
//                     color: color,
//                     fillColor: color,
//                     fillOpacity: 0.8,
//                     weight: 3
//                 }).addTo(map);

//                 const popupContent = `
//                     <div style="font-family: sans-serif; padding: 10px; min-width: 200px;">
//                         <h4 style="margin: 0 0 10px 0; color: #374151;">${device.name}</h4>
//                         <div style="display: grid; gap: 5px; font-size: 13px;">
//                             <div style="display: flex; justify-content: space-between;">
//                                 <span>Status:</span>
//                                 <span style="font-weight: bold; color: ${color};">${device.status}</span>
//                             </div>
//                             <div style="display: flex; justify-content: space-between;">
//                                 <span>IP:</span>
//                                 <span style="font-family: monospace;">${device.ip}</span>
//                             </div>
//                             <div style="display: flex; justify-content: space-between;">
//                                 <span>Koordinat:</span>
//                                 <span style="font-family: monospace;">${parseFloat(device.latitude).toFixed(4)}, ${parseFloat(device.longitude).toFixed(4)}</span>
//                             </div>
//                         </div>
//                         <div style="margin-top: 10px; display: flex; gap: 5px;">
//                             <button onclick="editDevice(${device.id})" style="padding: 5px 10px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px;">
//                                 <i class="fas fa-edit"></i> Edit
//                             </button>
//                             <button onclick="deleteDevice(${device.id})" style="padding: 5px 10px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px;">
//                                 <i class="fas fa-trash"></i> Hapus
//                             </button>
//                         </div>
//                     </div>
//                 `;

//                 marker.bindPopup(popupContent);
//                 markers.push(marker);
//             });
//         }

//         // Get status color
//         function getStatusColor(status) {
//             switch(status) {
//                 case 'online': return '#10b981';
//                 case 'offline': return '#ef4444';
//                 case 'partial': return '#f59e0b';
//                 default: return '#6b7280';
//             }
//         }

//         // Modal functions
//         function openAddModal() {
//             document.getElementById('modal-title').textContent = 'Tambah Device Baru';
//             document.getElementById('deviceForm').reset();
//             document.getElementById('deviceForm').action = '{{ route("devices.store") }}';
//             document.getElementById('device_id').value = '';
//             editingDeviceId = null;
//             document.getElementById('deviceModal').style.display = 'block';
//         }

//         function editDevice(id) {
//             const device = devices.find(d => d.id === id);
//             if (device) {
//                 document.getElementById('modal-title').textContent = 'Edit Device';
//                 document.getElementById('deviceName').value = device.name;
//                 document.getElementById('deviceIP').value = device.ip;
//                 document.getElementById('deviceStatus').value = device.status;
//                 document.getElementById('deviceLatitude').value = device.latitude;
//                 document.getElementById('deviceLongitude').value = device.longitude;
//                 document.getElementById('device_id').value = device.id;
//                 document.getElementById('deviceForm').action = '{{ route("devices.update", ":id") }}'.replace(':id', device.id);
                
//                 // Add method spoofing for PUT request
//                 let methodField = document.querySelector('input[name="_method"]');
//                 if (!methodField) {
//                     methodField = document.createElement('input');
//                     methodField.type = 'hidden';
//                     methodField.name = '_method';
//                     document.getElementById('deviceForm').appendChild(methodField);
//                 }
//                 methodField.value = 'PUT';
                
//                 editingDeviceId = id;
//                 document.getElementById('deviceModal').style.display = 'block';
//             }
//         }

//         function deleteDevice(id) {
//             if (confirm('Yakin ingin menghapus device ini?')) {
//                 // Create form for delete
//                 const form = document.createElement('form');
//                 form.method = 'POST';
//                 form.action = '{{ route("devices.destroy", ":id") }}'.replace(':id', id);
                
//                 const csrfField = document.createElement('input');
//                 csrfField.type = 'hidden';
//                 csrfField.name = '_token';
//                 csrfField.value = '{{ csrf_token() }}';
                
//                 const methodField = document.createElement('input');
//                 methodField.type = 'hidden';
//                 methodField.name = '_method';
//                 methodField.value = 'DELETE';
                
//                 form.appendChild(csrfField);
//                 form.appendChild(methodField);
//                 document.body.appendChild(form);
//                 form.submit();
//             }
//         }

//         function closeModal() {
//             document.getElementById('deviceModal').style.display = 'none';
//             editingDeviceId = null;
            
//             // Remove temporary marker
//             if (tempMarker) {
//                 map.removeLayer(tempMarker);
//                 tempMarker = null;
//             }
//         }

//         // Focus on device
//         function focusOnDevice(id) {
//             const device = devices.find(d => d.id === id);
//             if (device) {
//                 map.setView([device.latitude, device.longitude], 18);
//                 const marker = markers.find(m => 
//                     Math.abs(m.getLatLng().lat - device.latitude) < 0.0001 && 
//                     Math.abs(m.getLatLng().lng - device.longitude) < 0.0001
//                 );
//                 if (marker) {
//                     marker.openPopup();
//                 }
//             }
//         }

//         // Refresh map
//         function refreshMap() {
//             location.reload();
//         }

//         // Close modal when clicking outside
//         document.getElementById('deviceModal').addEventListener('click', function(e) {
//             if (e.target === this) {
//                 closeModal();
//             }
//         });

//         // Initialize everything when page loads
//         document.addEventListener('DOMContentLoaded', function () {
//             initMap();
//         });

//         // Keyboard shortcuts
//         document.addEventListener('keydown', function(e) {
//             if (e.key === 'Escape') {
//                 closeModal();
//             }
//         });