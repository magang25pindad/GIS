<?php

namespace App\Http\Controllers\Api;

use App\Models\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    public function index()
    {
        return Node::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'ip' => 'required|ip',
            'status' => 'required|in:online,offline,partial',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $validated['uptime'] = '99.9%';       // default uptime
        $validated['last_ping'] = '1s ago';   // default last ping

        return Node::create($validated);
    }

    public function show(Node $node)
    {
        return $node;
    }

    public function update(Request $request, Node $node)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'ip' => 'required|ip',
            'status' => 'required|in:online,offline,partial',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $node->update($validated);
        return $node;
    }

    public function destroy(Node $node)
    {
        $node->delete();
        return response()->noContent();
    }
}
