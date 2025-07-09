<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Connectors;

use App\Http\Controllers\Api\NodeController;

// CRUD API Endpoint
Route::get('/nodes', [NodeController::class, 'index']);
Route::post('/nodes', [NodeController::class, 'store']);
Route::put('/nodes/{node}', [NodeController::class, 'update']);
Route::delete('/nodes/{node}', [NodeController::class, 'destroy']);
