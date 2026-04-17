@extends('admin.layout')

@section('title', 'Dashboard')
@section('subtitle', 'Vue d\'ensemble de votre contenu')

@section('content')

<!-- Stats -->
<div class="stats-grid" id="statsGrid">
    <div class="stat-card stat-card-1">
        <h3 id="statServices">—</h3>
        <p>Pôles actifs</p>
        <i class="fas fa-chart-line"></i>
    </div>
    <div class="stat-card stat-card-2">
        <h3 id="statTeam">—</h3>
        <p>Membres d'équipe</p>
        <i class="fas fa-users"></i>
    </div>
    <div class="stat-card stat-card-3">
        <h3 id="statAch">—</h3>
        <p>Réalisations</p>
        <i class="fas fa-trophy"></i>
    </div>
    <div class="stat-card stat-card-4">
        <h3 id="statNews">—</h3>
        <p>Actualités</p>
        <i class="fas fa-newspaper"></i>
    </div>
</div>

<!-- Bottom row: Chart + Quick Access -->
<div style="display:grid;grid-template-columns:1.4fr 1fr;gap:20px;align-items:start;">

    <!-- Chart -->
    <div class="editor-card" style="margin-bottom:0;">
        <div class="editor-card-header">
            <div class="editor-card-title">
                <div class="editor-card-icon"><i class="fas fa-chart-area"></i></div>
                <div>
                    <div class="editor-card-label">Performance globale</div>
                    <div class="editor-card-sub">Activité des 6 derniers mois</div>
                </div>
            </div>
            <span class="badge badge-green"><i class="fas fa-arrow-up"></i> +18%</span>
        </div>
        <div class="editor-card-body">
            <canvas id="activityChart" height="160"></canvas>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="editor-card" style="margin-bottom:0;">
        <div class="editor-card-header">
            <div class="editor-card-title">
                <div class="editor-card-icon"><i class="fas fa-bolt"></i></div>
                <div>
                    <div class="editor-card-label">Accès rapide</div>
                    <div class="editor-card-sub">Modifier les sections du site</div>
                </div>
            </div>
        </div>
        <div class="editor-card-body" style="padding:16px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                @foreach([
                    ['href'=>'/admin/hero',         'icon'=>'fa-home',         'label'=>'Hero'],
                    ['href'=>'/admin/about',        'icon'=>'fa-building',     'label'=>'À propos'],
                    ['href'=>'/admin/services',     'icon'=>'fa-chart-line',   'label'=>'Pôles'],
                    ['href'=>'/admin/team',         'icon'=>'fa-users',        'label'=>'Équipe'],
                    ['href'=>'/admin/achievements', 'icon'=>'fa-trophy',       'label'=>'Réalisations'],
                    ['href'=>'/admin/news',         'icon'=>'fa-newspaper',    'label'=>'Actualités'],
                    ['href'=>'/admin/gallery',      'icon'=>'fa-images',       'label'=>'Galerie'],
                    ['href'=>'/admin/contact',      'icon'=>'fa-address-card', 'label'=>'Contact'],
                ] as $i => $item)
                <a href="{{ $item['href'] }}" style="display:flex;align-items:center;gap:10px;padding:11px 13px;border-radius:12px;border:1.5px solid var(--border-light);background:var(--bg-app);text-decoration:none;color:var(--text-primary);font-weight:600;font-size:0.82rem;transition:all 0.2s;"
                   onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)';this.style.background='var(--accent-soft)'"
                   onmouseout="this.style.borderColor='var(--border-light)';this.style.color='var(--text-primary)';this.style.background='var(--bg-app)'">
                    <i class="fas {{ $item['icon'] }}" style="font-size:0.9rem;width:16px;text-align:center;color:{{ $i % 3 === 0 ? 'var(--accent)' : ($i % 3 === 1 ? '#07102a' : 'var(--red)') }};opacity:0.8;"></i>
                    {{ $item['label'] }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function animNum(id, target){
        const el = document.getElementById(id);
        if(!el) return;
        let cur = 0, step = Math.max(1, Math.ceil(target/25));
        const iv = setInterval(()=>{
            cur += step;
            if(cur >= target){ el.textContent = target; clearInterval(iv); }
            else el.textContent = cur;
        }, 30);
    }
    Promise.all([
        api('GET', '/admin/api/services'),
        api('GET', '/admin/api/team'),
        api('GET', '/admin/api/achievements'),
        api('GET', '/admin/api/news'),
    ]).then(([services, team, achievements, news]) => {
        animNum('statServices', services.length);
        animNum('statTeam',     team.length);
        animNum('statAch',      achievements.length);
        animNum('statNews',     news.length);
    }).catch(() => {
        ['statServices','statTeam','statAch','statNews'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = '—';
        });
    });

    // Chart
    const isDark = document.body.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(7,16,42,0.05)';
    const tickColor = isDark ? '#7e8aa2' : '#6c7a91';

    const ctx = document.getElementById('activityChart').getContext('2d');
    const grad = ctx.createLinearGradient(0,0,0,200);
    grad.addColorStop(0, 'rgba(4,120,71,0.18)');
    grad.addColorStop(1, 'rgba(4,120,71,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Fév','Mar','Avr','Mai','Juin'],
            datasets: [{
                label: 'Activité',
                data: [320, 480, 620, 810, 970, 1150],
                borderColor: '#047847',
                backgroundColor: grad,
                tension: 0.42,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#047847',
                pointBorderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
            scales: {
                y: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 11 } }, border: { display: false } },
                x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 11 } }, border: { display: false } }
            }
        }
    });
</script>
@endsection
