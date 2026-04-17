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
    <title>WIBC - World Innovations Business Consulting</title>
    <link rel="icon" type="image/jpeg" href="photos/logo.jpeg">
    <link rel="shortcut icon" type="image/jpeg" href="photos/logo.jpeg">
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
            --navy-mid: #1a2357;
            --blue: #0564B7;
            --blue-light: #0a84ff;
            --gold: #C9A227;
            --surface: #eef1f9;
            --surface-2: #e3e8f4;
            --border: rgba(10, 18, 39, 0.10);
            --border-light: rgba(10, 18, 39, 0.06);
            --text-muted: rgba(10, 18, 39, 0.52);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 8px rgba(10, 18, 39, 0.08);
            --shadow-md: 0 8px 32px rgba(10, 18, 39, 0.12);
            --shadow-lg: 0 20px 60px rgba(10, 18, 39, 0.16);
            --shadow-xl: 0 32px 80px rgba(10, 18, 39, 0.20);
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            color: var(--black);
            background-color: #f8fafd;
            overflow-x: hidden;
            font-weight: 400;
        }

        h1, h2, h3, h4 { font-family: 'Space Grotesk', 'Inter', sans-serif; }

        .container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 28px;
        }

        /* ── SCROLL PROGRESS ── */
        #scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--green), var(--blue), var(--red));
            z-index: 9999;
            width: 0%;
            transition: width 0.1s linear;
        }

        /* ── HEADER ── */
        header {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        .header-inner {
            background: rgba(10, 14, 39, 0.0);
            transition: var(--transition);
        }

        .header-scrolled .header-inner {
            background: rgba(10, 14, 39, 0.96);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 4px 30px rgba(0,0,0,0.3);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 0;
            transition: var(--transition);
        }

        .header-scrolled .navbar {
            padding: 14px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-image {
            height: 58px;
            width: auto;
            border-radius: 8px;
            transition: var(--transition);
        }

        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--white);
            letter-spacing: 0.5px;
        }

        .logo-text span {
            color: var(--green-light);
        }

        .logo:hover .logo-image { transform: scale(1.06); }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 6px;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255,255,255,0.75);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 14px;
            border-radius: 8px;
            transition: var(--transition);
            letter-spacing: 0.2px;
        }

        .nav-links a:hover {
            color: var(--white);
            background: rgba(255,255,255,0.1);
        }

        .nav-cta {
            background: var(--green) !important;
            color: var(--white) !important;
            padding: 9px 20px !important;
        }

        .nav-cta:hover {
            background: var(--green-light) !important;
            transform: translateY(-1px);
        }

        .mobile-menu-btn {
            display: none;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: var(--white);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .mobile-menu-btn:hover { background: rgba(255,255,255,0.2); }

        /* ── HERO ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            background-color: var(--navy);
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('photos/fond.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.25;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(10, 14, 39, 0.98) 0%,
                rgba(10, 14, 39, 0.85) 40%,
                rgba(4, 120, 71, 0.25) 100%
            );
        }

        .hero-grid-pattern {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .hero-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
        }

        .hero-glow-1 {
            background: rgba(4, 120, 71, 0.25);
            top: -100px;
            right: -100px;
        }

        .hero-glow-2 {
            background: rgba(5, 100, 183, 0.2);
            bottom: -200px;
            left: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 820px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(4, 120, 71, 0.15);
            border: 1px solid rgba(4, 120, 71, 0.4);
            color: #4ade80;
            padding: 8px 18px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 32px;
        }

        .hero-eyebrow::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #4ade80;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }

        .hero h1 {
            font-size: clamp(2.4rem, 5vw, 4.2rem);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -2px;
            color: var(--white);
            margin-bottom: 12px;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, #526bda, #4ade80);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-tagline {
            font-size: 1.3rem;
            font-weight: 600;
            color: rgba(255,255,255,0.85);
            margin-bottom: 20px;
            letter-spacing: -0.3px;
        }

        .hero-description {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.55);
            margin-bottom: 48px;
            max-width: 560px;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: var(--green);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(4, 120, 71, 0.4);
        }

        .btn-primary:hover {
            background: var(--green-light);
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(4, 120, 71, 0.5);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-ghost:hover {
            background: rgba(255,255,255,0.12);
            color: var(--white);
            border-color: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        .hero-scroll-hint {
            position: absolute;
            bottom: 36px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            z-index: 2;
            color: rgba(255,255,255,0.3);
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .scroll-mouse {
            width: 22px;
            height: 36px;
            border: 2px solid rgba(255,255,255,0.25);
            border-radius: 11px;
            display: flex;
            justify-content: center;
            padding-top: 5px;
        }

        .scroll-mouse::after {
            content: '';
            width: 3px;
            height: 8px;
            background: rgba(255,255,255,0.5);
            border-radius: 2px;
            animation: scroll-down 1.6s ease-in-out infinite;
        }

        @keyframes scroll-down {
            0% { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(10px); opacity: 0; }
        }

        /* ── STATS BAR ── */
        .stats-bar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .stat-item {
            padding: 36px 28px;
            text-align: center;
            border-right: 1px solid var(--border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-item:last-child { border-right: none; }

        .stat-item::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            transform: scaleX(0);
            transition: var(--transition);
        }

        .stat-item:nth-child(1)::before { background: var(--green); }
        .stat-item:nth-child(2)::before { background: var(--blue); }
        .stat-item:nth-child(3)::before { background: var(--red); }
        .stat-item:nth-child(4)::before { background: var(--gold); }

        .stat-item:hover::before { transform: scaleX(1); }

        .stat-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 6px;
            letter-spacing: -1.5px;
        }

        .stat-item:nth-child(1) .stat-value { color: var(--green); }
        .stat-item:nth-child(2) .stat-value { color: var(--blue); }
        .stat-item:nth-child(3) .stat-value { color: var(--red); }
        .stat-item:nth-child(4) .stat-value { color: var(--gold); }

        .stat-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* ── SECTIONS ── */
        .section { padding: 110px 0; }

        .section-alt {
            background: linear-gradient(160deg, var(--surface) 0%, var(--surface-2) 100%);
            border-top: 1px solid rgba(10, 18, 39, 0.06);
            border-bottom: 1px solid rgba(10, 18, 39, 0.06);
        }

        .section-dark {
            background: var(--navy);
            color: var(--white);
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--green);
            margin-bottom: 16px;
        }

        .section-label::before {
            content: '';
            width: 20px;
            height: 2px;
            background: var(--green);
        }

        .section-dark .section-label { color: #4ade80; }
        .section-dark .section-label::before { background: #4ade80; }

        .section-title h2 {
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -1.2px;
            color: var(--navy-mid);
            line-height: 1.15;
            margin-bottom: 18px;
            color: var(--black);
        }

        .section-dark .section-title h2 { color: var(--white); }

        .section-title p {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 600px;
            line-height: 1.8;
        }

        .section-dark .section-title p { color: rgba(255,255,255,0.55); }

        .section-title.centered { text-align: center; }
        .section-title.centered p { margin: 0 auto; }

        /* ── ABOUT ── */
        .about-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .about-visual {
            position: relative;
        }

        .about-image-wrap {
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            aspect-ratio: 4/5;
        }

        .about-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-slow);
        }

        .about-image-wrap:hover img { transform: scale(1.04); }

        .about-badge {
            position: absolute;
            bottom: -24px;
            right: -24px;
            background: var(--green);
            color: var(--white);
            padding: 22px 24px;
            border-radius: var(--radius-md);
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        .about-badge-num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .about-badge-text {
            font-size: 0.78rem;
            font-weight: 600;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .about-text .section-label { margin-top: 0; }

        .about-text h3 {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.8px;
            line-height: 1.2;
            margin-bottom: 20px;
            color: var(--black);
        }

        .about-text p {
            color: var(--text-muted);
            font-size: 1.05rem;
            margin-bottom: 18px;
            line-height: 1.8;
        }

        .pillars {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 32px;
        }

        .pillar-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            background: var(--surface);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .pillar-item:hover {
            background: var(--white);
            border-color: var(--green);
            transform: translateX(6px);
            box-shadow: var(--shadow-sm);
        }

        .pillar-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--white);
            flex-shrink: 0;
        }

        .pillar-icon.green { background: var(--green); }
        .pillar-icon.blue { background: var(--blue); }
        .pillar-icon.red { background: var(--red); }

        .pillar-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--black);
        }

        /* ── SERVICES ── */
        @keyframes svcIn {
            0%   { opacity: 0; transform: translateY(50px) scale(0.94); }
            65%  { opacity: 1; transform: translateY(-4px) scale(1.01); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        #services {
            background: linear-gradient(160deg, #07102a 0%, #0a1227 55%, #0e1c38 100%);
            position: relative;
            overflow: hidden;
        }
        #services::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(4,120,71,0.08) 0%, transparent 70%);
            top: -100px; right: -100px;
            pointer-events: none;
        }
        #services .section-label { color: rgba(255,255,255,0.5); }
        #services .section-label::before { background: var(--green); }
        #services h2 { color: #fff; }
        #services .section-title p { color: rgba(255,255,255,0.45); }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 60px;
            position: relative;
            z-index: 1;
        }

        .service-card {
            border-radius: var(--radius-lg);
            padding: 44px 36px;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transition: transform 0.4s cubic-bezier(.22,.68,0,1.2), box-shadow 0.4s ease;
            border: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(12px);
        }

        /* Card backgrounds */
        .service-card:nth-child(1) { background: linear-gradient(145deg, rgba(4,120,71,0.18) 0%, rgba(4,120,71,0.05) 100%); }
        .service-card:nth-child(2) { background: linear-gradient(145deg, rgba(5,100,183,0.18) 0%, rgba(5,100,183,0.05) 100%); }
        .service-card:nth-child(3) { background: linear-gradient(145deg, rgba(243,66,68,0.18) 0%, rgba(243,66,68,0.05) 100%); }

        /* Top colored bar */
        .service-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }
        .service-card:nth-child(1)::before { background: linear-gradient(90deg, #047847, #4ade80); }
        .service-card:nth-child(2)::before { background: linear-gradient(90deg, #0564B7, #60a5fa); }
        .service-card:nth-child(3)::before { background: linear-gradient(90deg, #F34244, #f87171); }

        /* Big watermark number */
        .service-num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 6rem;
            font-weight: 900;
            opacity: 0.05;
            position: absolute;
            bottom: -10px; right: 16px;
            line-height: 1;
            color: #fff;
            pointer-events: none;
        }

        .service-card.animated { animation: svcIn 0.7s cubic-bezier(.22,.68,0,1.2) forwards; }
        .service-card:nth-child(2).animated { animation-delay: 0.13s; }
        .service-card:nth-child(3).animated { animation-delay: 0.26s; }

        .service-card:hover {
            transform: translateY(-12px) scale(1.02);
        }
        .service-card:nth-child(1):hover { box-shadow: 0 24px 60px rgba(4,120,71,0.25); }
        .service-card:nth-child(2):hover { box-shadow: 0 24px 60px rgba(5,100,183,0.25); }
        .service-card:nth-child(3):hover { box-shadow: 0 24px 60px rgba(243,66,68,0.25); }

        .service-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--white);
            margin-bottom: 28px;
            transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }
        .service-card:nth-child(1) .service-icon-wrap { background: linear-gradient(135deg, #047847, #059c60); box-shadow: 0 8px 24px rgba(4,120,71,0.35); }
        .service-card:nth-child(2) .service-icon-wrap { background: linear-gradient(135deg, #0564B7, #0a84ff); box-shadow: 0 8px 24px rgba(5,100,183,0.35); }
        .service-card:nth-child(3) .service-icon-wrap { background: linear-gradient(135deg, #F34244, #f87171); box-shadow: 0 8px 24px rgba(243,66,68,0.35); }

        .service-card:hover .service-icon-wrap { transform: scale(1.12) rotate(-6deg); }

        .service-card h3 {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -0.4px;
            margin-bottom: 12px;
            color: #fff;
        }

        .service-card > p {
            color: rgba(255,255,255,0.55);
            font-size: 0.9rem;
            margin-bottom: 28px;
            line-height: 1.75;
        }

        .service-features {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .service-features li {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 999px;
            letter-spacing: 0.3px;
        }
        .service-card:nth-child(1) .service-features li { background: rgba(4,120,71,0.20); color: #4ade80; border: 1px solid rgba(74,222,128,0.2); }
        .service-card:nth-child(2) .service-features li { background: rgba(5,100,183,0.20); color: #93c5fd; border: 1px solid rgba(147,197,253,0.2); }
        .service-card:nth-child(3) .service-features li { background: rgba(243,66,68,0.20); color: #fca5a5; border: 1px solid rgba(252,165,165,0.2); }

        .service-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 28px;
            padding: 10px 24px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.20);
            color: rgba(255,255,255,0.85);
            background: rgba(255,255,255,0.06);
            transition: background 0.3s ease, border-color 0.3s ease,
                        color 0.3s ease, transform 0.2s ease;
        }
        .service-btn i { font-size: 0.7rem; transition: transform 0.25s ease; }
        .service-btn:hover { transform: translateX(4px); }
        .service-card:nth-child(1) .service-btn:hover { background: rgba(4,120,71,0.30); border-color: #4ade80; color: #4ade80; }
        .service-card:nth-child(2) .service-btn:hover { background: rgba(5,100,183,0.30); border-color: #93c5fd; color: #93c5fd; }
        .service-card:nth-child(3) .service-btn:hover { background: rgba(243,66,68,0.30); border-color: #fca5a5; color: #fca5a5; }
        .service-btn:hover i { transform: translateX(4px); }

        /* ── TEAM ── */
        @keyframes teamCardIn {
            0%   { opacity: 0; transform: translateY(50px) scale(0.95); }
            60%  { opacity: 1; transform: translateY(-6px) scale(1.01); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        #team .container {
            max-width: 1400px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-top: 60px;
        }

        .team-card {
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            transition: transform 0.4s cubic-bezier(.22,.68,0,1.2), box-shadow 0.4s ease;
            opacity: 0;
            cursor: pointer;
            aspect-ratio: 3/4;
        }

        .team-card.animated {
            animation: teamCardIn 0.75s cubic-bezier(.22,.68,0,1.2) forwards;
        }

        .team-card.animated:nth-child(2) { animation-delay: 0.15s; }
        .team-card.animated:nth-child(3) { animation-delay: 0.30s; }

        .team-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 24px 60px rgba(0,0,0,0.20);
        }

        /* Photo — fills the full card */
        .team-photo {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .team-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 15%;
            transition: transform 0.7s ease;
        }

        .team-card:hover .team-photo img { transform: scale(1.08); }

        /* Bandeau nom toujours visible en bas */
        .team-name-bar {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(to top, rgba(10,14,39,0.85) 0%, transparent 100%);
            padding: 48px 24px 20px;
            transition: opacity 0.35s ease;
        }

        .team-card:hover .team-name-bar { opacity: 0; }

        .team-name-bar h3 {
            font-size: 1.05rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #fff;
            margin: 0 0 4px;
            line-height: 1.3;
        }

        .team-name-bar .team-position {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0;
        }

        /* Overlay infos — glisse depuis le bas au hover */
        .team-info {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(10,14,39,0.97) 0%,
                rgba(10,14,39,0.80) 60%,
                rgba(10,14,39,0.40) 100%
            );
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 32px 28px;
            transform: translateY(100%);
            transition: transform 0.45s cubic-bezier(.22,.68,0,1.1);
        }

        .team-card:hover .team-info {
            transform: translateY(0);
        }

        .team-info h3 {
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
            color: #fff;
            line-height: 1.4;
        }

        .team-position {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 14px;
        }

        .team-bio {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.80);
            line-height: 1.65;
            margin-bottom: 20px;
        }

        .team-btn {
            display: inline-block;
            border: 2px solid #e8392a;
            color: #fff;
            border-radius: 999px;
            padding: 9px 28px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease;
            align-self: flex-start;
            background: #e8392a;
        }

        .team-btn:hover {
            background: transparent;
            transform: scale(1.05);
            color: #e8392a;
        }

        /* Social links + button row */
        .team-action-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 6px;
            flex-wrap: wrap;
        }
        .team-socials {
            display: flex;
            gap: 8px;
        }
        .team-socials a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.20);
            color: #fff;
            font-size: 0.8rem;
            text-decoration: none;
            transition: background 0.25s ease, transform 0.2s ease, border-color 0.25s ease;
        }
        .team-socials a:hover {
            background: rgba(255,255,255,0.22);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-3px);
        }
        .team-socials a.soc-linkedin:hover  { background: #0077b5; border-color: #0077b5; }
        .team-socials a.soc-instagram:hover { background: #e1306c; border-color: #e1306c; }
        .team-socials a.soc-facebook:hover  { background: #1877f2; border-color: #1877f2; }
        .team-socials a.soc-twitter:hover   { background: #1a1a1a; border-color: #555; }

        /* ── RÉALISATIONS ── */
        #achievements {
            background: linear-gradient(160deg, #040c1e 0%, #061025 60%, #081530 100%);
            padding: 110px 0;
            position: relative;
            overflow: clip;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        #achievements .section-label { color: rgba(255,255,255,0.5); }
        #achievements .section-label::before { background: var(--green); }
        #achievements h2 { color: #fff; }
        #achievements .section-title p { color: rgba(255,255,255,0.45); }

        /* Animations */
        @keyframes achFromLeft {
            0%   { opacity: 0; transform: translateX(-70px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes achFromRight {
            0%   { opacity: 0; transform: translateX(70px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes achImgScale {
            0%   { transform: scale(1.12); }
            100% { transform: scale(1); }
        }

        /* Deck stack */
        .ach-list {
            margin-top: 60px;
            position: relative;
            z-index: 1;
            height: 420px;
            overflow: visible;
        }

        .ach-strip {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: absolute;
            left: 0; right: 0;
            top: 0;
            transition: transform 0.55s cubic-bezier(.22,.68,0,1.1),
                        opacity  0.55s ease,
                        box-shadow 0.55s ease;
            cursor: pointer;
        }

        /* Stack positions — back cards peek from below the front card */
        .ach-strip.pos-0 { transform: translateY(0px)   scale(1);    z-index: 4; opacity: 1;    box-shadow: 0 24px 60px rgba(0,0,0,0.55); }
        .ach-strip.pos-1 { transform: translateY(10px)  scale(0.97); z-index: 3; opacity: 0.75; box-shadow: none; }
        .ach-strip.pos-2 { transform: translateY(18px)  scale(0.94); z-index: 2; opacity: 0.50; box-shadow: none; }
        .ach-strip.pos-3 { transform: translateY(24px)  scale(0.91); z-index: 1; opacity: 0.25; box-shadow: none; }

        /* Photo side */
        .ach-img {
            position: relative;
            overflow: hidden;
            aspect-ratio: 16/9;
        }
        .ach-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.7s ease;
            display: block;
        }
        .ach-strip.animated .ach-img img {
            animation: achImgScale 1s cubic-bezier(.25,.46,.45,.94) forwards;
        }
        .ach-strip:hover .ach-img img { transform: scale(1.06); }

        /* Dimming overlay on photo */
        .ach-img::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(7,16,42,0.35);
            transition: background 0.4s ease;
        }
        .ach-strip:hover .ach-img::after { background: rgba(7,16,42,0.15); }

        /* Reverse layout */
        .ach-strip.ach-reverse .ach-img { order: 2; }
        .ach-strip.ach-reverse .ach-text { order: 1; }

        /* Text side */
        .ach-text {
            padding: 28px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }
        .ach-strip--green .ach-text { background: linear-gradient(135deg, #043d24 0%, #062e1c 100%); }
        .ach-strip--blue  .ach-text { background: linear-gradient(135deg, #032870 0%, #021c52 100%); }
        .ach-strip--red   .ach-text { background: linear-gradient(135deg, #5a0e0e 0%, #3d0808 100%); }
        .ach-strip--gold  .ach-text { background: linear-gradient(135deg, #4a3200 0%, #321f00 100%); }

        /* Big decorative number */
        .ach-text::before {
            content: attr(data-num);
            position: absolute;
            right: -10px; bottom: -20px;
            font-size: 9rem;
            font-weight: 900;
            font-family: 'Space Grotesk', sans-serif;
            line-height: 1;
            pointer-events: none;
            opacity: 0.07;
            color: #fff;
        }

        .ach-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            width: fit-content;
        }
        .ach-strip--green .ach-tag { background: rgba(74,222,128,0.12); color: #4ade80; border: 1px solid rgba(74,222,128,0.25); }
        .ach-strip--blue  .ach-tag { background: rgba(96,165,250,0.12); color: #93c5fd; border: 1px solid rgba(147,197,253,0.25); }
        .ach-strip--red   .ach-tag { background: rgba(248,113,113,0.12); color: #fca5a5; border: 1px solid rgba(252,165,165,0.25); }
        .ach-strip--gold  .ach-tag { background: rgba(253,211,77,0.12); color: #fde68a; border: 1px solid rgba(253,230,138,0.25); }

        .ach-text h3 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.3;
            letter-spacing: -0.3px;
        }

        .ach-text p {
            font-size: 0.83rem;
            color: rgba(255,255,255,0.58);
            line-height: 1.65;
            max-width: 380px;
        }

        /* Colored left accent bar */
        .ach-text::after {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 4px;
            border-radius: 0 4px 4px 0;
        }
        .ach-strip--green .ach-text::after { background: linear-gradient(to bottom, #4ade80, #047847); }
        .ach-strip--blue  .ach-text::after { background: linear-gradient(to bottom, #60a5fa, #0564B7); }
        .ach-strip--red   .ach-text::after { background: linear-gradient(to bottom, #f87171, #F34244); }
        .ach-strip--gold  .ach-text::after { background: linear-gradient(to bottom, #fcd34d, #C9A227); }

        /* Reverse: accent bar on right */
        .ach-strip.ach-reverse .ach-text::after {
            left: unset; right: 0;
            border-radius: 4px 0 0 4px;
        }

        /* Voir plus button */
        .ach-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            padding: 9px 22px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-decoration: none;
            border: 1.5px solid rgba(255,255,255,0.22);
            color: #fff;
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(4px);
            transition: background 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
            position: relative;
            z-index: 10;
            width: fit-content;
        }
        .ach-btn:hover {
            background: rgba(255,255,255,0.14);
            border-color: rgba(255,255,255,0.45);
            transform: translateY(-2px);
            color: #fff;
        }
        .ach-strip--green .ach-btn { border-color: rgba(74,222,128,0.4); color: #4ade80; }
        .ach-strip--green .ach-btn:hover { background: rgba(74,222,128,0.12); border-color: #4ade80; color: #4ade80; }
        .ach-strip--blue  .ach-btn { border-color: rgba(147,197,253,0.4); color: #93c5fd; }
        .ach-strip--blue  .ach-btn:hover { background: rgba(96,165,250,0.12); border-color: #93c5fd; color: #93c5fd; }
        .ach-strip--red   .ach-btn { border-color: rgba(252,165,165,0.4); color: #fca5a5; }
        .ach-strip--red   .ach-btn:hover { background: rgba(248,113,113,0.12); border-color: #fca5a5; color: #fca5a5; }
        .ach-strip--gold  .ach-btn { border-color: rgba(253,230,138,0.4); color: #fde68a; }
        .ach-strip--gold  .ach-btn:hover { background: rgba(253,211,77,0.12); border-color: #fde68a; color: #fde68a; }

        /* Progress dots */
        .ach-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            position: relative;
            z-index: 10;
        }
        .ach-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.25);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            padding: 0;
        }
        .ach-dot.active {
            background: #fff;
            width: 26px;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .ach-list  { height: 520px; }
            .ach-strip { grid-template-columns: 1fr; }
            .ach-img   { aspect-ratio: 4/3; }
            .ach-text  { padding: 16px 20px; }
            .ach-text h3 { font-size: 0.9rem; }
            .ach-text p  { display: none; }
            .ach-strip.ach-reverse .ach-img { order: 0; }
            .ach-strip.ach-reverse .ach-text { order: 1; }
        }

        /* ── ACTUALITÉS ── */
        @keyframes newsCardIn {
            0%   { opacity: 0; transform: translateY(60px) scale(0.94); }
            65%  { opacity: 1; transform: translateY(-5px) scale(1.01); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        #actualites .container {
            max-width: 1400px;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-top: 60px;
        }

        .news-card {
            background: #fff;
            border-radius: var(--radius-lg);
            border-top: 5px solid var(--green);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            opacity: 0;
            /* depth */
            box-shadow:
                0 2px 4px rgba(10,18,39,0.06),
                0 8px 20px rgba(10,18,39,0.10),
                0 24px 48px rgba(10,18,39,0.10);
            transition: transform 0.4s cubic-bezier(.22,.68,0,1.2),
                        box-shadow 0.4s ease;
        }

        .news-card.animated {
            animation: newsCardIn 0.75s cubic-bezier(.22,.68,0,1.2) forwards;
        }

        .news-card.animated:nth-child(2) { animation-delay: 0.15s; }
        .news-card.animated:nth-child(3) { animation-delay: 0.30s; }

        .news-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow:
                0 4px 8px rgba(10,18,39,0.08),
                0 16px 40px rgba(10,18,39,0.14),
                0 40px 80px rgba(10,18,39,0.14);
        }

        .news-cover {
            aspect-ratio: 16/9;
            position: relative;
            overflow: hidden;
        }

        .news-cover-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .news-card:hover .news-cover-bg { transform: scale(1.07); }

        .news-body {
            padding: 24px 28px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .news-date {
            font-size: 0.73rem;
            font-weight: 600;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .news-card h3 {
            font-size: 1rem;
            font-weight: 800;
            color: var(--navy-mid);
            line-height: 1.45;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            flex: 1;
        }

        .news-link {
            display: inline-block;
            border: 2px solid #e8392a;
            color: #e8392a;
            border-radius: 999px;
            padding: 9px 26px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            align-self: flex-start;
            transition: background 0.3s ease, color 0.3s ease, transform 0.2s ease;
        }

        .news-link:hover {
            background: #e8392a;
            color: #fff;
            transform: scale(1.05);
        }

        /* ── GALLERY ── */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: 260px 260px;
            gap: 16px;
            margin-top: 60px;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .gallery-item {
            overflow: hidden;
            border-radius: var(--radius-md);
            cursor: pointer;
        }

        .gallery-item:nth-child(1) { grid-column: span 5; grid-row: span 2; }
        .gallery-item:nth-child(2) { grid-column: span 4; }
        .gallery-item:nth-child(3) { grid-column: span 3; }
        .gallery-item:nth-child(4) { grid-column: span 3; }
        .gallery-item:nth-child(5) { grid-column: span 4; }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-slow);
        }

        .gallery-item:hover img { transform: scale(1.08); }

        /* ── CONTACT ── */
        .contact-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: start;
            margin-top: 60px;
        }

        .contact-info-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-item {
            display: flex;
            gap: 18px;
            padding: 22px;
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .contact-item:hover {
            background: var(--white);
            border-color: var(--blue);
            transform: translateX(4px);
            box-shadow: var(--shadow-sm);
        }

        .contact-item-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.1rem;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .contact-item:hover .contact-item-icon {
            background: var(--green);
            transform: rotate(8deg);
        }

        .contact-item-body h4 {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .contact-item-body p {
            font-size: 0.98rem;
            font-weight: 500;
            color: var(--black);
        }

        .contact-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .btn-solid {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 26px;
            background: var(--green);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(4,120,71,0.3);
        }

        .btn-solid:hover {
            background: var(--green-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(4,120,71,0.4);
        }

        .btn-outline-dark {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 26px;
            background: transparent;
            color: var(--black);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-outline-dark:hover {
            border-color: var(--blue);
            color: var(--blue);
            transform: translateY(-2px);
        }

        .contact-map-wrap {
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            background: var(--navy);
            height: 100%;
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .contact-map-wrap iframe {
            width: 100%;
            height: 100%;
            min-height: 420px;
            display: block;
            border: none;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--navy);
            color: var(--white);
            padding: 80px 0 0;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            padding-bottom: 60px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .footer-brand .logo-text { font-size: 1.3rem; }

        .footer-description {
            margin-top: 18px;
            margin-bottom: 28px;
            font-size: 0.92rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.8;
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 1rem;
            transition: var(--transition);
        }

        .social-btn:hover {
            background: var(--green);
            border-color: var(--green);
            color: var(--white);
            transform: translateY(-3px);
        }

        .footer-col h4 {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.35);
            margin-bottom: 20px;
        }

        .footer-col ul { list-style: none; }

        .footer-col li { margin-bottom: 12px; }

        .footer-col a {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.92rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .footer-col a:hover {
            color: var(--white);
            gap: 10px;
        }

        .footer-bottom {
            padding: 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-copyright {
            font-size: 0.84rem;
            color: rgba(255,255,255,0.3);
        }

        .footer-legal {
            display: flex;
            gap: 24px;
        }

        .footer-legal a {
            font-size: 0.84rem;
            color: rgba(255,255,255,0.3);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-legal a:hover { color: rgba(255,255,255,0.6); }

        /* ── ANIMATIONS ── */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* Team card tap on touch devices */
        @media (hover: none) {
            .team-card.touch-open .team-info { transform: translateY(0); }
            .team-card.touch-open .team-name-bar { opacity: 0; }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1100px) {
            .about-layout { gap: 50px; }
            .footer-top { grid-template-columns: 1fr 1fr; gap: 40px; }
        }

        @media (max-width: 992px) {
            .services-grid,
            .team-grid,
            .news-grid { grid-template-columns: repeat(2, 1fr); }
            .about-layout,
            .contact-layout { grid-template-columns: 1fr; gap: 50px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(2) { border-right: none; }
            .stat-item:nth-child(3),
            .stat-item:nth-child(4) { border-top: 1px solid var(--border); }
            .about-badge { bottom: -16px; right: -10px; }
        }

        @media (max-width: 768px) {
            .section { padding: 72px 0; }
            .hero h1 { font-size: 2.2rem; letter-spacing: -1.5px; }
            .hero-tagline { font-size: 1.1rem; }
            .services-grid,
            .team-grid,
            .news-grid { grid-template-columns: 1fr; }

            /* Team cards mobile — image full + name bar visible, tap to reveal info */
            .team-card { aspect-ratio: 3/4; }
            .team-name-bar { opacity: 1; }
            .team-info { transform: translateY(100%); }
            .team-card.touch-open .team-info { transform: translateY(0); }
            .team-card.touch-open .team-name-bar { opacity: 0; }
            .gallery-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: repeat(3, 200px);
            }
            .gallery-item:nth-child(1),
            .gallery-item:nth-child(2),
            .gallery-item:nth-child(3),
            .gallery-item:nth-child(4),
            .gallery-item:nth-child(5) {
                grid-column: span 1;
                grid-row: span 1;
            }
            .gallery-item:nth-child(1) { grid-column: span 2; }

            .footer-top { grid-template-columns: 1fr; gap: 32px; }
            .footer-bottom { flex-direction: column; text-align: center; }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(10,14,39,0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 20px;
                gap: 4px;
                border-top: 1px solid rgba(255,255,255,0.07);
            }

            .nav-links.active { display: flex; }
            .mobile-menu-btn { display: flex; }

            .hero-actions { flex-direction: column; }
            .btn-primary, .btn-ghost { width: 100%; justify-content: center; }
        }

        @media (max-width: 480px) {
            .container { padding: 0 18px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .contact-actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <!-- Scroll Progress -->
    <div id="scroll-progress"></div>

    <!-- Header -->
    <header id="header">
        <div class="header-inner">
            <div class="container">
                <nav class="navbar">
                    <a href="#home" class="logo">
                        <img src="photos/logo.jpeg" alt="WIBC Logo" class="logo-image">
                        <span class="logo-text">WI<span>BC</span></span><span style="color: white">-  World Innovations Business Consulting</span>
                    </a>
                    <ul class="nav-links" id="nav-links">
                        <li><a href="#home">Accueil</a></li>
                        <li><a href="#about">À propos</a></li>
                        <li><a href="#services">Expertises</a></li>
                        <li><a href="#team">Équipe</a></li>
                        <li><a href="#achievements">Réalisations</a></li>
                        <li><a href="/actualites">Actualités</a></li>
                        <li><a href="#contact" class="nav-cta">Contact</a></li>
                    </ul>
                    <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-grid-pattern"></div>
        <div class="hero-glow hero-glow-1"></div>
        <div class="hero-glow hero-glow-2"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-eyebrow">Innovation &amp; Stratégie</div>
                <h1 style="color: #4ade80">WORLD INNOVATIONS<br><span class="highlight">BUSINESS CONSULTING</span></h1>
                <p class="hero-tagline">{{ $hero['tagline'] ?? 'Une vision mondiale, un impact africain' }}</p>
                <p class="hero-description">{{ $hero['description'] ?? 'Conseil – Innovation – Stratégie – Impact. Nous accompagnons entreprises et institutions vers l\'excellence en Afrique et au-delà.' }}</p>
                <div class="hero-actions">
                    <a href="#contact" class="btn-primary">
                        {{ $hero['btnText'] ?? 'Nous contacter' }}
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#about" class="btn-ghost">
                        Découvrir WIBC
                        <i class="fas fa-chevron-down"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-scroll-hint">
            <div class="scroll-mouse"></div>
            <span>Défiler</span>
        </div>
    </section>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="container" style="padding: 0;">
            <div class="stats-grid">
                <div class="stat-item reveal">
                    <div class="stat-value">3</div>
                    <div class="stat-label">Pôles d'expertise</div>
                </div>
                <div class="stat-item reveal reveal-delay-1">
                    <div class="stat-value">4+</div>
                    <div class="stat-label">Réalisations majeures</div>
                </div>
                <div class="stat-item reveal reveal-delay-2">
                    <div class="stat-value">CI</div>
                    <div class="stat-label">Basé en Côte d'Ivoire</div>
                </div>
                <div class="stat-item reveal reveal-delay-3">
                    <div class="stat-value">2025</div>
                    <div class="stat-label">Enregistré RCCM</div>
                </div>
            </div>
        </div>
    </div>

    <!-- About -->
    <section class="section" id="about">
        <div class="container">
            <div class="about-layout">
                <div class="about-visual reveal">
                    <div class="about-image-wrap">
                        <img src="photos/about.jpg" alt="Équipe WIBC">
                    </div>
                    <div class="about-badge">
                        <div class="about-badge-num">100%</div>
                        <div class="about-badge-text">Ivoirien</div>
                    </div>
                </div>
                <div class="about-text reveal reveal-delay-2">
                    <div class="section-label">À propos de WIBC</div>
                    <h3>{{ $about['title'] ?? "Transformer l'Afrique par l'innovation et la stratégie" }}</h3>
                    <p>{{ $about['description1'] ?? 'World Innovations Business Consulting (WIBC) est une société ivoirienne de conseil fondée par trois jeunes entrepreneurs visionnaires.' }}</p>
                    <p>{{ $about['description2'] ?? 'Nous accompagnons entreprises, institutions publiques, organisations et personnalités via trois pôles spécialisés.' }}</p>
                    <div class="pillars">
                        <div class="pillar-item">
                            <div class="pillar-icon green"><i class="fas fa-rocket"></i></div>
                            <span class="pillar-name">WIBC Strategy &amp; Business</span>
                        </div>
                        <div class="pillar-item">
                            <div class="pillar-icon blue"><i class="fas fa-microchip"></i></div>
                            <span class="pillar-name">WIBC Tech &amp; Innovation</span>
                        </div>
                        <div class="pillar-item">
                            <div class="pillar-icon red"><i class="fas fa-bullhorn"></i></div>
                            <span class="pillar-name">WIBC Influence &amp; Support Events</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="section section-alt" id="services">
        <div class="container">
            <div class="section-title centered">
                <div class="section-label" style="justify-content: center;">Nos pôles</div>
                <h2>Nos pôles d'expertise</h2>
                <p>Trois pôles spécialisés conçus pour répondre à vos besoins stratégiques et opérationnels.</p>
            </div>
            <div class="services-grid">
                @forelse($services as $i => $service)
                <div class="service-card">
                    <span class="service-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="service-icon-wrap"><i class="fas fa-chart-line"></i></div>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description }}</p>
                    @if(!empty($service->features) && count($service->features) > 0)
                    <ul class="service-features">
                        @foreach($service->features as $feature)
                        <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    @endif
                    <a href="#contact" class="service-btn">Voir plus <i class="fas fa-arrow-right"></i></a>
                </div>
                @empty
                <div class="service-card">
                    <span class="service-num">01</span>
                    <div class="service-icon-wrap"><i class="fas fa-chart-line"></i></div>
                    <h3>Strategy &amp; Business</h3>
                    <p>Accompagner les structures dans leurs choix stratégiques et leur développement sur le continent.</p>
                    <a href="#contact" class="service-btn">Voir plus <i class="fas fa-arrow-right"></i></a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="section" id="team">
        <div class="container">
            <div class="section-title centered">
                <div class="section-label" style="justify-content: center;">L'équipe</div>
                <h2>Nos Co-fondateurs</h2>
                <p>L'équipe de direction qui pilote WIBC vers l'excellence et l'innovation.</p>
            </div>
            <div class="team-grid">
                @forelse($team as $member)
                <div class="team-card">
                    <div class="team-photo">
                        @if($member->photo)
                        <img data-member-photo="{{ $member->id }}" src="" alt="{{ $member->name }}">
                        @else
                        <img src="photos/miguih.jpeg" alt="{{ $member->name }}">
                        @endif
                    </div>
                    <div class="team-name-bar">
                        <h3>{{ $member->name }}</h3>
                        <div class="team-position">{{ $member->position }}</div>
                    </div>
                    <div class="team-info">
                        <h3>{{ $member->name }}</h3>
                        <div class="team-position">{{ $member->position }}</div>
                        @if($member->bio)
                        <p class="team-bio">{{ $member->bio }}</p>
                        @endif
                        <div class="team-action-row">
                            <div class="team-socials">
                                <a href="#" class="soc-linkedin" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="soc-instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="soc-facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="soc-twitter" title="X / Twitter"><i class="fab fa-twitter"></i></a>
                            </div>
                            <a href="#contact" class="team-btn">En savoir plus</a>
                        </div>
                    </div>
                </div>
                @empty
                <p style="text-align:center;color:var(--text-muted);">Aucun membre pour le moment.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Achievements -->
    <section id="achievements">
        <div class="container">
            <div class="section-title centered">
                <div class="section-label" style="justify-content: center;">Impact</div>
                <h2>Nos Réalisations</h2>
                <p>Quelques-uns de nos projets marquants qui témoignent de notre expertise.</p>
            </div>

            @php
                $achColors = ['ach-strip--green', 'ach-strip--blue', 'ach-strip--red', 'ach-strip--gold'];
            @endphp
            <div class="ach-list">
                @forelse($achievements as $i => $ach)
                <div class="ach-strip {{ $achColors[$i % count($achColors)] }} {{ $i % 2 !== 0 ? 'ach-reverse' : '' }}">
                    <div class="ach-img">
                        @if($ach->photo)
                        <img data-ach-pub-id="{{ $ach->id }}" src="" alt="{{ $ach->title }}">
                        @else
                        <img src="photos/{{ ($i % 4) + 1 }}.jpeg" alt="{{ $ach->title }}">
                        @endif
                    </div>
                    <div class="ach-text" data-num="{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}">
                        @if($ach->category)
                        <div class="ach-tag"><i class="fas fa-tag"></i> {{ $ach->category }}</div>
                        @endif
                        <h3>{{ $ach->title }}</h3>
                        @if($ach->description)
                        <p>{{ $ach->description }}</p>
                        @endif
                        <a href="#contact" class="ach-btn">Voir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                @empty
                <p style="text-align:center;color:var(--text-muted);padding:40px 0;">Aucune réalisation pour le moment.</p>
                @endforelse
            </div>

            <div class="ach-dots" id="achDots"></div>

        </div>
    </section>

    <!-- Actualités -->
    <section class="section" id="actualites">
        <div class="container">
            <div class="section-title centered">
                <div class="section-label" style="justify-content: center;">News</div>
                <h2>Actualités</h2>
                <p>Les dernières nouvelles et événements de WIBC et de ses projets.</p>
            </div>

            @php $newsVisible = $news->take(3); $newsExtra = $news->skip(3); @endphp

            <div class="news-grid">
                @forelse($newsVisible as $i => $article)
                <div class="news-card">
                    <div class="news-cover">
                        @if($article->photo)
                        <img src="/actualites/{{ $article->id }}/photo" alt="{{ $article->title }}" class="news-cover-bg">
                        @else
                        <img src="photos/{{ ($i % 4) + 1 }}.jpeg" alt="{{ $article->title }}" class="news-cover-bg">
                        @endif
                    </div>
                    <div class="news-body">
                        @if($article->date)
                        <span class="news-date"><i class="fas fa-calendar-alt"></i> {{ fmtDate($article->date) }}</span>
                        @endif
                        <h3>{{ $article->title }}</h3>
                        <a href="/actualites/{{ $article->id }}" class="news-link">Lire la suite</a>
                    </div>
                </div>
                @empty
                <p style="text-align:center;color:var(--text-muted);grid-column:1/-1;padding:40px 0;">Aucune actualité pour le moment.</p>
                @endforelse
            </div>

            @if($newsExtra->count() > 0)
            <div style="text-align:center;margin-top:40px;">
                <a href="/actualites" style="display:inline-flex;align-items:center;gap:10px;padding:14px 32px;border-radius:14px;border:2px solid var(--green);background:transparent;color:var(--green);font-weight:700;font-size:0.92rem;text-decoration:none;transition:all 0.25s;" onmouseover="this.style.background='rgba(4,120,71,0.08)'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-newspaper"></i>
                    Voir toutes les actualités
                    <span style="background:var(--green);color:#fff;border-radius:20px;padding:2px 10px;font-size:0.8rem;">{{ $news->count() }}</span>
                </a>
            </div>
            @endif

        </div>
    </section>

    <!-- Gallery -->
    <section class="section section-alt" id="gallery">
        <div class="container">
            <div class="section-title centered">
                <div class="section-label" style="justify-content: center;">Galerie</div>
                <h2>Galerie Photos</h2>
                <p>Nos moments marquants et événements à travers ces images.</p>
            </div>

            @php $galleryVisible = $gallery->take(5); $galleryExtra = $gallery->skip(5); @endphp

            <div class="gallery-grid reveal" id="publicGallery">
                @forelse($galleryVisible as $i => $item)
                <div class="gallery-item" data-gallery-id="{{ $item->id }}">
                    <img src="" alt="Galerie {{ $i + 1 }}">
                </div>
                @empty
                <div class="gallery-item"><img src="photos/1.jpeg" alt="Galerie 1"></div>
                <div class="gallery-item"><img src="photos/2.jpeg" alt="Galerie 2"></div>
                <div class="gallery-item"><img src="photos/3.jpeg" alt="Galerie 3"></div>
                <div class="gallery-item"><img src="photos/4.jpeg" alt="Galerie 4"></div>
                <div class="gallery-item"><img src="photos/5.jpeg" alt="Galerie 5"></div>
                @endforelse
            </div>

            @if($galleryExtra->count() > 0)
            <div style="text-align:center;margin-top:36px;">
                <button id="galleryMoreBtn" onclick="openGalleryModal()" style="display:inline-flex;align-items:center;gap:10px;padding:14px 32px;border-radius:14px;border:2px solid var(--green);background:transparent;color:var(--green);font-weight:700;font-size:0.92rem;cursor:pointer;transition:all 0.25s;font-family:inherit;">
                    <i class="fas fa-images"></i>
                    Voir plus <span style="background:var(--green);color:#fff;border-radius:20px;padding:2px 10px;font-size:0.8rem;">+{{ $galleryExtra->count() }}</span>
                </button>
            </div>

            <!-- Lightbox galerie complète -->
            <div id="galleryModal" style="display:none;position:fixed;inset:0;background:rgba(7,16,42,0.92);z-index:9999;overflow-y:auto;padding:40px 20px;" onclick="if(event.target===this)closeGalleryModal()">
                <div style="max-width:1100px;margin:0 auto;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
                        <h3 style="color:#fff;font-size:1.2rem;font-weight:700;"><i class="fas fa-images" style="color:#4ade80;margin-right:10px;"></i> Toute la galerie</h3>
                        <button onclick="closeGalleryModal()" style="width:40px;height:40px;border-radius:50%;background:#e8392a;border:none;color:#fff;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;">
                        @foreach($gallery as $j => $item)
                        <div style="aspect-ratio:1;border-radius:14px;overflow:hidden;cursor:pointer;" onclick="openLightboxItem({{ $j }})">
                            <img data-gallery-modal-id="{{ $item->id }}" src="" alt="Photo {{ $j + 1 }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>

    <!-- Contact -->
    <section class="section" id="contact">
        <div class="container">
            <div class="section-title">
                <div class="section-label">Contact</div>
                <h2>Parlons de votre projet</h2>
                <p>N'hésitez pas à nous contacter pour découvrir comment WIBC peut vous accompagner vers le succès.</p>
            </div>
            <div class="contact-layout">
                <div class="reveal">
                    <div class="contact-info-list">
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="contact-item-body">
                                <h4>Adresse</h4>
                                <p>{{ $contact['address'] ?? 'Angré, Cocody, Abidjan, Côte d\'Ivoire' }}</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                            <div class="contact-item-body">
                                <h4>Email</h4>
                                <p>{{ $contact['email'] ?? 'worldibconsulting@gmail.com' }}</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
                            <div class="contact-item-body">
                                <h4>Téléphone</h4>
                                <p>{{ $contact['phone'] ?? '+225 07 12 70 01 67' }}</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-file-contract"></i></div>
                            <div class="contact-item-body">
                                <h4>RCCM</h4>
                                <p>{{ $contact['rccm'] ?? 'CI-ABJ-03-2025-B12-04372' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="contact-actions">
                        <a href="mailto:{{ $contact['email'] ?? 'worldibconsulting@gmail.com' }}" class="btn-solid">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer un email
                        </a>
                        <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone'] ?? '+2250712700167') }}" class="btn-outline-dark">
                            <i class="fas fa-phone"></i>
                            Appeler maintenant
                        </a>
                    </div>
                </div>
                <div class="contact-map-wrap reveal reveal-delay-2">
                    @php
                        $mapSrc = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.0!2d-3.9693!3d5.3936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1ea5311959121%3A0x3dfa1b2b8ba54dc2!2sAngr%C3%A9%2C%20Abidjan%2C%20C%C3%B4te%20d%27Ivoire!5e0!3m2!1sfr!2sfr!4v1680000000000!5m2!1sfr!2sfr';
                        if (!empty($contact['map_lat']) && !empty($contact['map_lng'])) {
                            $mapSrc = 'https://maps.google.com/maps?q=' . $contact['map_lat'] . ',' . $contact['map_lng'] . '&z=16&output=embed';
                        } elseif (!empty($contact['map_embed'])) {
                            $mapSrc = $contact['map_embed'];
                        }
                    @endphp
                    <iframe
                        src="{{ $mapSrc }}"
                        width="100%"
                        height="100%"
                        style="border:0; min-height: 420px; display: block; border-radius: 24px;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="WIBC - Localisation">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-top">
                <div class="footer-brand">
                    <a href="#home" class="logo">
                        <img src="photos/logo.jpeg" alt="WIBC Logo" class="logo-image">
                        <span class="logo-text">WI<span>BC</span></span>
                    </a>
                    <p class="footer-description">World Innovations Business Consulting est une société ivoirienne de conseil fondée par trois jeunes entrepreneurs visionnaires. Une vision mondiale, un impact africain.</p>
                    <div class="footer-social">
                        @if(!empty($social['facebook']))
                        <a href="{{ $social['facebook'] }}" target="_blank" rel="noopener" class="social-btn" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($social['instagram']))
                        <a href="{{ $social['instagram'] }}" target="_blank" rel="noopener" class="social-btn" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($social['twitter']))
                        <a href="{{ $social['twitter'] }}" target="_blank" rel="noopener" class="social-btn" aria-label="Twitter / X"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if(!empty($social['linkedin']))
                        <a href="{{ $social['linkedin'] }}" target="_blank" rel="noopener" class="social-btn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if(!empty($social['youtube']))
                        <a href="{{ $social['youtube'] }}" target="_blank" rel="noopener" class="social-btn" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if(!empty($social['tiktok']))
                        <a href="{{ $social['tiktok'] }}" target="_blank" rel="noopener" class="social-btn" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        @endif
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="#home"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Accueil</a></li>
                        <li><a href="#about"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> À propos</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Expertises</a></li>
                        <li><a href="#team"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Équipe</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Strategy &amp; Business</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Tech &amp; Innovation</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Influence &amp; Events</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="mailto:worldibconsulting@gmail.com"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Email</a></li>
                        <li><a href="tel:+2250712700167"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Téléphone</a></li>
                        <li><a href="#contact"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i> Localisation</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; 2025 World Innovations Business Consulting (WIBC). Tous droits réservés.</p>
                <div class="footer-legal">
                    <a href="#">Mentions légales</a>
                    <a href="#">Confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Scroll progress bar
        window.addEventListener('scroll', () => {
            const docH = document.documentElement.scrollHeight - window.innerHeight;
            const pct = (window.scrollY / docH) * 100;
            document.getElementById('scroll-progress').style.width = pct + '%';
        });

        // Header scroll effect
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            header.classList.toggle('header-scrolled', window.scrollY > 60);
        });

        // Mobile menu
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const navLinks = document.getElementById('nav-links');
        mobileBtn.addEventListener('click', () => {
            const open = navLinks.classList.toggle('active');
            mobileBtn.innerHTML = open
                ? '<i class="fas fa-times"></i>'
                : '<i class="fas fa-bars"></i>';
        });

        // Smooth scroll + close menu
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', e => {
                e.preventDefault();
                const id = anchor.getAttribute('href');
                if (id === '#') return;
                const el = document.querySelector(id);
                if (el) {
                    navLinks.classList.remove('active');
                    mobileBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    window.scrollTo({ top: el.offsetTop - 80, behavior: 'smooth' });
                }
            });
        });

        // Reveal on scroll
        const revealEls = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revealObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        revealEls.forEach(el => revealObserver.observe(el));

        // Service cards animation
        const svcCards = document.querySelectorAll('.service-card');
        const svcObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('animated');
                    svcObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.15 });
        svcCards.forEach(c => svcObserver.observe(c));

        // News cards animation
        const newsCards = document.querySelectorAll('.news-card');
        const newsObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('animated');
                    newsObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
        newsCards.forEach(card => newsObserver.observe(card));

        // Team cards animation
        const teamCards = document.querySelectorAll('.team-card');
        const teamObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('animated');
                    teamObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

        teamCards.forEach(card => teamObserver.observe(card));

        // Team cards — tap to reveal on touch devices (tablets 769-992px)
        if (window.matchMedia('(hover: none)').matches) {
            teamCards.forEach(card => {
                card.addEventListener('click', () => {
                    const isOpen = card.classList.contains('touch-open');
                    teamCards.forEach(c => c.classList.remove('touch-open'));
                    if (!isOpen) card.classList.add('touch-open');
                });
            });
        }

        // ── RÉALISATIONS deck stack ──────────────────────────────────
        (function () {
            var strips   = Array.from(document.querySelectorAll('.ach-strip'));
            var dotsWrap = document.getElementById('achDots');
            if (!strips.length || !dotsWrap) return;

            var N     = strips.length;
            var front = 0;
            var locked = false;

            var POS = [
                'translateY(0px) scale(1)',
                'translateY(10px) scale(0.97)',
                'translateY(18px) scale(0.94)',
                'translateY(24px) scale(0.91)'
            ];
            var OPA = [1, 0.75, 0.50, 0.25];
            var ZID = [4, 3, 2, 1];
            var TRANS = 'transform 0.55s cubic-bezier(.22,.68,0,1.1), opacity 0.55s ease';

            function applyPos() {
                strips.forEach(function(s, i) {
                    var p = ((i - front) % N + N) % N;
                    s.style.transition = TRANS;
                    s.style.transform  = POS[p];
                    s.style.opacity    = OPA[p];
                    s.style.zIndex     = ZID[p];
                });
                dotsWrap.querySelectorAll('.ach-dot').forEach(function(d, i) {
                    d.classList.toggle('active', i === front);
                });
            }

            function next() {
                if (locked) return;
                locked = true;

                var leaving = strips[front];
                /* front card slides up and out */
                leaving.style.transition = 'transform 0.4s ease, opacity 0.35s ease';
                leaving.style.transform  = 'translateY(-70px) scale(0.85)';
                leaving.style.opacity    = '0';
                leaving.style.zIndex     = '0';

                setTimeout(function() {
                    front = (front + 1) % N;
                    applyPos();
                    locked = false;
                }, 430);
            }

            /* dots */
            strips.forEach(function(_, i) {
                var d = document.createElement('button');
                d.className = 'ach-dot' + (i === 0 ? ' active' : '');
                d.addEventListener('click', function() {
                    if (locked || i === front) return;
                    front = i;
                    applyPos();
                });
                dotsWrap.appendChild(d);
            });

            /* container height = front card + bottom peek gap */
            var list = document.querySelector('.ach-list');
            function syncHeight() {
                var h = strips[0].offsetHeight;
                if (h > 0) list.style.height = (h + 40) + 'px';
            }
            window.addEventListener('load', syncHeight);
            window.addEventListener('resize', syncHeight);
            setTimeout(syncHeight, 100);
            setTimeout(syncHeight, 600);

            applyPos();

            setInterval(next, 4000);
        })();
    </script>


    @if($achievements->filter(fn($a) => !empty($a->photo))->count())
    <script>
        // Assign base64 achievement photos after DOM
        (function() {
            var photos = {
                @foreach($achievements as $ach)
                @if($ach->photo)
                {{ $ach->id }}: @json($ach->photo),
                @endif
                @endforeach
            };
            document.querySelectorAll('[data-ach-pub-id]').forEach(function(img) {
                var id = parseInt(img.getAttribute('data-ach-pub-id'));
                if (photos[id]) img.src = photos[id];
            });
        })();
    </script>
    @endif

    @if($team->filter(fn($m) => !empty($m->photo))->count())
    <script>
        // Assign base64 team photos after DOM (avoids innerHTML truncation of long data URIs)
        (function() {
            var photos = {
                @foreach($team as $member)
                @if($member->photo)
                {{ $member->id }}: @json($member->photo),
                @endif
                @endforeach
            };
            document.querySelectorAll('[data-member-photo]').forEach(function(img) {
                var id = parseInt(img.getAttribute('data-member-photo'));
                if (photos[id]) img.src = photos[id];
            });
        })();
    </script>
    @endif

    @if($gallery->count())
    <script>
        // Map id => url pour toutes les photos
        var galleryPhotos = {
            @foreach($gallery as $item)
            {{ $item->id }}: @json($item->url),
            @endforeach
        };
        var galleryUrls = @json($gallery->values()->pluck('url'));

        // Assigner src grille principale (5 premières)
        document.querySelectorAll('[data-gallery-id]').forEach(function(el) {
            var id  = parseInt(el.getAttribute('data-gallery-id'));
            var img = el.querySelector('img');
            if (img && galleryPhotos[id]) img.src = galleryPhotos[id];
        });

        // Assigner src grille modale (toutes)
        document.querySelectorAll('[data-gallery-modal-id]').forEach(function(img) {
            var id = parseInt(img.getAttribute('data-gallery-modal-id'));
            if (galleryPhotos[id]) img.src = galleryPhotos[id];
        });

        function openGalleryModal() {
            var modal = document.getElementById('galleryModal');
            if (!modal) return;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeGalleryModal() {
            var modal = document.getElementById('galleryModal');
            if (!modal) return;
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function openLightboxItem(idx) {
            // simple lightbox avec navigation
            var url = galleryUrls[idx];
            if (!url) return;
            var lb = document.createElement('div');
            lb.id = 'lb-overlay';
            lb.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:10000;display:flex;align-items:center;justify-content:center;';
            lb.innerHTML = `
                <button onclick="document.getElementById('lb-overlay').remove();document.body.style.overflow=''" style="position:absolute;top:20px;right:20px;width:44px;height:44px;border-radius:50%;background:#e8392a;border:none;color:#fff;font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:1;"><i class="fas fa-times"></i></button>
                <button onclick="lbNav(-1)" style="position:absolute;left:20px;width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="fas fa-chevron-left"></i></button>
                <img id="lb-img" src="${url}" style="max-width:90vw;max-height:85vh;border-radius:16px;object-fit:contain;box-shadow:0 30px 80px rgba(0,0,0,0.6);">
                <button onclick="lbNav(1)" style="position:absolute;right:20px;width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="fas fa-chevron-right"></i></button>
                <div style="position:absolute;bottom:20px;color:rgba(255,255,255,0.5);font-size:0.85rem;">${idx+1} / ${galleryUrls.length}</div>`;
            lb.lbIdx = idx;
            lb.onclick = function(e){ if(e.target===lb){ lb.remove(); document.body.style.overflow=''; } };
            document.body.appendChild(lb);
        }

        function lbNav(dir) {
            var lb = document.getElementById('lb-overlay');
            if (!lb) return;
            var next = ((lb.lbIdx + dir) + galleryUrls.length) % galleryUrls.length;
            lb.lbIdx = next;
            document.getElementById('lb-img').src = galleryUrls[next];
            lb.querySelector('div[style*="bottom"]').textContent = (next+1) + ' / ' + galleryUrls.length;
        }

        document.addEventListener('keydown', function(e) {
            if (document.getElementById('lb-overlay')) {
                if (e.key === 'ArrowLeft')  lbNav(-1);
                if (e.key === 'ArrowRight') lbNav(1);
                if (e.key === 'Escape')     { document.getElementById('lb-overlay').remove(); document.body.style.overflow=''; }
            } else if (document.getElementById('galleryModal')?.style.display === 'block') {
                if (e.key === 'Escape') closeGalleryModal();
            }
        });
    </script>
    @endif
</body>
</html>
