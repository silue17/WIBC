<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

/**
 * Gère Hero, About et Contact (données simples stockées en site_settings)
 */
class SiteController extends Controller
{
    /* ── HERO ── */
    public function heroIndex()
    {
        return view('admin.hero');
    }

    public function heroUpdate(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:500',
            'tagline'     => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'btnText'     => 'nullable|string|max:100',
        ]);

        SiteSetting::set('hero', $data);

        return response()->json(['ok' => true, 'data' => $data]);
    }

    public function heroShow()
    {
        return response()->json(SiteSetting::get('hero', [
            'title'       => 'WORLD INNOVATIONS BUSINESS CONSULTING',
            'tagline'     => 'Une vision mondiale, un impact africain',
            'description' => 'Conseil – Innovation – Stratégie – Impact.',
            'btnText'     => 'Nous contacter',
        ]));
    }

    /* ── ABOUT ── */
    public function aboutIndex()
    {
        return view('admin.about');
    }

    public function aboutUpdate(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:500',
            'description1' => 'nullable|string|max:2000',
            'description2' => 'nullable|string|max:2000',
            'badge'        => 'nullable|string|max:200',
        ]);

        SiteSetting::set('about', $data);

        return response()->json(['ok' => true, 'data' => $data]);
    }

    public function aboutShow()
    {
        return response()->json(SiteSetting::get('about', [
            'title'        => "Transformer l'Afrique par l'innovation",
            'description1' => 'WIBC est une société ivoirienne de conseil fondée par trois jeunes entrepreneurs visionnaires.',
            'description2' => 'Nous accompagnons entreprises et institutions via trois pôles spécialisés.',
            'badge'        => '100% Ivoirien',
        ]));
    }

    /* ── CONTACT ── */
    public function contactIndex()
    {
        return view('admin.contact');
    }

    public function contactUpdate(Request $request)
    {
        $data = $request->validate([
            'address'   => 'nullable|string|max:300',
            'email'     => 'nullable|email|max:300',
            'phone'     => 'nullable|string|max:100',
            'rccm'      => 'nullable|string|max:200',
            'map_lat'   => 'nullable|numeric',
            'map_lng'   => 'nullable|numeric',
            'map_embed' => 'nullable|string|max:2000',
        ]);

        SiteSetting::set('contact', $data);

        return response()->json(['ok' => true, 'data' => $data]);
    }

    public function contactShow()
    {
        return response()->json(SiteSetting::get('contact', [
            'address'   => 'Angré, Cocody, Abidjan',
            'email'     => 'worldibconsulting@gmail.com',
            'phone'     => '+225 07 12 70 01 67',
            'rccm'      => 'CI-ABJ-03-2025-B12-04372',
            'map_lat'   => null,
            'map_lng'   => null,
            'map_embed' => null,
        ]));
    }
}
