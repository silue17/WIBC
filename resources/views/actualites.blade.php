@php
function fmtDate($d) {
    if (!$d) return '';
    $m = [];
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $d, $m)) return "{$m[3]}/{$m[2]}/{$m[1]}";
    return $d; // already dd/mm/yyyy or other format
}
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités — WIBC</title>
    <link rel="icon" type="image/jpeg" href="/photos/logo.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --white: #FEFEFE;
            --green: #047847;
            --green-light: #05a35f;
            --red: #F34244;
            --black: #050613;
            --navy: #0a1227;
            --surface: #eef1f9;
            --border: rgba(10,18,39,0.10);
            --text-muted: rgba(10,18,39,0.52);
            --transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
            --shadow-md: 0 8px 32px rgba(10,18,39,0.12);
            --radius-md: 16px;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { font-family:'Inter',sans-serif; background:#f8fafd; color:var(--black); overflow-x:hidden; }
        h1,h2,h3,h4 { font-family:'Space Grotesk','Inter',sans-serif; }

        .container { width:100%; max-width:1240px; margin:0 auto; padding:0 28px; }

        /* ── SCROLL PROGRESS ── */
        #scroll-progress {
            position:fixed; top:0; left:0; height:3px;
            background:linear-gradient(90deg,var(--green),#0564B7,var(--red));
            z-index:9999; width:0%; transition:width 0.1s linear;
        }

        /* ── HEADER ── */
        header { position:fixed; width:100%; top:0; z-index:1000; }
        .header-inner { background:rgba(10,14,39,0.96); backdrop-filter:blur(24px); border-bottom:1px solid rgba(255,255,255,0.08); box-shadow:0 4px 30px rgba(0,0,0,0.3); }
        .navbar { display:flex; justify-content:space-between; align-items:center; padding:18px 0; }
        .logo { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .logo-image { width:36px; height:36px; border-radius:8px; object-fit:cover; }
        .logo-text { font-family:'Space Grotesk',sans-serif; font-weight:800; font-size:1.15rem; color:#fff; letter-spacing:-0.5px; }
        .logo-text span { color:#4ade80; }
        .nav-links { display:flex; align-items:center; gap:6px; list-style:none; }
        .nav-links a { color:rgba(255,255,255,0.78); text-decoration:none; font-size:0.88rem; font-weight:500; padding:7px 14px; border-radius:8px; transition:var(--transition); }
        .nav-links a:hover, .nav-links a.active { color:#fff; background:rgba(255,255,255,0.08); }
        .nav-links a.nav-cta { background:var(--green); color:#fff; font-weight:700; padding:8px 18px; border-radius:10px; }
        .nav-links a.nav-cta:hover { background:var(--green-light); }
        .mobile-menu-btn { display:none; background:none; border:none; color:#fff; font-size:1.3rem; cursor:pointer; }

        @media(max-width:768px){
            .nav-links { display:none; flex-direction:column; position:absolute; top:100%; left:0; right:0; background:rgba(10,14,39,0.98); padding:20px; gap:4px; }
            .nav-links.active { display:flex; }
            .mobile-menu-btn { display:block; }
        }

        /* ── HERO ── */
        .news-hero {
            background: linear-gradient(135deg, #050613 0%, #0a1227 50%, #062e1c 100%);
            padding: 140px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .news-hero::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(4,120,71,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .news-hero-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(74,222,128,0.1);
            border: 1px solid rgba(74,222,128,0.25);
            color: #4ade80;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .news-hero h1 { font-size: clamp(2rem,5vw,3.2rem); font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 16px; }
        .news-hero h1 span { color: #4ade80; }
        .news-hero p { color: rgba(255,255,255,0.6); font-size: 1rem; max-width: 520px; line-height: 1.7; }
        .news-hero-stats { display:flex; gap:32px; margin-top:36px; flex-wrap:wrap; }
        .news-hero-stat strong { display:block; font-size:1.6rem; font-weight:800; color:#fff; }
        .news-hero-stat span { font-size:0.78rem; color:rgba(255,255,255,0.45); text-transform:uppercase; letter-spacing:0.8px; }

        /* ── FILTERS ── */
        .filter-section { padding: 32px 0 8px; background: #fff; border-bottom: 1px solid var(--border); position: sticky; top: 72px; z-index: 100; }
        .filter-chips { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
        .filter-chip {
            padding: 7px 18px;
            border-radius: 20px;
            border: 1.5px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
        }
        .filter-chip:hover { border-color: var(--green); color: var(--green); }
        .filter-chip.active { background: var(--green); border-color: var(--green); color: #fff; }

        /* ── GRID ── */
        .news-section { padding: 56px 0 80px; }
        .news-count { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 32px; }
        .news-count strong { color: var(--black); }

        .all-news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }
        @media(max-width:992px){ .all-news-grid { grid-template-columns: repeat(2,1fr); } }
        @media(max-width:600px){ .all-news-grid { grid-template-columns: 1fr; } }

        .news-card-full {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }
        .news-card-full:hover { transform: translateY(-6px); box-shadow: 0 20px 60px rgba(10,18,39,0.16); }

        .news-cover-full {
            height: 220px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #0a1227, #062e1c);
            flex-shrink: 0;
        }
        .news-cover-full img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }
        .news-card-full:hover .news-cover-full img { transform: scale(1.07); }
        .news-cover-full .news-no-photo {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem; color: rgba(255,255,255,0.15);
        }

        .news-body-full { padding: 24px; flex: 1; display: flex; flex-direction: column; }
        .news-meta { display:flex; align-items:center; gap:10px; margin-bottom:12px; flex-wrap:wrap; }
        .news-date-badge {
            display: inline-flex; align-items: center; gap:6px;
            background: rgba(4,120,71,0.08);
            color: var(--green);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .news-card-full h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--black);
            line-height: 1.4;
            margin-bottom: 10px;
        }
        .news-card-full p {
            font-size: 0.82rem;
            color: var(--text-muted);
            line-height: 1.65;
            flex: 1;
            margin-bottom: 18px;
        }
        .news-read-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--green);
            font-weight: 700;
            font-size: 0.82rem;
            text-decoration: none;
            transition: gap 0.2s;
        }
        .news-read-btn:hover { gap: 12px; }

        /* ── EMPTY STATE ── */
        .empty-state { text-align:center; padding:80px 20px; color:var(--text-muted); }
        .empty-state i { font-size:3rem; margin-bottom:16px; opacity:0.3; display:block; }
        .empty-state p { font-size:1rem; }

        /* ── NO RESULT ── */
        #noResult { display:none; text-align:center; padding:60px 20px; color:var(--text-muted); grid-column:1/-1; }

        /* ── BACK BTN ── */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 24px;
            transition: color 0.2s;
        }
        .back-btn:hover { color: #4ade80; }

        /* ── FOOTER ── */
        footer { background: var(--navy); color: rgba(255,255,255,0.6); padding: 40px 0; text-align: center; border-top: 1px solid rgba(255,255,255,0.06); }
        footer p { font-size: 0.82rem; }
        footer a { color: #4ade80; text-decoration: none; }
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
                        <img src="/photos/logo.jpeg" alt="WIBC Logo" class="logo-image">
                        <span class="logo-text">WI<span>BC</span></span>
                        <span style="color:rgba(255,255,255,0.6);font-size:0.82rem;display:none;" id="logoSub">World Innovations Business Consulting</span>
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
                    <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <div class="news-hero">
        <div class="container">
            <a href="/" class="back-btn"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
            <div class="news-hero-label"><i class="fas fa-newspaper"></i> Actualités</div>
            <h1>Les dernières <span>nouvelles</span> de WIBC</h1>
            <p>Suivez l'actualité de World Innovations Business Consulting — projets, partenariats, événements et innovations.</p>
            <div class="news-hero-stats">
                <div class="news-hero-stat">
                    <strong>{{ $news->count() }}</strong>
                    <span>Articles publiés</span>
                </div>
                @if($news->where('date', '!=', null)->count())
                <div class="news-hero-stat">
                    <strong>{{ fmtDate($news->where('date', '!=', null)->last()->date) }}</strong>
                    <span>Dernier article</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filtres par catégorie/date -->
    @php
        $categories = $news->pluck('date')->filter()->unique()->values();
    @endphp
    @if($categories->count() > 1)
    <div class="filter-section">
        <div class="container">
            <div class="filter-chips">
                <button class="filter-chip active" data-filter="all">Tous</button>
                @foreach($categories as $cat)
                <button class="filter-chip" data-filter="{{ $cat }}">{{ $cat }}</button>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Grille articles -->
    <section class="news-section">
        <div class="container">
            @if($news->count())
            <p class="news-count">
                <strong id="visibleCount">{{ $news->count() }}</strong> article(s) trouvé(s)
            </p>
            <div class="all-news-grid" id="newsGrid">
                @foreach($news as $i => $article)
                <div class="news-card-full" data-date="{{ fmtDate($article->date) }}">
                    <div class="news-cover-full">
                        @if($article->photo)
                        <img src="/actualites/{{ $article->id }}/photo" alt="{{ $article->title }}">
                        @else
                        <div class="news-no-photo"><i class="fas fa-newspaper"></i></div>
                        @endif
                    </div>
                    <div class="news-body-full">
                        <div class="news-meta">
                            @if($article->date)
                            <span class="news-date-badge"><i class="fas fa-calendar-alt"></i> {{ fmtDate($article->date) }}</span>
                            @endif
                        </div>
                        <h3>{{ $article->title }}</h3>
                        {{-- @if($article->description)
                        <p>{{ $article->description }}</p>
                        @else
                        <p style="font-style:italic;">Aucune description disponible.</p>
                        @endif --}}
                        <a href="/actualites/{{ $article->id }}" class="news-read-btn">
                            Lire la suite <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
                <div id="noResult"><i class="fas fa-search"></i><p>Aucun article pour cette période.</p></div>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <p>Aucune actualité publiée pour le moment.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 World Innovations Business Consulting (WIBC). Tous droits réservés. —
                <a href="mailto:{{ $contact['email'] ?? 'worldibconsulting@gmail.com' }}">{{ $contact['email'] ?? 'worldibconsulting@gmail.com' }}</a>
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

        // Filtres
        document.querySelectorAll('.filter-chip').forEach(function(chip) {
            chip.addEventListener('click', function() {
                document.querySelectorAll('.filter-chip').forEach(function(c){ c.classList.remove('active'); });
                chip.classList.add('active');
                var filter = chip.getAttribute('data-filter');
                var cards  = document.querySelectorAll('.news-card-full');
                var visible = 0;
                cards.forEach(function(card) {
                    var match = filter === 'all' || card.getAttribute('data-date') === filter;
                    card.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                document.getElementById('visibleCount').textContent = visible;
                var noResult = document.getElementById('noResult');
                if (noResult) noResult.style.display = visible === 0 ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
