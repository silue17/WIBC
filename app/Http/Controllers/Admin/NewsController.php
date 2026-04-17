<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return response()->json(NewsArticle::orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'date'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'photo'       => 'nullable|string',
        ]);

        $data['sort_order'] = NewsArticle::max('sort_order') + 1;
        $article = NewsArticle::create($data);

        return response()->json($article, 201);
    }

    public function update(Request $request, NewsArticle $news)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'date'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'photo'       => 'nullable|string',
        ]);

        $news->update($data);

        return response()->json($news->fresh());
    }

    public function destroy(NewsArticle $news)
    {
        $news->delete();
        return response()->json(['ok' => true]);
    }
}
