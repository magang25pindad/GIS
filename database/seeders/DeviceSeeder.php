<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            [
                'name' => 'Gedung A',
                'ip' => '192.168.1.101',
                'status' => 'online',
                'latitude' => -8.173500,
                'longitude' => 112.684700,
            ],
            [
                'name' => 'Gedung B',
                'ip' => '192.168.1.102',
                'status' => 'offline',
                'latitude' => -8.173100,
                'longitude' => 112.684200,
            ],
            [
                'name' => 'Gedung C',
                'ip' => '192.168.1.103',
                'status' => 'partial',
                'latitude' => -8.172800,
                'longitude' => 112.685000,
            ],
        ];

        foreach ($devices as $device) {
            Device::create($device);
        }
    }
}
