<?php

use App\Models\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function index()
    {
        return response()->json(Node::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'ip' => 'required|ip',
            'status' => 'required|in:online,offline,partial',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        return Node::create($validated);
    }

    public function update(Request $request, Node $node)
    {
        $validated = $request->validate([
            'name' => 'required',
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
        return response()->json(['message' => 'Node deleted']);
    }
}
