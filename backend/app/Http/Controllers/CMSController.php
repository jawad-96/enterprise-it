<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CMSController extends Controller
{
    /**
     * Get a list of resources.
     */
    public function index(Request $request)
    {
        $query = Resource::query();

        if ($request->has('category')) {
            $query->where('category', $request->query('category'));
        }

        $resources = $query->orderBy('published_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $resources
        ]);
    }

    /**
     * Get a single resource by slug.
     */
    public function show($slug)
    {
        $resource = Resource::where('slug', $slug)->first();

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $resource
        ]);
    }

    /**
     * Store a new resource (Admin backend uploads).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|in:sustainability,blog,news',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_url' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['published_at'] = now();

        $resource = Resource::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Resource uploaded successfully.',
            'data' => $resource
        ], 201);
    }
}
