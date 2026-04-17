<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return response()->json(GalleryItem::orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'url' => 'required|string',
        ]);

        $data['sort_order'] = GalleryItem::max('sort_order') + 1;
        $item = GalleryItem::create($data);

        return response()->json($item, 201);
    }

    public function destroy(GalleryItem $gallery)
    {
        $gallery->delete();
        return response()->json(['ok' => true]);
    }
}
