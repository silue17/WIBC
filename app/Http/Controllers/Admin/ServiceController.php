<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(Service::orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:300',
            'description' => 'nullable|string|max:2000',
        ]);

        $data['sort_order'] = Service::max('sort_order') + 1;
        $service = Service::create($data);

        return response()->json($service, 201);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:300',
            'description' => 'nullable|string|max:2000',
        ]);

        $service->update($data);

        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['ok' => true]);
    }
}
