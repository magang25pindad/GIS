<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Node;

class NodeSeeder extends Seeder
{
    public function run()
    {
        Node::create([
            'name' => 'Gedung A',
            'ip' => '192.168.1.101',
            'status' => 'online',
            'latitude' => -8.173500,
            'longitude' => 112.684700,
            'uptime' => 99.8,
        ]);

        Node::create([
            'name' => 'Gedung B',
            'ip' => '192.168.1.102',
            'status' => 'offline',
            'latitude' => -8.173100,
            'longitude' => 112.684200,
            'uptime' => 85.2,
        ]);

        Node::create([
            'name' => 'Gedung C',
            'ip' => '192.168.1.103',
            'status' => 'online',
            'latitude' => -8.172800,
            'longitude' => 112.685000,
            'uptime' => 99.9,
        ]);
    }
}

