<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>WIBC Admin • Dashboard Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg-app: #f4f6fb;
            --surface: #ffffff;
            --surface-elevated: #ffffff;
            --sidebar-bg: rgba(10, 18, 39, 0.98);
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --text-primary: #0a1227;
            --text-secondary: #4a5568;
            --text-muted: #6c7a91;
            --accent: #047847;
            --accent-light: #05a35f;
            --accent-soft: rgba(4, 120, 71, 0.12);
            --danger: #e53e3e;
            --border-light: #eef2f6;
            --shadow-sm: 0 8px 20px rgba(0, 0, 0, 0.03), 0 2px 6px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
            --transition: all 0.25s cubic-bezier(0.2, 0, 0, 1);
        }

        body.dark {
            --bg-app: #0f1219;
            --surface: #1a1f2e;
            --surface-elevated: #222837;
            --sidebar-bg: #0b0f17;
            --text-primary: #edf2f7;
            --text-secondary: #a0aec0;
            --text-muted: #7e8aa2;
            --border-light: #2d3448;
            --shadow-sm: 0 8px 20px rgba(0, 0, 0, 0.4);
            --shadow-md: 0 20px 35px -12px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-app);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.2s ease;
        }

        .dashboard-container { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(12px);
            border-right: 1px solid var(--sidebar-border);
            position: fixed;
            height: 100vh;
            transition: var(--transition);
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar.collapsed { width: 90px; }
        .sidebar.collapsed .sidebar-header h2,
        .sidebar.collapsed .nav-item span,
        .sidebar.collapsed .logo-text { display: none; }
        .sidebar.collapsed .nav-item { justify-content: center; padding: 14px 0; }
        .sidebar.collapsed .sidebar-header { justify-content: center; }

        .sidebar-header {
            padding: 28px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .sidebar-header img {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .sidebar-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #a3f5c1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 24px;
            margin: 6px 12px;
            border-radius: 14px;
            color: var(--text-secondary);
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
        }

        .nav-item i { width: 24px; font-size: 1.2rem; }
        .nav-item:hover { background: var(--accent-soft); color: var(--accent-light); transform: translateX(4px); }
        .nav-item.active { background: var(--accent); color: white; box-shadow: 0 6px 14px rgba(4, 120, 71, 0.3); }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 28px 32px;
            transition: var(--transition);
        }

        .sidebar.collapsed ~ .main-content { margin-left: 90px; }

        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title h1 {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            background: linear-gradient(145deg, var(--text-primary), var(--accent));
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .admin-actions { display: flex; gap: 12px; align-items: center; }

        .icon-btn {
            background: var(--surface);
            border: none;
            padding: 10px;
            border-radius: 40px;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--text-secondary);
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .icon-btn:hover { background: var(--accent); color: white; transform: scale(1.03); }

        /* Stats Grid Premium */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .gradient-card {
            background: linear-gradient(135deg, #047847, #05a35f);
            border-radius: 28px;
            padding: 24px;
            color: white;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .gradient-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 60%);
            pointer-events: none;
        }

        .gradient-card:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 20px 35px -12px rgba(4,120,71,0.4); }

        .gradient-card h3 { font-size: 2.2rem; font-weight: 800; margin-bottom: 8px; }
        .gradient-card p { font-size: 0.85rem; opacity: 0.9; }

        /* Section Cards */
        .section-card {
            background: var(--surface);
            border-radius: 32px;
            padding: 28px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
            transition: all 0.2s;
        }

        .glass {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        body.dark .glass {
            background: rgba(30,35,50,0.6);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            padding: 10px 20px;
            border-radius: 40px;
            font-weight: 600;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .btn-primary:hover { background: var(--accent-light); transform: translateY(-2px); box-shadow: 0 12px 20px -12px var(--accent); }

        /* Items Grid */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .item-card {
            background: var(--bg-app);
            border-radius: 24px;
            padding: 20px;
            transition: var(--transition);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .item-card::before {
            content: "";
            position: absolute;
            width: 120%;
            height: 100%;
            top: -50%;
            left: -10%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.15), transparent);
            transform: rotate(25deg);
            opacity: 0;
            transition: 0.4s;
        }

        .item-card:hover::before { opacity: 1; top: 100%; }
        .item-card:hover { transform: translateY(-5px); background: var(--surface-elevated); box-shadow: var(--shadow-md); }

        /* Modal Glassmorphism */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.2s ease;
        }

        .modal-content {
            background: var(--surface);
            border-radius: 36px;
            width: 90%;
            max-width: 520px;
            padding: 32px;
            animation: scaleUp 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 30px 50px rgba(0,0,0,0.3);
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes scaleUp { from { transform: scale(0.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        /* Toast */
        .toast-notify {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: var(--accent);
            color: white;
            padding: 14px 28px;
            border-radius: 60px;
            font-weight: 500;
            backdrop-filter: blur(12px);
            z-index: 1200;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            animation: slideUp 0.3s ease forwards;
        }

        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .fade-in { animation: fadeInUp 0.5s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        html { scroll-behavior: smooth; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); position: fixed; z-index: 200; }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; padding: 20px; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 16px; }
        }

        .collapsible-toggle {
            position: absolute;
            bottom: 24px;
            left: 20px;
            background: var(--accent-soft);
            border-radius: 40px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="photos/logo.jpeg" alt="logo" onerror="this.src='https://placehold.co/45x45?text=W'">
            <h2>WI<span style="color:#4ade80;">BC</span> <span class="logo-text">Admin</span></h2>
        </div>
        <div class="nav-menu" id="navMenu">
            <div class="nav-item active" data-section="dashboard"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></div>
            <div class="nav-item" data-section="hero"><i class="fas fa-home"></i><span>Hero</span></div>
            <div class="nav-item" data-section="about"><i class="fas fa-building"></i><span>À propos</span></div>
            <div class="nav-item" data-section="services"><i class="fas fa-chart-line"></i><span>Pôles</span></div>
            <div class="nav-item" data-section="team"><i class="fas fa-users"></i><span>Équipe</span></div>
            <div class="nav-item" data-section="achievements"><i class="fas fa-trophy"></i><span>Réalisations</span></div>
            <div class="nav-item" data-section="news"><i class="fas fa-newspaper"></i><span>Actualités</span></div>
            <div class="nav-item" data-section="gallery"><i class="fas fa-images"></i><span>Galerie</span></div>
            <div class="nav-item" data-section="contact"><i class="fas fa-address-card"></i><span>Contact</span></div>
        </div>
        <div class="collapsible-toggle" id="collapseSidebarBtn"><i class="fas fa-chevron-left"></i> <span>Reduce</span></div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div class="page-title">
                <h1 id="mainTitle">Tableau de bord</h1>
                <p style="color:var(--text-muted)">Gestion centralisée • WIBC Intelligence</p>
            </div>
            <div class="admin-actions">
                <button class="icon-btn" id="darkModeToggle"><i class="fas fa-moon"></i></button>
                <button class="icon-btn" id="mobileMenuToggle"><i class="fas fa-bars"></i></button>
                <div class="admin-avatar" style="width:44px;height:44px;background:var(--accent);border-radius:40px;display:flex;align-items:center;justify-content:center;font-weight:bold">AD</div>
            </div>
        </div>
        <div id="dynamicContent" class="fade-in"></div>
    </main>
</div>

<div id="genericModal" class="modal"><div class="modal-content"><div class="modal-header" style="display:flex;justify-content:space-between;"><h3 id="modalTitle"></h3><span class="close-modal" style="font-size:28px;cursor:pointer;">&times;</span></div><div id="modalBody"></div></div></div>

<script>
    // ------------------------- DATA STORE -------------------------
    let db = {
        hero: { title: "WORLD INNOVATIONS BUSINESS CONSULTING", tagline: "Une vision mondiale, un impact africain", description: "Conseil – Innovation – Stratégie – Impact. Nous accompagnons entreprises et institutions vers l'excellence en Afrique et au-delà.", btnText: "Nous contacter" },
        about: { title: "Transformer l'Afrique par l'innovation", description1: "World Innovations Business Consulting (WIBC) est une société ivoirienne de conseil fondée par trois jeunes entrepreneurs visionnaires.", description2: "Nous accompagnons entreprises, institutions publiques, organisations et personnalités via trois pôles spécialisés.", badge: "100% Ivoirien" },
        services: [{ id:1, name:"Strategy & Business", description:"Accompagner les structures dans leurs choix stratégiques.", features:["Conseil","Structuration"]},{ id:2, name:"Tech & Innovation", description:"Concevoir des projets technologiques.", features:["Énergie","Mobilité"]},{ id:3, name:"Influence & Events", description:"Valoriser l'image et stratégies de visibilité.", features:["Événementiel","Carrière"]}],
        team: [{ id:1, name:"Miwouguih Secongo Clotchor", position:"Directeur Général", bio:"Passionné par le management"},{ id:2, name:"Johann Coulibaly", position:"DGA", bio:"Business & relations internationales"},{ id:3, name:"Ousmane Tidiane Aziz TOURÉ-DERUOTH", position:"DAF", bio:"Expertise financière"}],
        achievements: [{ id:1, title:"African MMA League", category:"Sport", description:"Gestion billetterie et relations institutionnelles"},{ id:2, title:"Gestion d'image", category:"Carrière", description:"Accompagnement Ruth Gbagbi"},{ id:3, title:"Mise en relation B2B", category:"Partenariat", description:"Logistique Endeavour Mining"},{ id:4, title:"Trottinette Électrique", category:"Innovation", description:"Mobilité durable Abidjan"}],
        news: [{ id:1, title:"WIBC expansion Afrique de l'Ouest", date:"Mars 2025", description:"Une nouvelle ère pour le conseil."},{ id:2, title:"African MMA League record", date:"Février 2025", description:"15 000 spectateurs."},{ id:3, title:"Mobilité durable phase pilote", date:"Janvier 2025", description:"Lancement à Abidjan."}],
        gallery: ["photos/1.jpeg","photos/2.jpeg","photos/3.jpeg","photos/4.jpeg","photos/5.jpeg"],
        contact: { address:"Angré, Cocody, Abidjan", email:"worldibconsulting@gmail.com", phone:"+225 07 12 70 01 67", rccm:"CI-ABJ-03-2025-B12-04372" }
    };

    let currentChart = null;

    function saveToLocalStorage() { localStorage.setItem('wibc_db', JSON.stringify(db)); }
    function loadFromLocalStorage() { let data = localStorage.getItem('wibc_db'); if(data) db = JSON.parse(data); }
    loadFromLocalStorage();

    function showToast(msg) { let t=document.createElement('div'); t.className='toast-notify'; t.innerText=msg; document.body.appendChild(t); setTimeout(()=>t.remove(),2200); }
    function escapeHtml(str) { return String(str).replace(/[&<>]/g, function(m){ if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

    function renderDashboard() {
        setTimeout(()=>{
            const canvas = document.getElementById('activityChart');
            if(canvas && currentChart) currentChart.destroy();
            if(canvas){
                const ctx = canvas.getContext('2d');
                currentChart = new Chart(ctx, { type:'line', data:{ labels:['Jan','Fév','Mar','Avr','Mai'], datasets:[{ label:'Visites', data:[420,550,690,870,1020], borderColor:'#047847', backgroundColor:'rgba(4,120,71,0.1)', tension:0.3, fill:true }] }, options:{ responsive:true, maintainAspectRatio:true } });
            }
        },50);
        return `
        <div class="stats-grid">
            <div class="gradient-card"><h3 id="statServices">${db.services.length}</h3><p>Pôles actifs</p></div>
            <div class="gradient-card"><h3 id="statTeam">${db.team.length}</h3><p>Membres</p></div>
            <div class="gradient-card"><h3 id="statAch">${db.achievements.length}</h3><p>Réalisations</p></div>
            <div class="gradient-card"><h3 id="statNews">${db.news.length}</h3><p>Actualités</p></div>
        </div>
        <div class="section-card glass">
            <div class="section-header"><h2><i class="fas fa-chart-line"></i> Performance globale</h2></div>
            <canvas id="activityChart" height="200" style="max-height:280px"></canvas>
        </div>
        <div class="section-card glass">
            <div class="section-header"><h2><i class="fas fa-fire"></i> Activité récente</h2></div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div class="item-card">✨ Nouvelle actualité ajoutée</div>
                <div class="item-card">👤 Membre modifié</div>
                <div class="item-card">🖼️ Galerie mise à jour</div>
            </div>
        </div>`;
    }

    function updateStatsNumbers(){
        ['Services','Team','Ach','News'].forEach(t=>{ let el=document.getElementById(`stat${t}`); if(el) el.innerText=db[t.toLowerCase()].length; });
    }

    // Section renders with live preview
    function renderHero(){
        return `<div class="fade-in">
            <div style="background:linear-gradient(135deg,#07102a 0%,#0a1c44 50%,#042e1a 100%);border-radius:28px;padding:48px 40px;margin-bottom:28px;position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.03) 1px,transparent 1px);background-size:50px 50px;"></div>
                <div style="position:absolute;width:300px;height:300px;background:rgba(4,120,71,0.2);border-radius:50%;filter:blur(80px);top:-80px;right:0;"></div>
                <div style="position:relative;z-index:2;">
                    <div style="display:inline-flex;align-items:center;gap:7px;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:4px 14px;border-radius:999px;font-size:0.7rem;font-weight:700;margin-bottom:20px;"><span style="width:6px;height:6px;background:#4ade80;border-radius:50%;"></span> APERÇU EN DIRECT</div>
                    <div id="previewTitle" style="font-size:clamp(1.4rem,3vw,2.2rem);font-weight:900;color:#fff;margin-bottom:8px;">${escapeHtml(db.hero.title)}</div>
                    <div id="previewTagline" style="font-size:1rem;font-weight:600;color:rgba(255,255,255,0.75);margin-bottom:6px;">${escapeHtml(db.hero.tagline)}</div>
                    <div id="previewDesc" style="font-size:0.82rem;color:rgba(255,255,255,0.45);max-width:480px;margin-bottom:16px;">${escapeHtml(db.hero.description)}</div>
                    <span id="previewBtn" style="display:inline-flex;align-items:center;gap:8px;background:#e8392a;color:#fff;padding:10px 24px;border-radius:999px;font-size:0.82rem;font-weight:700;">${escapeHtml(db.hero.btnText)} <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
            <div style="background:var(--surface);border-radius:24px;border:1px solid var(--border-light);padding:28px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;"><h3><i class="fas fa-edit"></i> Éditeur Hero</h3><button id="saveHeroBtn" class="btn-primary"><i class="fas fa-save"></i> Sauvegarder</button></div>
                <div class="form-group"><label>Titre</label><input id="heroTitle" class="modern-input" value="${escapeHtml(db.hero.title)}" oninput="document.getElementById('previewTitle').textContent=this.value" style="width:100%;padding:12px;border-radius:16px;border:1px solid var(--border-light);background:var(--bg-app);"></div>
                <div class="form-group"><label>Tagline</label><input id="heroTagline" value="${escapeHtml(db.hero.tagline)}" oninput="document.getElementById('previewTagline').textContent=this.value" style="width:100%;padding:12px;border-radius:16px;border:1px solid var(--border-light);background:var(--bg-app);"></div>
                <div class="form-group"><label>Description</label><textarea id="heroDesc" rows="3" oninput="document.getElementById('previewDesc').textContent=this.value" style="width:100%;padding:12px;border-radius:16px;border:1px solid var(--border-light);background:var(--bg-app);">${escapeHtml(db.hero.description)}</textarea></div>
                <div class="form-group"><label>Texte bouton</label><input id="heroBtn" value="${escapeHtml(db.hero.btnText)}" oninput="document.getElementById('previewBtn').childNodes[0].textContent=this.value+' '" style="width:100%;padding:12px;border-radius:16px;border:1px solid var(--border-light);background:var(--bg-app);"></div>
            </div>
        </div>`;
    }

    function renderAbout(){
        return `<div class="fade-in">
            <div style="background:linear-gradient(135deg,#f8faff 0%,#eef3ff 100%);border-radius:28px;padding:40px;margin-bottom:28px;display:grid;grid-template-columns:1fr 1fr;gap:32px;">
                <div><div style="display:inline-flex;background:rgba(4,120,71,0.1);border:1px solid rgba(4,120,71,0.2);color:#047847;padding:4px 14px;border-radius:999px;font-size:0.7rem;margin-bottom:14px;">APERÇU</div>
                <div id="previewAboutTitle" style="font-size:1.5rem;font-weight:800;color:#0a1227;margin-bottom:12px;">${escapeHtml(db.about.title)}</div>
                <div id="previewAboutBadge" style="display:inline-flex;align-items:center;gap:6px;background:#047847;color:#fff;padding:6px 16px;border-radius:999px;font-size:0.75rem;"><i class="fas fa-check-circle"></i> ${escapeHtml(db.about.badge)}</div></div>
                <div><div id="previewAboutDesc1" style="background:#fff;border-left:3px solid #047847;padding:14px;border-radius:14px;margin-bottom:12px;">${escapeHtml(db.about.description1)}</div>
                <div id="previewAboutDesc2" style="background:#fff;border-left:3px solid #0564b7;padding:14px;border-radius:14px;">${escapeHtml(db.about.description2)}</div></div>
            </div>
            <div style="background:var(--surface);border-radius:24px;border:1px solid var(--border-light);padding:28px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:24px;"><h3>✏️ Éditer À propos</h3><button id="saveAboutBtn" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button></div>
                <input id="aboutTitle" value="${escapeHtml(db.about.title)}" oninput="document.getElementById('previewAboutTitle').textContent=this.value" style="width:100%;padding:12px;margin-bottom:12px;border-radius:16px;"><textarea id="aboutDesc1" rows="2" oninput="document.getElementById('previewAboutDesc1').textContent=this.value" style="width:100%;padding:12px;margin-bottom:12px;">${escapeHtml(db.about.description1)}</textarea>
                <textarea id="aboutDesc2" rows="2" oninput="document.getElementById('previewAboutDesc2').textContent=this.value" style="width:100%;padding:12px;margin-bottom:12px;">${escapeHtml(db.about.description2)}</textarea>
                <input id="aboutBadge" value="${escapeHtml(db.about.badge)}" oninput="document.getElementById('previewAboutBadge').childNodes[1].textContent=' '+this.value" style="width:100%;padding:12px;border-radius:16px;">
            </div>
        </div>`;
    }

    function renderContact(){
        return `<div class="fade-in">
            <div style="background:linear-gradient(135deg,#07102a,#0d1a3a);border-radius:28px;padding:40px;margin-bottom:28px;display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">
                ${[{icon:'map-marker-alt',id:'previewAddr',val:db.contact.address,label:'Adresse'},{icon:'envelope',id:'previewEmail',val:db.contact.email,label:'Email'},{icon:'phone',id:'previewPhone',val:db.contact.phone,label:'Téléphone'},{icon:'building',id:'previewRccm',val:db.contact.rccm,label:'RCCM'}].map(f=>`<div style="display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.06);border-radius:16px;padding:14px;"><div style="width:36px;height:36px;background:rgba(4,120,71,0.25);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#4ade80;"><i class="fas fa-${f.icon}"></i></div><div><div style="font-size:0.68rem;color:rgba(255,255,255,0.4);">${f.label}</div><div id="${f.id}" style="color:#fff;">${escapeHtml(f.val)}</div></div></div>`).join('')}
            </div>
            <div style="background:var(--surface);border-radius:24px;border:1px solid var(--border-light);padding:28px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:24px;"><h3>📞 Coordonnées</h3><button id="saveContactBtn" class="btn-primary"><i class="fas fa-save"></i> Sauvegarder</button></div>
                ${['address','email','phone','rccm'].map(f=>`<input id="contact${f.charAt(0).toUpperCase()+f.slice(1)}" value="${escapeHtml(db.contact[f])}" oninput="document.getElementById('preview${f.charAt(0).toUpperCase()+f.slice(1)}').textContent=this.value" style="width:100%;padding:12px;margin-bottom:12px;border-radius:16px;" placeholder="${f}">`).join('')}
            </div>
        </div>`;
    }

    function renderListManager(items, type){
        const cfg = { services:{icon:'chart-line',emoji:'📊',label:'Pôles',color:'#047847',nameKey:'name',subKey:'description'}, team:{icon:'users',emoji:'👥',label:'Équipe',color:'#0564b7',nameKey:'name',subKey:'bio',tagKey:'position'}, achievements:{icon:'trophy',emoji:'🏆',label:'Réalisations',color:'#e8392a',nameKey:'title',subKey:'description',tagKey:'category'}, news:{icon:'newspaper',emoji:'📰',label:'Actualités',color:'#7c3aed',nameKey:'title',subKey:'description',tagKey:'date'} }[type];
        let cards = items.map(item => `<div class="item-card"><div><strong>${escapeHtml(item[cfg.nameKey])}</strong>${cfg.tagKey?`<span style="display:inline-block;background:${cfg.color}22;color:${cfg.color};border-radius:999px;font-size:0.7rem;padding:2px 10px;margin-left:8px;">${escapeHtml(item[cfg.tagKey])}</span>`:''}<p style="margin-top:8px;font-size:0.8rem;">${escapeHtml((item[cfg.subKey]||'').substring(0,70))}</p></div><div style="display:flex;gap:8px;margin-top:12px;"><button class="edit-btn" data-id="${item.id}" data-type="${type}" class="btn-outline">Modifier</button><button class="delete-btn" data-id="${item.id}" data-type="${type}" style="background:#fee2e2;color:#e53e3e;border:none;padding:6px 12px;border-radius:12px;">Supprimer</button></div></div>`).join('');
        return `<div><div style="background:var(--surface);border-radius:24px;padding:20px;margin-bottom:20px;display:flex;justify-content:space-between;"><h3><i class="fas fa-${cfg.icon}"></i> ${cfg.label}</h3><button id="addBtn_${type}" class="btn-primary"><i class="fas fa-plus"></i> Ajouter</button></div><div class="items-grid">${cards}</div></div>`;
    }

    function renderGallery(){
        let cards = db.gallery.map((url,idx)=>`<div class="item-card" style="padding:0;overflow:hidden;"><img src="${escapeHtml(url)}" style="width:100%;height:160px;object-fit:cover;"><div style="padding:12px;display:flex;justify-content:space-between;"><span>Photo ${idx+1}</span><button class="delete-btn" data-idx="${idx}" data-gallery="true" style="background:#e53e3e;color:white;border:none;padding:6px 12px;border-radius:12px;">🗑 Supprimer</button></div></div>`).join('');
        return `<div><div style="background:var(--surface);border-radius:24px;padding:20px;margin-bottom:20px;display:flex;justify-content:space-between;"><h3><i class="fas fa-images"></i> Galerie</h3><button id="addGalleryBtn" class="btn-primary"><i class="fas fa-plus"></i> Ajouter image</button></div><div class="items-grid">${cards}</div></div>`;
    }

    function loadSection(section){
        let contentDiv=document.getElementById('dynamicContent');
        const titles={ dashboard:"Dashboard", hero:"Hero", about:"À propos", services:"Pôles", team:"Équipe", achievements:"Réalisations", news:"Actualités", gallery:"Galerie", contact:"Contact" };
        document.getElementById('mainTitle').innerText=titles[section]||"Gestion";
        if(section==='dashboard'){ contentDiv.innerHTML=renderDashboard(); updateStatsNumbers(); }
        else if(section==='hero') contentDiv.innerHTML=renderHero();
        else if(section==='about') contentDiv.innerHTML=renderAbout();
        else if(section==='contact') contentDiv.innerHTML=renderContact();
        else if(section==='services') contentDiv.innerHTML=renderListManager(db.services, "services");
        else if(section==='team') contentDiv.innerHTML=renderListManager(db.team, "team");
        else if(section==='achievements') contentDiv.innerHTML=renderListManager(db.achievements, "achievements");
        else if(section==='news') contentDiv.innerHTML=renderListManager(db.news, "news");
        else if(section==='gallery') contentDiv.innerHTML=renderGallery();
        attachSectionEvents(section);
    }

    function attachSectionEvents(section){
        if(section==='hero' && document.getElementById('saveHeroBtn')) document.getElementById('saveHeroBtn').onclick=()=>{ db.hero.title=document.getElementById('heroTitle').value; db.hero.tagline=document.getElementById('heroTagline').value; db.hero.description=document.getElementById('heroDesc').value; db.hero.btnText=document.getElementById('heroBtn').value; saveToLocalStorage(); showToast("Hero mis à jour"); };
        if(section==='about' && document.getElementById('saveAboutBtn')) document.getElementById('saveAboutBtn').onclick=()=>{ db.about.title=document.getElementById('aboutTitle').value; db.about.description1=document.getElementById('aboutDesc1').value; db.about.description2=document.getElementById('aboutDesc2').value; db.about.badge=document.getElementById('aboutBadge').value; saveToLocalStorage(); showToast("À propos mis à jour"); };
        if(section==='contact' && document.getElementById('saveContactBtn')) document.getElementById('saveContactBtn').onclick=()=>{ db.contact.address=document.getElementById('contactAddress').value; db.contact.email=document.getElementById('contactEmail').value; db.contact.phone=document.getElementById('contactPhone').value; db.contact.rccm=document.getElementById('contactRccm').value; saveToLocalStorage(); showToast("Contact mis à jour"); };
        ['services','team','achievements','news'].forEach(t=>{ if(section===t){ document.getElementById(`addBtn_${t}`)?.addEventListener('click',()=>{ let newId=Date.now(); let newItem={id:newId, name:"Nouveau", description:"Description"}; if(t==='team') newItem={id:newId, name:"Nouveau membre", position:"Poste", bio:"Bio"}; if(t==='achievements') newItem={id:newId, title:"Nouvelle réalisation", category:"Catégorie", description:"Description"}; if(t==='news') newItem={id:newId, title:"Nouvel article", date:"2025", description:"Description"}; db[t].push(newItem); saveToLocalStorage(); loadSection(t); }); attachCrudEvents(t, db[t]); } });
        if(section==='gallery'){ document.getElementById('addGalleryBtn')?.addEventListener('click',()=>{ let url=prompt("URL de l'image"); if(url) db.gallery.push(url); saveToLocalStorage(); loadSection('gallery'); }); document.querySelectorAll('[data-gallery="true"]').forEach(btn=>{ btn.onclick=()=>{ let idx=parseInt(btn.dataset.idx); db.gallery.splice(idx,1); saveToLocalStorage(); showToast("Image supprimée"); loadSection('gallery'); }; }); }
    }

    function attachCrudEvents(type, collection){
        document.querySelectorAll(`.edit-btn[data-type="${type}"]`).forEach(btn=>{ btn.onclick=()=>{ let id=parseInt(btn.dataset.id); let item=collection.find(i=>i.id===id); openModal(`Modifier ${type}`, `<input id="editName" value="${escapeHtml(item.name||item.title)}" style="width:100%;padding:12px;margin-bottom:12px;"><textarea id="editDesc" rows="3" style="width:100%;padding:12px;margin-bottom:12px;">${escapeHtml(item.description||item.bio||'')}</textarea>${type==='team'?`<input id="editPos" value="${escapeHtml(item.position)}" style="width:100%;padding:12px;">`:type==='achievements'?`<input id="editCat" value="${escapeHtml(item.category)}" style="width:100%;padding:12px;">`:type==='news'?`<input id="editDate" value="${escapeHtml(item.date)}" style="width:100%;padding:12px;">`:''}<button id="modalSaveBtn" class="btn-primary">Enregistrer</button>`, ()=>{ if(type==='team'){ item.name=document.getElementById('editName').value; item.bio=document.getElementById('editDesc').value; item.position=document.getElementById('editPos').value; } else if(type==='achievements'){ item.title=document.getElementById('editName').value; item.description=document.getElementById('editDesc').value; item.category=document.getElementById('editCat').value; } else if(type==='news'){ item.title=document.getElementById('editName').value; item.description=document.getElementById('editDesc').value; item.date=document.getElementById('editDate').value; } else { item.name=document.getElementById('editName').value; item.description=document.getElementById('editDesc').value; } saveToLocalStorage(); showToast("Mis à jour"); loadSection(type); }); }; });
        document.querySelectorAll(`.delete-btn[data-type="${type}"]`).forEach(btn=>{ btn.onclick=()=>{ if(confirm("Supprimer ?")){ let id=parseInt(btn.dataset.id); let idx=collection.findIndex(i=>i.id===id); if(idx!==-1) collection.splice(idx,1); saveToLocalStorage(); showToast("Supprimé"); loadSection(type); } }; });
    }

    function openModal(title, bodyHtml, onSave){
        const modal=document.getElementById('genericModal'); document.getElementById('modalTitle').innerText=title; document.getElementById('modalBody').innerHTML=bodyHtml; modal.style.display='flex'; document.getElementById('modalSaveBtn')?.addEventListener('click',()=>{ onSave(); modal.style.display='none'; },{ once:true }); document.querySelector('.close-modal').onclick=()=>modal.style.display='none'; window.onclick=(e)=>{ if(e.target===modal) modal.style.display='none'; };
    }

    document.querySelectorAll('.nav-item').forEach(item=>{ item.addEventListener('click',()=>{ document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active')); item.classList.add('active'); loadSection(item.dataset.section); if(window.innerWidth<768) document.getElementById('sidebar').classList.remove('mobile-open'); }); });
    document.getElementById('darkModeToggle')?.addEventListener('click',()=>{ document.body.classList.toggle('dark'); localStorage.setItem('theme', document.body.classList.contains('dark')?'dark':'light'); });
    document.getElementById('collapseSidebarBtn')?.addEventListener('click',()=>{ document.getElementById('sidebar').classList.toggle('collapsed'); });
    document.getElementById('mobileMenuToggle')?.addEventListener('click',()=>{ document.getElementById('sidebar').classList.toggle('mobile-open'); });
    if(localStorage.getItem('theme')==='dark') document.body.classList.add('dark');
    loadSection('dashboard');
</script>
</body>
</html>
