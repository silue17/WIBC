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

    <!-- Content Overview -->
    <div class="editor-card" style="margin-bottom:0;">
        <div class="editor-card-header">
            <div class="editor-card-title">
                <div class="editor-card-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="editor-card-label">Répartition du contenu</div>
                    <div class="editor-card-sub">Vue d'ensemble des sections publiées</div>
                </div>
            </div>
            <span class="badge badge-navy" id="totalContentBadge">— éléments</span>
        </div>
        <div class="editor-card-body" style="padding:20px 24px;">

            <!-- Donut + légende -->
            <div style="display:flex;align-items:center;gap:28px;">
                <div style="position:relative;flex-shrink:0;">
                    <canvas id="donutChart" width="140" height="140"></canvas>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                        <div id="donutTotal" style="font-size:1.6rem;font-weight:900;color:var(--text-primary);line-height:1;">—</div>
                        <div style="font-size:0.65rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.8px;">total</div>
                    </div>
                </div>
                <div style="flex:1;display:flex;flex-direction:column;gap:10px;" id="donutLegend"></div>
            </div>

            <!-- Barres de progression -->
            <div style="margin-top:20px;padding-top:18px;border-top:1px solid var(--border-light);display:flex;flex-direction:column;gap:10px;" id="progressBars"></div>

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
    // Donut chart — vraies données
    const SECTIONS = [
        { label: 'Pôles',        color: '#047847', key: 'services'     },
        { label: 'Équipe',       color: '#07102a', key: 'team'         },
        { label: 'Réalisations', color: '#e8392a', key: 'achievements' },
        { label: 'Actualités',   color: '#05a35f', key: 'news'         },
    ];

    Promise.all([
        api('GET', '/admin/api/services'),
        api('GET', '/admin/api/team'),
        api('GET', '/admin/api/achievements'),
        api('GET', '/admin/api/news'),
    ]).then(([services, team, achievements, news]) => {
        const counts = { services: services.length, team: team.length, achievements: achievements.length, news: news.length };

        animNum('statServices', counts.services);
        animNum('statTeam',     counts.team);
        animNum('statAch',      counts.achievements);
        animNum('statNews',     counts.news);

        const total = Object.values(counts).reduce((a,b)=>a+b, 0);
        document.getElementById('donutTotal').textContent = total;
        document.getElementById('totalContentBadge').textContent = `${total} éléments`;

        // Légende
        const legend = document.getElementById('donutLegend');
        legend.innerHTML = SECTIONS.map(s => {
            const n   = counts[s.key];
            const pct = total > 0 ? Math.round(n / total * 100) : 0;
            return `
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="width:10px;height:10px;border-radius:50%;background:${s.color};flex-shrink:0;display:block;"></span>
                <span style="flex:1;font-size:0.8rem;color:var(--text-secondary);font-weight:500;">${s.label}</span>
                <span style="font-size:0.82rem;font-weight:700;color:var(--text-primary);">${n}</span>
                <span style="font-size:0.72rem;color:var(--text-muted);min-width:32px;text-align:right;">${pct}%</span>
            </div>`;
        }).join('');

        // Barres de progression
        const bars = document.getElementById('progressBars');
        bars.innerHTML = SECTIONS.map(s => {
            const n   = counts[s.key];
            const pct = total > 0 ? Math.round(n / total * 100) : 0;
            return `
            <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                    <span style="font-size:0.73rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.6px;">${s.label}</span>
                    <span style="font-size:0.73rem;font-weight:700;color:var(--text-primary);">${n} élément${n>1?'s':''}</span>
                </div>
                <div style="height:6px;border-radius:999px;background:var(--border-light);overflow:hidden;">
                    <div style="height:100%;width:${pct}%;background:${s.color};border-radius:999px;transition:width 0.8s cubic-bezier(0.2,0,0,1);"></div>
                </div>
            </div>`;
        }).join('');

        // Donut chart
        const ctx = document.getElementById('donutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels:   SECTIONS.map(s => s.label),
                datasets: [{
                    data:            SECTIONS.map(s => counts[s.key] || 0.01),
                    backgroundColor: SECTIONS.map(s => s.color),
                    borderWidth:     3,
                    borderColor:     getComputedStyle(document.documentElement).getPropertyValue('--surface') || '#fff',
                    hoverOffset:     6,
                }]
            },
            options: {
                cutout: '70%',
                responsive: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label} : ${counts[SECTIONS[ctx.dataIndex].key]} élément(s)`
                        }
                    }
                }
            }
        });

    }).catch(() => {
        ['statServices','statTeam','statAch','statNews'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = '—';
        });
    });
</script>
@endsection
