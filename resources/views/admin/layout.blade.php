<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>WIBC Admin • @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/jpeg" href="/photos/logo.jpeg">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg-app: #f0f2f8;
            --surface: #ffffff;
            --surface-elevated: #ffffff;
            --sidebar-bg: rgba(7, 16, 42, 0.99);
            --sidebar-border: rgba(255, 255, 255, 0.07);
            --text-primary: #07102a;
            --text-secondary: #4a5568;
            --text-muted: #6c7a91;
            --accent: #047847;
            --accent-light: #05a35f;
            --accent-soft: rgba(4, 120, 71, 0.10);
            --red: #e8392a;
            --red-soft: rgba(232, 57, 42, 0.10);
            --danger: #e53e3e;
            --border-light: #e8ecf4;
            --shadow-sm: 0 2px 12px rgba(7,16,42,0.06), 0 1px 4px rgba(0,0,0,0.04);
            --shadow-md: 0 16px 40px -12px rgba(7,16,42,0.12);
            --transition: all 0.22s cubic-bezier(0.2, 0, 0, 1);
        }

        body.dark {
            --bg-app: #0c1020;
            --surface: #161b2e;
            --surface-elevated: #1e2540;
            --sidebar-bg: #07102a;
            --text-primary: #e8edf8;
            --text-secondary: #a0aec0;
            --text-muted: #7e8aa2;
            --border-light: #252f4a;
            --shadow-sm: 0 4px 16px rgba(0,0,0,0.4);
            --shadow-md: 0 20px 40px rgba(0,0,0,0.5);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-app);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.2s ease;
        }

        .dashboard-container { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 260px;
            background: #07102a;
            border-right: 1px solid rgba(255,255,255,0.06);
            position: fixed;
            height: 100vh;
            transition: width 0.25s cubic-bezier(0.2,0,0,1);
            z-index: 100;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar:hover { overflow-y: auto; }

        .sidebar.collapsed { width: 68px; }
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .sidebar-brand-text,
        .sidebar.collapsed .collapse-label,
        .sidebar.collapsed .nav-section-label,
        .sidebar.collapsed .sidebar-admin-info { display: none; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 11px 0; border-radius: 12px; }
        .sidebar.collapsed .sidebar-header { justify-content: center; padding: 18px 0; }
        .sidebar.collapsed .sidebar-admin { justify-content: center; padding: 12px 0; }
        .sidebar.collapsed .nav-link.active::before { display: none; }

        /* Header */
        .sidebar-header {
            padding: 20px 16px 18px;
            display: flex;
            align-items: center;
            gap: 11px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }

        .sidebar-logo {
            width: 36px; height: 36px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1.5px solid rgba(74,222,128,0.3);
        }

        .sidebar-brand {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .sidebar-brand-text {
            font-size: 0.95rem;
            font-weight: 800;
            color: #fff;
            white-space: nowrap;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .sidebar-brand-text .brand-accent { color: #4ade80; }

        .sidebar-brand-sub {
            font-size: 0.6rem;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            white-space: nowrap;
            margin-top: 2px;
        }

        /* Nav */
        .nav-section {
            padding: 12px 10px 6px;
            flex: 1;
        }

        .nav-section-label {
            font-size: 0.58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: rgba(255,255,255,0.22);
            padding: 0 10px;
            margin: 16px 0 4px;
        }

        .nav-section-label:first-child { margin-top: 2px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 11px;
            color: rgba(255,255,255,0.42);
            font-weight: 500;
            font-size: 0.92rem;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 2px;
            position: relative;
        }

        .nav-link-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.92rem;
            flex-shrink: 0;
            transition: all 0.2s ease;
            background: rgba(255,255,255,0.04);
        }

        .nav-label { white-space: nowrap; }

        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
            text-shadow: 0 0 20px rgba(255,255,255,0.4);
        }

        .nav-link:hover .nav-link-icon {
            background: rgba(255,255,255,0.14);
            box-shadow: 0 0 16px rgba(255,255,255,0.1);
            color: #fff;
        }

        .nav-link.active {
            color: #fff;
            background: rgba(4,120,71,0.2);
            font-weight: 600;
        }

        .nav-link.active .nav-link-icon {
            background: #047847;
            box-shadow: 0 4px 16px rgba(4,120,71,0.5), 0 0 24px rgba(74,222,128,0.2);
            color: #fff;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: #4ade80;
            border-radius: 0 3px 3px 0;
            box-shadow: 0 0 10px rgba(74,222,128,0.6);
        }

        /* Sidebar footer (admin + collapse) */
        .sidebar-footer {
            padding: 10px;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-admin {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 11px;
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-admin-avatar {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, #047847, #05a35f);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.72rem;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-admin-info { min-width: 0; flex: 1; }

        .sidebar-admin-name {
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-admin-role {
            font-size: 0.62rem;
            color: rgba(255,255,255,0.3);
            white-space: nowrap;
        }

        .collapse-btn {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 11px;
            border-radius: 10px;
            background: transparent;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            font-size: 0.78rem;
            border: none;
            width: 100%;
            transition: all 0.18s ease;
            font-family: inherit;
        }

        .collapse-btn:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.7); }

        /* ── MAIN CONTENT ── */
        .main-content {
            flex: 1;
            margin-left: 256px;
            padding: 28px 32px;
            transition: var(--transition);
            min-height: 100vh;
        }

        .sidebar.collapsed ~ .main-content { margin-left: 72px; }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 26px;
            flex-wrap: wrap;
            gap: 14px;
        }

        .page-header-left h1 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
        }

        .page-header-left h1 span { color: var(--accent); }

        .page-header-left p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        .page-header-actions { display: flex; gap: 8px; align-items: center; }

        .icon-btn {
            width: 38px; height: 38px;
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.95rem;
            color: var(--text-secondary);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
        }

        .icon-btn:hover { background: var(--accent); color: white; border-color: var(--accent); }

        .admin-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #07102a, #0a1c44);
            border: 2px solid var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
            color: #fff;
        }

        /* ── SECTION CARD ── */
        .section-card {
            background: var(--surface);
            border-radius: 22px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
        }

        /* ── PREVIEW PANEL (dark navy) ── */
        .preview-panel {
            background: linear-gradient(135deg, #07102a 0%, #0a1c44 55%, #071e12 100%);
            border-radius: 24px;
            padding: 36px 40px;
            margin-bottom: 22px;
            position: relative;
            overflow: hidden;
        }

        .preview-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
        }

        .preview-panel::after {
            content: '';
            position: absolute;
            width: 280px; height: 280px;
            background: rgba(4,120,71,0.18);
            border-radius: 50%;
            filter: blur(70px);
            top: -80px; right: -40px;
            pointer-events: none;
        }

        .preview-panel-inner { position: relative; z-index: 2; }

        /* ── LIVE BADGE ── */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(74,222,128,0.10);
            border: 1px solid rgba(74,222,128,0.25);
            color: #4ade80;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .live-badge-dot {
            width: 6px; height: 6px;
            background: #4ade80;
            border-radius: 50%;
            display: inline-block;
            animation: livePulse 2s ease infinite;
        }

        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.7); }
        }

        /* ── EDITOR CARD ── */
        .editor-card {
            background: var(--surface);
            border-radius: 22px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .editor-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-light);
            background: linear-gradient(90deg, rgba(4,120,71,0.04), transparent);
        }

        .editor-card-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .editor-card-icon {
            width: 38px; height: 38px;
            background: var(--accent-soft);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .editor-card-icon.red { background: var(--red-soft); color: var(--red); }

        .editor-card-label { font-weight: 700; font-size: 0.95rem; color: var(--text-primary); }
        .editor-card-sub { font-size: 0.73rem; color: var(--text-muted); margin-top: 2px; }

        .editor-card-body { padding: 24px; }

        /* ── FORM GRID ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-grid .full { grid-column: 1/-1; }

        @media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } .form-grid .full { grid-column: 1; } }

        /* ── FIELD ── */
        .field-group { margin-bottom: 0; }

        .field-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.71rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .field-label i { color: var(--accent); font-size: 0.75rem; }

        .field-input {
            width: 100%;
            padding: 11px 15px;
            border-radius: 12px;
            border: 1.5px solid var(--border-light);
            background: var(--bg-app);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .field-input:hover { border-color: rgba(4,120,71,0.4); }
        .field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(4,120,71,0.08); }

        textarea.field-input { resize: vertical; line-height: 1.55; }

        /* ── BUTTONS ── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 9px 20px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.83rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(4,120,71,0.25);
            white-space: nowrap;
        }

        .btn-primary:hover { background: var(--accent-light); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(4,120,71,0.35); }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--red-soft);
            color: var(--red);
            border: 1.5px solid rgba(232,57,42,0.25);
            padding: 9px 18px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.83rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-danger:hover { background: var(--red); color: #fff; }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: transparent;
            color: var(--text-secondary);
            border: 1.5px solid var(--border-light);
            padding: 9px 18px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.83rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

        /* ── ITEMS GRID ── */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .item-card {
            background: var(--surface);
            border-radius: 18px;
            padding: 18px;
            border: 1px solid var(--border-light);
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .item-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

        .item-icon {
            width: 42px; height: 42px;
            background: var(--accent-soft);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .item-icon.red { background: var(--red-soft); color: var(--red); }

        .item-actions {
            display: flex;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid var(--border-light);
            margin-top: 14px;
        }

        .btn-edit {
            flex: 1;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1.5px solid var(--border-light);
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.79rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: var(--transition);
            font-family: inherit;
        }

        .btn-edit:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }

        .btn-delete {
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1.5px solid rgba(229,62,62,0.2);
            background: rgba(229,62,62,0.06);
            color: #e53e3e;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .btn-delete:hover { background: #e53e3e; color: #fff; border-color: #e53e3e; }

        /* ── FILTER CHIPS ── */
        .filter-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .chip {
            padding: 6px 16px;
            border-radius: 999px;
            border: 1.5px solid var(--border-light);
            background: var(--surface);
            color: var(--text-secondary);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
        }

        .chip:hover { border-color: var(--accent); color: var(--accent); }
        .chip.active { background: var(--accent); color: #fff; border-color: var(--accent); box-shadow: 0 3px 10px rgba(4,120,71,0.25); }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
        }

        .badge-green { background: rgba(4,120,71,0.10); color: #047847; }
        .badge-red   { background: rgba(232,57,42,0.10); color: #e8392a; }
        .badge-navy  { background: rgba(7,16,42,0.08); color: #07102a; }

        /* ── STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .stat-card {
            border-radius: 20px;
            padding: 22px 20px;
            color: white;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .stat-card-1 { background: linear-gradient(135deg, #07102a, #0d1f52); }
        .stat-card-2 { background: linear-gradient(135deg, #047847, #05a35f); }
        .stat-card-3 { background: linear-gradient(135deg, #e8392a, #f55a4e); }
        .stat-card-4 { background: linear-gradient(135deg, #07102a, #1a2e5a); }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(255,255,255,0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 36px rgba(0,0,0,0.18); }
        .stat-card h3 { font-size: 2.1rem; font-weight: 900; margin-bottom: 4px; letter-spacing: -0.03em; }
        .stat-card p  { font-size: 0.78rem; opacity: 0.8; }
        .stat-card i  { position: absolute; right: 18px; bottom: 14px; font-size: 2.2rem; opacity: 0.12; }

        /* ── MODAL ── */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(7,16,42,0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal.open { display: flex; animation: fadeIn 0.18s ease; }

        .modal-content {
            background: var(--surface);
            border-radius: 24px;
            width: 100%;
            max-width: 480px;
            padding: 28px;
            animation: scaleUp 0.2s cubic-bezier(0.2,0.9,0.4,1.1);
            box-shadow: 0 30px 60px rgba(7,16,42,0.25);
            border: 1px solid var(--border-light);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-light);
        }

        .modal-header h3 { font-size: 1rem; font-weight: 800; }

        .close-modal {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--bg-app);
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .close-modal:hover { background: var(--red-soft); color: var(--red); }

        .modal-content .field-group { margin-bottom: 16px; }

        /* ── TOAST ── */
        .toast-notify {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #07102a;
            color: #fff;
            padding: 11px 22px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.83rem;
            z-index: 2000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            animation: slideUp 0.3s ease forwards;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast-notify.success { background: var(--accent); }
        .toast-notify.error   { background: var(--danger); }

        /* ── ANIMATIONS ── */
        @keyframes fadeIn   { from { opacity: 0; } to { opacity: 1; } }
        @keyframes scaleUp  { from { transform: scale(0.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        @keyframes slideUp  { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .fade-in { animation: fadeInUp 0.35s ease both; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; padding: 16px; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
            .preview-panel { padding: 24px 20px; }
        }

        /* ── UTILITY ── */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .count-label {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .count-label strong { color: var(--text-primary); }
    </style>
</head>
<body>
<div class="dashboard-container">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <!-- Brand -->
        <div class="sidebar-header">
            <img class="sidebar-logo" src="/photos/logo.jpeg" alt="WIBC" onerror="this.src='https://placehold.co/36x36?text=W'">
            <div class="sidebar-brand">
                <span class="sidebar-brand-text">WI<span class="brand-accent">BC</span> Admin</span>
                <span class="sidebar-brand-sub">Panneau de gestion</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="nav-section">

            <div class="nav-section-label">Principal</div>
            <a href="/admin" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="nav-label">Dashboard</span>
            </a>

            <div class="nav-section-label">Contenu</div>
            <a href="/admin/hero" class="nav-link {{ request()->is('admin/hero') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-home"></i></span>
                <span class="nav-label">Hero</span>
            </a>
            <a href="/admin/about" class="nav-link {{ request()->is('admin/about') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-building"></i></span>
                <span class="nav-label">À propos</span>
            </a>
            <a href="/admin/services" class="nav-link {{ request()->is('admin/services') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-chart-line"></i></span>
                <span class="nav-label">Pôles</span>
            </a>
            <a href="/admin/team" class="nav-link {{ request()->is('admin/team') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-users"></i></span>
                <span class="nav-label">Équipe</span>
            </a>
            <a href="/admin/achievements" class="nav-link {{ request()->is('admin/achievements') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-trophy"></i></span>
                <span class="nav-label">Réalisations</span>
            </a>
            <a href="/admin/news" class="nav-link {{ request()->is('admin/news') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-newspaper"></i></span>
                <span class="nav-label">Actualités</span>
            </a>
            <a href="/admin/gallery" class="nav-link {{ request()->is('admin/gallery') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-images"></i></span>
                <span class="nav-label">Galerie</span>
            </a>
            <a href="/admin/contact" class="nav-link {{ request()->is('admin/contact') ? 'active' : '' }}">
                <span class="nav-link-icon"><i class="fas fa-address-card"></i></span>
                <span class="nav-label">Contact</span>
            </a>

            <div class="nav-section-label">Site web</div>
            <a href="/" target="_blank" class="nav-link">
                <span class="nav-link-icon"><i class="fas fa-external-link-alt"></i></span>
                <span class="nav-label">Voir le site</span>
            </a>

        </nav>

        <!-- Footer : admin + collapse -->
        <div class="sidebar-footer">
            <div class="sidebar-admin">
                <div class="sidebar-admin-avatar">AD</div>
                <div class="sidebar-admin-info">
                    <div class="sidebar-admin-name">Administrateur</div>
                    <div class="sidebar-admin-role">WIBC · Super Admin</div>
                </div>
            </div>
            <form method="POST" action="/admin/logout" style="margin:0;">
                @csrf
                <button type="submit" title="Se déconnecter" style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 14px;background:rgba(243,66,68,0.08);border:1px solid rgba(243,66,68,0.15);border-radius:10px;color:#f87171;font-size:0.8rem;font-weight:600;cursor:pointer;font-family:inherit;transition:background 0.2s;margin-bottom:6px;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="collapse-label">Se déconnecter</span>
                </button>
            </form>
            <button class="collapse-btn" id="collapseBtn">
                <i class="fas fa-chevron-left" id="collapseIcon"></i>
                <span class="collapse-label">Réduire le menu</span>
            </button>
        </div>

    </aside>

    <!-- Main -->
    <main class="main-content" id="mainContent">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1>@yield('title', 'Dashboard')</h1>
                <p>@yield('subtitle', 'Gestion centralisée • WIBC Admin')</p>
            </div>
            <div class="page-header-actions">
                <button class="icon-btn" id="darkModeToggle" title="Mode sombre"><i class="fas fa-moon"></i></button>
                <button class="icon-btn" id="mobileMenuToggle" title="Menu"><i class="fas fa-bars"></i></button>
                <div class="admin-avatar">AD</div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="fade-in">
            @yield('content')
        </div>
    </main>
</div>

<!-- Modal -->
<div class="modal" id="genericModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle"></h3>
            <button class="close-modal">&times;</button>
        </div>
        <div id="modalBody"></div>
    </div>
</div>

<script>
    // ── CSRF ──
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── API helper ──
    async function api(method, url, body = null) {
        const opts = {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        };
        if (body) opts.body = JSON.stringify(body);
        const res = await fetch(url, opts);
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message ?? `HTTP ${res.status}`);
        }
        return res.json();
    }

    function escapeHtml(s){ return String(s).replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }

    function showToast(msg, type='success'){
        const t = document.createElement('div');
        t.className = 'toast-notify ' + type;
        t.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':'exclamation-circle'}"></i> ${msg}`;
        document.body.appendChild(t);
        setTimeout(()=>t.remove(), 2800);
    }

    function openModal(title, bodyHtml, onSave){
        const modal = document.getElementById('genericModal');
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalBody').innerHTML = bodyHtml;
        modal.classList.add('open');
        document.getElementById('modalSaveBtn')?.addEventListener('click', ()=>{ onSave(); modal.classList.remove('open'); }, { once: true });
        modal.querySelector('.close-modal').onclick = ()=> modal.classList.remove('open');
        modal.onclick = e => { if(e.target === modal) modal.classList.remove('open'); };
    }

    // Sidebar collapse
    const sidebar = document.getElementById('sidebar');
    const collapseBtn = document.getElementById('collapseBtn');
    const collapseIcon = document.getElementById('collapseIcon');

    if(localStorage.getItem('sidebar_collapsed') === '1') {
        sidebar.classList.add('collapsed');
        collapseIcon.classList.replace('fa-chevron-left','fa-chevron-right');
    }

    collapseBtn?.addEventListener('click', ()=>{
        sidebar.classList.toggle('collapsed');
        const collapsed = sidebar.classList.contains('collapsed');
        collapseIcon.classList.toggle('fa-chevron-left', !collapsed);
        collapseIcon.classList.toggle('fa-chevron-right', collapsed);
        localStorage.setItem('sidebar_collapsed', collapsed ? '1' : '0');
    });

    // Dark mode
    if(localStorage.getItem('theme') === 'dark') document.body.classList.add('dark');
    document.getElementById('darkModeToggle')?.addEventListener('click', ()=>{
        document.body.classList.toggle('dark');
        localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    });

    // Mobile menu
    document.getElementById('mobileMenuToggle')?.addEventListener('click', ()=>{
        sidebar.classList.toggle('mobile-open');
    });
</script>

@yield('scripts')
</body>
</html>
