<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(TeamMember::orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:300',
            'position' => 'nullable|string|max:300',
            'bio'      => 'nullable|string|max:2000',
            'photo'    => 'nullable|string',
            'social'   => 'nullable|array',
        ]);

        $data['sort_order'] = TeamMember::max('sort_order') + 1;
        $member = TeamMember::create($data);

        return response()->json($member, 201);
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:300',
            'position' => 'nullable|string|max:300',
            'bio'      => 'nullable|string|max:2000',
            'photo'    => 'nullable|string',
            'social'   => 'nullable|array',
        ]);

        $teamMember->update($data);

        return response()->json($teamMember->fresh());
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return response()->json(['ok' => true]);
    }
}
