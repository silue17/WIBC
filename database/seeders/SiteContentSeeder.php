<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Achievement;
use App\Models\NewsArticle;
use App\Models\GalleryItem;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        // Hero
        SiteSetting::set('hero', [
            'title'       => 'WORLD INNOVATIONS BUSINESS CONSULTING',
            'tagline'     => 'Une vision mondiale, un impact africain',
            'description' => 'Conseil – Innovation – Stratégie – Impact. Nous accompagnons entreprises et institutions vers l\'excellence en Afrique et au-delà.',
            'btnText'     => 'Nous contacter',
        ]);

        // About
        SiteSetting::set('about', [
            'title'        => "Transformer l'Afrique par l'innovation",
            'description1' => 'World Innovations Business Consulting (WIBC) est une société ivoirienne de conseil fondée par trois jeunes entrepreneurs visionnaires.',
            'description2' => 'Nous accompagnons entreprises, institutions publiques, organisations et personnalités via trois pôles spécialisés.',
            'badge'        => '100% Ivoirien',
        ]);

        // Contact
        SiteSetting::set('contact', [
            'address' => 'Angré, Cocody, Abidjan',
            'email'   => 'worldibconsulting@gmail.com',
            'phone'   => '+225 07 12 70 01 67',
            'rccm'    => 'CI-ABJ-03-2025-B12-04372',
        ]);

        // Services
        $services = [
            ['name' => 'Strategy & Business',  'description' => 'Accompagner les structures dans leurs choix stratégiques et leur développement.'],
            ['name' => 'Tech & Innovation',    'description' => 'Concevoir et déployer des projets technologiques et d\'innovation durable.'],
            ['name' => 'Influence & Events',   'description' => 'Valoriser l\'image et développer des stratégies de visibilité et d\'influence.'],
        ];
        foreach ($services as $i => $s) {
            Service::create(array_merge($s, ['sort_order' => $i]));
        }

        // Team
        $team = [
            ['name' => 'Miwouguih Secongo Clotchor',          'position' => 'Directeur Général',            'bio' => 'Passionné par le management et le développement des entreprises africaines.'],
            ['name' => 'Johann Coulibaly',                    'position' => 'Directeur Général Adjoint',    'bio' => 'Expert en business et relations internationales.'],
            ['name' => 'Ousmane Tidiane Aziz TOURÉ-DERUOTH',  'position' => 'Directeur Administratif et Financier', 'bio' => 'Expertise financière et structuration d\'entreprise.'],
        ];
        foreach ($team as $i => $m) {
            TeamMember::create(array_merge($m, ['sort_order' => $i, 'photo' => null]));
        }

        // Achievements
        $achievements = [
            ['title' => 'African MMA League',     'category' => 'Sport',       'description' => 'Gestion de la billetterie et relations institutionnelles pour la ligue africaine.'],
            ['title' => "Gestion d'image",         'category' => 'Carrière',    'description' => 'Accompagnement de Ruth Gbagbi, championne olympique de Taekwondo.'],
            ['title' => 'Mise en relation B2B',    'category' => 'Partenariat', 'description' => 'Facilitation logistique pour Endeavour Mining.'],
            ['title' => 'Trottinette Électrique',  'category' => 'Innovation',  'description' => 'Projet de mobilité durable à Abidjan.'],
        ];
        foreach ($achievements as $i => $a) {
            Achievement::create(array_merge($a, ['sort_order' => $i]));
        }

        // News
        $news = [
            ['title' => "WIBC expansion Afrique de l'Ouest", 'date' => 'Mars 2025',    'description' => 'Une nouvelle ère pour le conseil stratégique en Côte d\'Ivoire et au-delà.'],
            ['title' => 'African MMA League — record',        'date' => 'Février 2025', 'description' => '15 000 spectateurs ont assisté à l\'événement phare de la saison.'],
            ['title' => 'Mobilité durable — phase pilote',    'date' => 'Janvier 2025', 'description' => 'Lancement officiel du projet de trottinettes électriques à Abidjan.'],
        ];
        foreach ($news as $i => $n) {
            NewsArticle::create(array_merge($n, ['sort_order' => $i]));
        }

        // Gallery
        $photos = ['/photos/1.jpeg', '/photos/2.jpeg', '/photos/3.jpeg', '/photos/4.jpeg', '/photos/5.jpeg'];
        foreach ($photos as $i => $url) {
            GalleryItem::create(['url' => $url, 'sort_order' => $i]);
        }
    }
}
