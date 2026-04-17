<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Achievement;
use App\Models\NewsArticle;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        // ── Site Settings ─────────────────────────────────────────────────

        SiteSetting::set('hero', [
            'title'       => 'WORLD INNOVATIONS BUSINESS CONSULTING',
            'tagline'     => 'Une vision mondiale, un impact africain',
            'description' => 'Conseil – Innovation – Stratégie – Impact. Nous accompagnons entreprises et institutions vers l\'excellence en Afrique et au-delà.',
            'btnText'     => 'Nous contacter',
        ]);

        SiteSetting::set('about', [
            'title'        => "Transformer l'Afrique par l'innovation",
            'description1' => 'World Innovations Business Consulting (WIBC) est une société ivoirienne de conseil fondée par trois jeunes entrepreneurs visionnaires.',
            'description2' => 'Nous accompagnons entreprises, institutions publiques, organisations et personnalités via trois pôles spécialisés.',
            'badge'        => '100% Ivoirien',
        ]);

        SiteSetting::set('contact', [
            'address'   => 'Angré, Cocody, Abidjan',
            'email'     => 'worldibconsulting@gmail.com',
            'phone'     => '+225 07 12 70 01 67',
            'rccm'      => 'CI-ABJ-03-2025-B12-04372',
            'map_lat'   => '5.391215471838068',
            'map_lng'   => '-3.989660318834706',
            'map_embed' => null,
        ]);

        // ── Services ──────────────────────────────────────────────────────

        $services = [
            [
                'name'        => 'Strategy & Business',
                'description' => 'Accompagner les structures dans leurs choix stratégiques et leur développement.',
                'sort_order'  => 0,
            ],
            [
                'name'        => 'Tech & Innovation',
                'description' => 'Concevoir et déployer des projets technologiques et d\'innovation durable.',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Influence & Events',
                'description' => 'Valoriser l\'image et développer des stratégies de visibilité et d\'influence.',
                'sort_order'  => 2,
            ],
        ];

        foreach ($services as $s) {
            Service::create($s);
        }

        // ── Équipe ────────────────────────────────────────────────────────

        $team = [
            [
                'name'       => 'Miwouguih Secongo Clotchor',
                'position'   => 'Directeur Général',
                'bio'        => 'Passionné par le management et le développement des entreprises africaines.',
                'photo'      => null,
                'sort_order' => 0,
            ],
            [
                'name'       => 'Johann Coulibaly',
                'position'   => 'Directeur Général Adjoint',
                'bio'        => 'Expert en business et relations internationales.',
                'photo'      => null,
                'sort_order' => 1,
            ],
            [
                'name'       => 'Ousmane Tidiane Aziz TOURÉ-DERUOTH',
                'position'   => 'Directeur Administratif et Financier',
                'bio'        => 'Expertise financière et structuration d\'entreprise.',
                'photo'      => null,
                'sort_order' => 2,
            ],
        ];

        foreach ($team as $m) {
            TeamMember::create($m);
        }

        // ── Réalisations ──────────────────────────────────────────────────

        $achievements = [
            [
                'title'       => 'African MMA League',
                'category'    => 'Sport',
                'description' => 'Gestion de la billetterie et relations institutionnelles pour la ligue africaine.',
                'photo'       => null,
                'sort_order'  => 0,
            ],
            [
                'title'       => "Gestion d'image",
                'category'    => 'Carrière',
                'description' => 'Accompagnement de Ruth Gbagbi, championne olympique de Taekwondo.',
                'photo'       => null,
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Mise en relation B2B',
                'category'    => 'Partenariat',
                'description' => 'Facilitation logistique pour Endeavour Mining.',
                'photo'       => null,
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Trottinette Électrique',
                'category'    => 'Innovation',
                'description' => 'Projet de mobilité durable à Abidjan.',
                'photo'       => null,
                'sort_order'  => 3,
            ],
        ];

        foreach ($achievements as $a) {
            Achievement::create($a);
        }

        // ── Actualités ────────────────────────────────────────────────────

        $news = [
            [
                'title'       => "WIBC expansion Afrique de l'Ouest",
                'date'        => 'Mars 2025',
                'description' => "Une nouvelle ère pour le conseil stratégique en Côte d'Ivoire et au-delà.",
                'photo'       => null,
                'sort_order'  => 0,
            ],
            [
                'title'       => 'African MMA League — record',
                'date'        => 'Février 2025',
                'description' => "15 000 spectateurs ont assisté à l'événement phare de la saison.",
                'photo'       => null,
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Mobilité durable — phase pilote',
                'date'        => 'Janvier 2025',
                'description' => 'Lancement officiel du projet de trottinettes électriques à Abidjan.',
                'photo'       => null,
                'sort_order'  => 2,
            ],
        ];

        foreach ($news as $n) {
            NewsArticle::create($n);
        }

        // ── Galerie ───────────────────────────────────────────────────────
        // Les photos de la galerie sont des images uploadées via l'admin.
        // Elles ne peuvent pas être incluses dans le seeder (trop volumineuses).
        // Veuillez les uploader manuellement depuis /admin/gallery.
    }
}
