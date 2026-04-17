<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        return response()->json(Achievement::orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'photo'       => 'nullable|string',
        ]);

        $data['sort_order'] = Achievement::max('sort_order') + 1;
        $achievement = Achievement::create($data);

        return response()->json($achievement, 201);
    }

    public function update(Request $request, Achievement $achievement)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:300',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'photo'       => 'nullable|string',
        ]);

        $achievement->update($data);

        return response()->json($achievement->fresh());
    }

    public function destroy(Achievement $achievement)
    {
        $achievement->delete();
        return response()->json(['ok' => true]);
    }
}
