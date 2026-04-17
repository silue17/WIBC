<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Achievement;
use App\Models\NewsArticle;
use App\Models\GalleryItem;

class PageController extends Controller
{
    public function newsPage()
    {
        $news    = NewsArticle::orderBy('sort_order')->get();
        $contact = SiteSetting::get('contact', [
            'email' => 'worldibconsulting@gmail.com',
            'phone' => '+225 07 12 70 01 67',
        ]);
        return view('actualites', compact('news', 'contact'));
    }

    public function newsPhoto($id)
    {
        $article = NewsArticle::findOrFail($id);

        if (empty($article->photo)) {
            abort(404);
        }

        // La photo est stockée en base64 : data:image/jpeg;base64,....
        if (preg_match('/^data:([a-z\/]+);base64,(.+)$/i', $article->photo, $m)) {
            $mime    = $m[1];
            $binary  = base64_decode($m[2]);
        } else {
            abort(404);
        }

        return response($binary, 200)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    public function newsDetail($id)
    {
        $article  = NewsArticle::findOrFail($id);
        $others   = NewsArticle::where('id', '!=', $id)->orderBy('sort_order')->take(3)->get();
        $contact  = SiteSetting::get('contact', [
            'email' => 'worldibconsulting@gmail.com',
            'phone' => '+225 07 12 70 01 67',
        ]);
        return view('actualite-detail', compact('article', 'others', 'contact'));
    }

    public function index()
    {
        $hero = SiteSetting::get('hero', [
            'title'       => 'WORLD INNOVATIONS BUSINESS CONSULTING',
            'tagline'     => 'Une vision mondiale, un impact africain',
            'description' => 'Conseil – Innovation – Stratégie – Impact. Nous accompagnons entreprises et institutions vers l\'excellence en Afrique et au-delà.',
            'btnText'     => 'Nous contacter',
        ]);

        $about = SiteSetting::get('about', [
            'title'        => "Transformer l'Afrique par l'innovation",
            'description1' => 'World Innovations Business Consulting (WIBC) est une société ivoirienne de conseil fondée par trois jeunes entrepreneurs visionnaires.',
            'description2' => 'Nous accompagnons entreprises, institutions publiques, organisations et personnalités via trois pôles spécialisés.',
            'badge'        => '100% Ivoirien',
        ]);

        $contact = SiteSetting::get('contact', [
            'address' => 'Angré, Cocody, Abidjan',
            'email'   => 'worldibconsulting@gmail.com',
            'phone'   => '+225 07 12 70 01 67',
            'rccm'    => 'CI-ABJ-03-2025-B12-04372',
        ]);

        $services     = Service::orderBy('sort_order')->get();
        $team         = TeamMember::orderBy('sort_order')->get();
        $achievements = Achievement::orderBy('sort_order')->get();
        $news         = NewsArticle::orderBy('sort_order')->get();
        $gallery      = GalleryItem::orderBy('sort_order')->get();

        return view('pagecomplet', compact(
            'hero', 'about', 'contact',
            'services', 'team', 'achievements', 'news', 'gallery'
        ));
    }
}
