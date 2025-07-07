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
