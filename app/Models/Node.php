<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = [
        'name', 'ip', 'status', 'latitude', 'longitude', 'uptime', 'last_ping'
    ];
}

