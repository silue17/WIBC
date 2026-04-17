@php
function fmtDate($d) {
    if (!$d) return '';
    $m = [];
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $d, $m)) return "{$m[3]}/{$m[2]}/{$m[1]}";
    return $d;
}
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} — WIBC</title>
    <link rel="icon" type="image/jpeg" href="/photos/logo.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #047847; --green-light: #05a35f;
            --red: #F34244;  --navy: #0a1227;
            --black: #050613; --surface: #eef1f9;
            --border: rgba(10,18,39,0.10);
            --text-muted: rgba(10,18,39,0.52);
            --transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
            --shadow-md: 0 8px 32px rgba(10,18,39,0.12);
            --shadow-lg: 0 20px 60px rgba(10,18,39,0.16);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { font-family:'Inter',sans-serif; background:#f8fafd; color:var(--black); overflow-x:hidden; line-height:1.75; }
        h1,h2,h3,h4 { font-family:'Space Grotesk','Inter',sans-serif; }

        .container { width:100%; max-width:1240px; margin:0 auto; padding:0 28px; }
        .container-sm { width:100%; max-width:780px; margin:0 auto; padding:0 28px; }

        /* Scroll progress */
        #scroll-progress { position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,var(--green),#0564B7,var(--red));z-index:9999;width:0%;transition:width 0.1s linear; }

        /* Header */
        header { position:fixed;width:100%;top:0;z-index:1000; }
        .header-inner { background:rgba(10,14,39,0.96);backdrop-filter:blur(24px);border-bottom:1px solid rgba(255,255,255,0.08);box-shadow:0 4px 30px rgba(0,0,0,0.3); }
        .navbar { display:flex;justify-content:space-between;align-items:center;padding:18px 0; }
        .logo { display:flex;align-items:center;gap:10px;text-decoration:none; }
        .logo-image { width:36px;height:36px;border-radius:8px;object-fit:cover; }
        .logo-text { font-family:'Space Grotesk',sans-serif;font-weight:800;font-size:1.15rem;color:#fff;letter-spacing:-0.5px; }
        .logo-text span { color:#4ade80; }
        .nav-links { display:flex;align-items:center;gap:6px;list-style:none; }
        .nav-links a { color:rgba(255,255,255,0.78);text-decoration:none;font-size:0.88rem;font-weight:500;padding:7px 14px;border-radius:8px;transition:var(--transition); }
        .nav-links a:hover, .nav-links a.active { color:#fff;background:rgba(255,255,255,0.08); }
        .nav-links a.nav-cta { background:var(--green);color:#fff;font-weight:700;padding:8px 18px;border-radius:10px; }
        .nav-links a.nav-cta:hover { background:var(--green-light); }
        .mobile-menu-btn { display:none;background:none;border:none;color:#fff;font-size:1.3rem;cursor:pointer; }
        @media(max-width:768px){
            .nav-links { display:none;flex-direction:column;position:absolute;top:100%;left:0;right:0;background:rgba(10,14,39,0.98);padding:20px;gap:4px; }
            .nav-links.active { display:flex; }
            .mobile-menu-btn { display:block; }
        }

        /* Hero article */
        .article-hero {
            background: var(--navy);
            padding: 120px 0 0;
            position: relative;
            overflow: hidden;
        }
        .article-hero::before {
            content:'';position:absolute;top:-30%;right:-5%;
            width:500px;height:500px;
            background:radial-gradient(circle,rgba(4,120,71,0.12) 0%,transparent 70%);
            pointer-events:none;
        }
        .article-hero-inner { padding: 40px 0 0; position:relative; z-index:1; }
        .article-breadcrumb {
            display:flex;align-items:center;gap:8px;
            font-size:0.78rem;color:rgba(255,255,255,0.45);
            margin-bottom:20px;flex-wrap:wrap;
        }
        .article-breadcrumb a { color:rgba(255,255,255,0.55);text-decoration:none;transition:color 0.2s; }
        .article-breadcrumb a:hover { color:#4ade80; }
        .article-breadcrumb i { font-size:0.6rem; }

        .article-tags { display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px; }
        .article-tag {
            display:inline-flex;align-items:center;gap:6px;
            background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.25);
            color:#4ade80;font-size:0.72rem;font-weight:700;
            letter-spacing:0.8px;text-transform:uppercase;
            padding:5px 12px;border-radius:20px;
        }
        .article-title {
            font-size:clamp(1.6rem,4vw,2.6rem);
            font-weight:800;color:#fff;
            line-height:1.2;margin-bottom:20px;
        }
        .article-meta {
            display:flex;align-items:center;gap:20px;
            padding:18px 0;border-top:1px solid rgba(255,255,255,0.08);
            flex-wrap:wrap;
        }
        .article-meta-item {
            display:flex;align-items:center;gap:8px;
            font-size:0.8rem;color:rgba(255,255,255,0.5);
        }
        .article-meta-item i { color:#4ade80;font-size:0.85rem; }

        /* Corps article */
        .article-body-wrap {
            background:#fff;
            border-radius:24px 24px 0 0;
            margin-top:0;
            position:relative;
            z-index:2;
            padding: 56px 0 72px;
        }

        /* Layout 2 colonnes : image gauche | texte droite */
        .article-layout {
            display: grid;
            grid-template-columns: 420px 1fr;
            gap: 52px;
            align-items: flex-start;
        }
        @media(max-width:900px){
            .article-layout { grid-template-columns: 1fr; gap: 32px; }
        }

        /* Colonne image */
        .article-image-col {
            position: sticky;
            top: 100px;
        }
        .article-image-frame {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(10,18,39,0.18), 0 4px 16px rgba(10,18,39,0.10);
            border: 4px solid #fff;
            outline: 1px solid rgba(4,120,71,0.15);
            background: linear-gradient(135deg,#062e1c,#0a1227);
            aspect-ratio: 4/3;
        }
        .article-image-frame img {
            width:100%; height:100%;
            object-fit:cover; display:block;
            transition: transform 0.5s ease;
        }
        .article-image-frame:hover img { transform: scale(1.04); }
        .article-image-placeholder {
            width:100%; height:100%;
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            gap:14px; color:rgba(255,255,255,0.15);
            font-size:4rem;
        }
        .article-image-placeholder span {
            font-size:0.78rem; font-weight:600;
            letter-spacing:1px; text-transform:uppercase;
            color:rgba(255,255,255,0.2);
        }
        .article-image-caption {
            margin-top:12px;
            font-size:0.75rem;
            color:var(--text-muted);
            text-align:center;
            font-style:italic;
            display:flex; align-items:center; justify-content:center; gap:6px;
        }

        /* Colonne texte */
        .article-text-col {}
        .article-content {
            font-size:1.02rem;
            color:#2d3748;
            line-height:1.9;
        }
        .article-content p { margin-bottom:1.45em; }
        .article-content p:first-child {
            font-size:1.1rem;
            font-weight:500;
            color:#1a202c;
        }

        /* Partage */
        .share-bar {
            display:flex;align-items:center;gap:12px;
            padding:20px 24px;
            background:var(--surface);
            border-radius:16px;
            margin:36px 0;
            flex-wrap:wrap;
        }
        .share-bar span { font-size:0.82rem;font-weight:700;color:var(--black);margin-right:4px; }
        .share-btn {
            display:inline-flex;align-items:center;gap:7px;
            padding:8px 16px;border-radius:10px;border:none;
            font-size:0.78rem;font-weight:700;cursor:pointer;
            text-decoration:none;transition:opacity 0.2s;font-family:inherit;
        }
        .share-btn:hover { opacity:0.85; }
        .share-fb  { background:#1877f2;color:#fff; }
        .share-tw  { background:#000;color:#fff; }
        .share-wa  { background:#25d366;color:#fff; }
        .share-copy{ background:var(--navy);color:#fff; }

        /* Séparateur */
        .divider { border:none;border-top:1px solid var(--border);margin:40px 0; }

        /* Autres articles */
        .others-section { padding:48px 0 80px;background:#f8fafd; }
        .others-section h2 { font-size:1.3rem;font-weight:800;margin-bottom:28px;color:var(--black); }
        .others-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:24px; }
        @media(max-width:768px){ .others-grid { grid-template-columns:1fr; } }

        .other-card {
            background:#fff;border-radius:18px;overflow:hidden;
            box-shadow:var(--shadow-md);border:1px solid var(--border);
            text-decoration:none;color:var(--black);
            transition:var(--transition);display:block;
        }
        .other-card:hover { transform:translateY(-5px);box-shadow:var(--shadow-lg); }
        .other-card-img { height:160px;overflow:hidden;background:var(--navy);position:relative; }
        .other-card-img img { width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.4s; }
        .other-card:hover .other-card-img img { transform:scale(1.07); }
        .other-card-img-placeholder { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2rem;color:rgba(255,255,255,0.1); }
        .other-card-body { padding:18px; }
        .other-card-date { font-size:0.7rem;color:var(--green);font-weight:700;margin-bottom:8px;display:block; }
        .other-card-title { font-size:0.88rem;font-weight:700;line-height:1.4;color:var(--black); }

        /* CTA contact */
        .contact-cta {
            background:linear-gradient(135deg,#050613 0%,#062e1c 100%);
            border-radius:20px;padding:36px;
            text-align:center;margin-top:48px;
        }
        .contact-cta h3 { color:#fff;font-size:1.2rem;font-weight:800;margin-bottom:10px; }
        .contact-cta p { color:rgba(255,255,255,0.55);font-size:0.88rem;margin-bottom:22px; }
        .contact-cta a {
            display:inline-flex;align-items:center;gap:10px;
            background:var(--green);color:#fff;
            padding:13px 28px;border-radius:12px;
            font-weight:700;font-size:0.9rem;text-decoration:none;
            transition:background 0.2s;
        }
        .contact-cta a:hover { background:var(--green-light); }

        /* Footer */
        footer { background:var(--navy);color:rgba(255,255,255,0.55);padding:32px 0;text-align:center;border-top:1px solid rgba(255,255,255,0.06); }
        footer p { font-size:0.8rem; }
        footer a { color:#4ade80;text-decoration:none; }
    </style>
</head>
<body>
    <div id="scroll-progress"></div>

    <!-- Header -->
    <header id="header">
        <div class="header-inner">
            <div class="container">
                <nav class="navbar">
                    <a href="/" class="logo">
                        <img src="/photos/logo.jpeg" alt="WIBC" class="logo-image">
                        <span class="logo-text">WI<span>BC</span></span>
                    </a>
                    <ul class="nav-links" id="nav-links">
                        <li><a href="/">Accueil</a></li>
                        <li><a href="/#about">À propos</a></li>
                        <li><a href="/#services">Expertises</a></li>
                        <li><a href="/#team">Équipe</a></li>
                        <li><a href="/#achievements">Réalisations</a></li>
                        <li><a href="/actualites" class="active">Actualités</a></li>
                        <li><a href="/#contact" class="nav-cta">Contact</a></li>
                    </ul>
                    <button class="mobile-menu-btn" id="mobile-menu-btn"><i class="fas fa-bars"></i></button>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <div class="article-hero">
        <div class="container-sm">
            <div class="article-hero-inner">
                <div class="article-breadcrumb">
                    <a href="/"><i class="fas fa-home"></i> Accueil</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="/actualites">Actualités</a>
                    <i class="fas fa-chevron-right"></i>
                    <span style="color:rgba(255,255,255,0.7);">{{ Str::limit($article->title, 40) }}</span>
                </div>
                <div class="article-tags">
                    <span class="article-tag"><i class="fas fa-newspaper"></i> Actualité</span>
                    @if($article->date)
                    <span class="article-tag" style="background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);">
                        <i class="fas fa-calendar-alt"></i> {{ fmtDate($article->date) }}
                    </span>
                    @endif
                </div>
                <h1 class="article-title">{{ $article->title }}</h1>
                <div class="article-meta">
                    <div class="article-meta-item">
                        <i class="fas fa-building"></i>
                        <span>WIBC — World Innovations Business Consulting</span>
                    </div>
                    @if($article->date)
                    <div class="article-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ fmtDate($article->date) }}</span>
                    </div>
                    @endif
                    <div class="article-meta-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ max(1, ceil(str_word_count($article->description ?? '') / 200)) }} min de lecture</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Corps de l'article : layout image gauche | texte droite -->
    <div class="article-body-wrap">
        <div class="container">

            <div class="article-layout">

                <!-- Colonne gauche : image encadrée -->
                <div class="article-image-col">
                    <div class="article-image-frame">
                        @if($article->photo)
                        <img src="/actualites/{{ $article->id }}/photo" alt="{{ $article->title }}">
                        @else
                        <div class="article-image-placeholder">
                            <i class="fas fa-newspaper"></i>
                            <span>Aucune photo</span>
                        </div>
                        @endif
                    </div>
                    @if($article->photo)
                    <div class="article-image-caption">
                        <i class="fas fa-camera"></i> {{ Str::limit($article->title, 50) }}
                    </div>
                    @endif
                </div>

                <!-- Colonne droite : contenu texte -->
                <div class="article-text-col">
                    <div class="article-content">
                        @if($article->description)
                            @foreach(explode("\n", $article->description) as $paragraph)
                                @if(trim($paragraph))
                                <p>{{ trim($paragraph) }}</p>
                                @endif
                            @endforeach
                        @else
                        <p style="color:var(--text-muted);font-style:italic;">Aucun contenu disponible pour cet article.</p>
                        @endif
                    </div>

                    <!-- Barre de partage -->
                    <div class="share-bar">
                <span><i class="fas fa-share-alt"></i> Partager :</span>
                <a class="share-btn share-fb" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a class="share-btn share-tw" href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
                <a class="share-btn share-wa" href="https://wa.me/?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                    <button class="share-btn share-copy" onclick="copyLink()">
                        <i class="fas fa-link"></i> <span id="copyLabel" style="color: white">Copier le lien</span>
                    </button>
                </div>

                </div><!-- /article-text-col -->
            </div><!-- /article-layout -->

            <hr class="divider">

            <!-- CTA contact -->
            <div class="contact-cta">
                <h3>Intéressé par nos services ?</h3>
                <p>Contactez WIBC pour découvrir comment nous pouvons vous accompagner vers le succès.</p>
                <a href="/#contact">
                    <i class="fas fa-paper-plane"></i>
                    Nous contacter
                </a>
            </div>
        </div>
    </div>

    <!-- Autres articles -->
    @if($others->count())
    <section class="others-section">
        <div class="container">
            <h2><i class="fas fa-newspaper" style="color:var(--green);margin-right:10px;"></i> Autres actualités</h2>
            <div class="others-grid">
                @foreach($others as $j => $other)
                <a href="/actualites/{{ $other->id }}" class="other-card">
                    <div class="other-card-img">
                        @if($other->photo)
                        <img src="/actualites/{{ $other->id }}/photo" alt="{{ $other->title }}">
                        @else
                        <div class="other-card-img-placeholder"><i class="fas fa-newspaper"></i></div>
                        @endif
                    </div>
                    <div class="other-card-body">
                        @if($other->date)
                        <span class="other-card-date"><i class="fas fa-calendar-alt"></i> {{ fmtDate($other->date) }}</span>
                        @endif
                        <div class="other-card-title">{{ $other->title }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 World Innovations Business Consulting (WIBC). —
                <a href="mailto:{{ $contact['email'] ?? 'worldibconsulting@gmail.com' }}">{{ $contact['email'] ?? 'worldibconsulting@gmail.com' }}</a>
                — <a href="/actualites">← Retour aux actualités</a>
            </p>
        </div>
    </footer>

    <script>
        // Scroll progress
        window.addEventListener('scroll', function() {
            var docH = document.documentElement.scrollHeight - window.innerHeight;
            document.getElementById('scroll-progress').style.width = (window.scrollY / docH * 100) + '%';
        });

        // Mobile menu
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            var nav = document.getElementById('nav-links');
            var open = nav.classList.toggle('active');
            this.innerHTML = open ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });

        // Copier le lien
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                var label = document.getElementById('copyLabel');
                label.textContent = 'Copié !';
                setTimeout(function() { label.textContent = 'Copier le lien'; }, 2000);
            });
        }
    </script>
</body>
</html>
