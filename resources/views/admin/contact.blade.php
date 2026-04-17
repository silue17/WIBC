@extends('admin.layout')
@section('title', 'Contact')
@section('subtitle', 'Coordonnées affichées sur le site')

@section('content')

<div class="preview-panel">
    <div class="preview-panel-inner">
        <div class="live-badge"><span class="live-badge-dot"></span> Aperçu en direct</div>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-top:4px;">
            @foreach([
                ['icon'=>'fa-map-marker-alt','id'=>'previewAddr', 'label'=>'Adresse'],
                ['icon'=>'fa-envelope',      'id'=>'previewEmail','label'=>'Email'],
                ['icon'=>'fa-phone',         'id'=>'previewPhone','label'=>'Téléphone'],
                ['icon'=>'fa-building',      'id'=>'previewRccm', 'label'=>'RCCM'],
            ] as $f)
            <div style="display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:14px;padding:13px 15px;">
                <div style="width:36px;height:36px;background:rgba(4,120,71,0.22);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#4ade80;flex-shrink:0;font-size:0.9rem;">
                    <i class="fas {{ $f['icon'] }}"></i>
                </div>
                <div>
                    <div style="font-size:0.65rem;color:rgba(255,255,255,0.38);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:2px;">{{ $f['label'] }}</div>
                    <div id="{{ $f['id'] }}" style="color:#fff;font-size:0.82rem;font-weight:500;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Coordonnées -->
<div class="editor-card">
    <div class="editor-card-header">
        <div class="editor-card-title">
            <div class="editor-card-icon"><i class="fas fa-address-card"></i></div>
            <div>
                <div class="editor-card-label">Coordonnées</div>
                <div class="editor-card-sub">Informations de contact affichées sur le site</div>
            </div>
        </div>
        <button id="saveContactBtn" class="btn-primary"><i class="fas fa-save"></i> Publier</button>
    </div>
    <div class="editor-card-body">
        <div class="form-grid">
            <div class="field-group">
                <label class="field-label"><i class="fas fa-map-marker-alt"></i> Adresse</label>
                <input id="contactAddress" class="field-input" type="text"
                       oninput="document.getElementById('previewAddr').textContent=this.value"
                       placeholder="Ex: Angré, Cocody, Abidjan">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-envelope"></i> Email</label>
                <input id="contactEmail" class="field-input" type="email"
                       oninput="document.getElementById('previewEmail').textContent=this.value"
                       placeholder="Ex: contact@wibc.ci">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-phone"></i> Téléphone</label>
                <input id="contactPhone" class="field-input" type="text"
                       oninput="document.getElementById('previewPhone').textContent=this.value"
                       placeholder="Ex: +225 07 12 70 01 67">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-building"></i> RCCM</label>
                <input id="contactRccm" class="field-input" type="text"
                       oninput="document.getElementById('previewRccm').textContent=this.value"
                       placeholder="Ex: CI-ABJ-03-2025-B12-04372">
            </div>
        </div>
    </div>
</div>

<!-- Carte / Localisation -->
<div class="editor-card" style="margin-top:20px;">
    <div class="editor-card-header">
        <div class="editor-card-title">
            <div class="editor-card-icon" style="background:rgba(5,100,183,0.12);color:#0564B7;">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div>
                <div class="editor-card-label">Localisation sur la carte</div>
                <div class="editor-card-sub">Position affichée sur la page de contact</div>
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <button id="geoBtn" class="btn-primary" style="background:linear-gradient(135deg,#0564B7,#047847);">
                <i class="fas fa-crosshairs"></i> Position actuelle
            </button>
            <button id="saveMapBtn" class="btn-primary">
                <i class="fas fa-save"></i> Sauvegarder
            </button>
        </div>
    </div>
    <div class="editor-card-body" style="padding:0;overflow:hidden;border-radius:0 0 16px 16px;">

        <!-- Barre d'état GPS -->
        <div id="geoStatus" style="display:none;padding:12px 20px;font-size:0.82rem;font-weight:600;align-items:center;gap:10px;border-bottom:1px solid var(--border-light);">
            <i id="geoStatusIcon" class="fas fa-spinner fa-spin"></i>
            <span id="geoStatusText">Localisation en cours...</span>
        </div>

        <!-- Coordonnées affichées -->
        <div id="coordsBar" style="display:none;padding:10px 20px;background:var(--accent-soft);border-bottom:1px solid var(--border-light);display:none;align-items:center;gap:16px;flex-wrap:wrap;">
            <span style="font-size:0.78rem;color:var(--accent);font-weight:700;"><i class="fas fa-map-pin"></i> Latitude : <strong id="dispLat">—</strong></span>
            <span style="font-size:0.78rem;color:var(--accent);font-weight:700;"><i class="fas fa-map-pin"></i> Longitude : <strong id="dispLng">—</strong></span>
            <span style="font-size:0.78rem;color:var(--text-muted);" id="dispAccuracy"></span>
        </div>

        <!-- Iframe carte -->
        <div style="position:relative;width:100%;height:420px;background:var(--bg-app);">
            <iframe id="mapIframe"
                src=""
                width="100%" height="100%"
                style="border:0;display:block;"
                allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Carte WIBC">
            </iframe>
            <div id="mapPlaceholder" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;color:var(--text-muted);background:var(--bg-app);">
                <div style="width:64px;height:64px;border-radius:18px;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:var(--accent);">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <p style="font-size:0.85rem;font-weight:600;">Aucune position enregistrée</p>
                <p style="font-size:0.78rem;">Cliquez sur <strong style="color:var(--accent);">Position actuelle</strong> pour détecter votre emplacement</p>
            </div>
        </div>

        <!-- Champ manuel -->
        <div style="padding:16px 20px;border-top:1px solid var(--border-light);">
            <label class="field-label" style="margin-bottom:8px;display:block;"><i class="fas fa-link"></i> Ou coller un lien Google Maps embed</label>
            <input id="mapEmbedUrl" class="field-input" type="url"
                   placeholder="https://www.google.com/maps/embed?pb=..."
                   style="font-size:0.78rem;">
            <p style="font-size:0.72rem;color:var(--text-muted);margin-top:6px;">
                <i class="fas fa-info-circle"></i>
                Sur Google Maps → Partager → Intégrer une carte → copier l'URL de l'attribut <code>src</code>
            </p>
        </div>
    </div>
</div>

<input type="hidden" id="mapLat" value="">
<input type="hidden" id="mapLng" value="">

<!-- Réseaux sociaux -->
<div class="editor-card" style="margin-top:20px;">
    <div class="editor-card-header">
        <div class="editor-card-title">
            <div class="editor-card-icon" style="background:rgba(243,66,68,0.10);color:#F34244;">
                <i class="fas fa-share-alt"></i>
            </div>
            <div>
                <div class="editor-card-label">Réseaux sociaux</div>
                <div class="editor-card-sub">Liens affichés dans le footer du site</div>
            </div>
        </div>
        <button id="saveSocialBtn" class="btn-primary"><i class="fas fa-save"></i> Publier</button>
    </div>
    <div class="editor-card-body">
        <div class="form-grid">
            <div class="field-group">
                <label class="field-label"><i class="fab fa-facebook-f" style="color:#1877f2;"></i> Facebook</label>
                <input id="socialFacebook" class="field-input" type="url" placeholder="https://facebook.com/wibc">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fab fa-instagram" style="color:#e1306c;"></i> Instagram</label>
                <input id="socialInstagram" class="field-input" type="url" placeholder="https://instagram.com/wibc">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fab fa-twitter" style="color:#1a8cd8;"></i> Twitter / X</label>
                <input id="socialTwitter" class="field-input" type="url" placeholder="https://twitter.com/wibc">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fab fa-linkedin-in" style="color:#0077b5;"></i> LinkedIn</label>
                <input id="socialLinkedin" class="field-input" type="url" placeholder="https://linkedin.com/company/wibc">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube</label>
                <input id="socialYoutube" class="field-input" type="url" placeholder="https://youtube.com/@wibc">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fab fa-tiktok" style="color:#000;"></i> TikTok</label>
                <input id="socialTiktok" class="field-input" type="url" placeholder="https://tiktok.com/@wibc">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ── Chargement des données ──────────────────────────────────────────────
    api('GET', '/admin/api/contact').then(data => {
        document.getElementById('contactAddress').value = data.address ?? '';
        document.getElementById('contactEmail').value   = data.email   ?? '';
        document.getElementById('contactPhone').value   = data.phone   ?? '';
        document.getElementById('contactRccm').value    = data.rccm    ?? '';

        // Réseaux sociaux
        document.getElementById('socialFacebook').value  = data.social_facebook  ?? '';
        document.getElementById('socialInstagram').value = data.social_instagram ?? '';
        document.getElementById('socialTwitter').value   = data.social_twitter   ?? '';
        document.getElementById('socialLinkedin').value  = data.social_linkedin  ?? '';
        document.getElementById('socialYoutube').value   = data.social_youtube   ?? '';
        document.getElementById('socialTiktok').value    = data.social_tiktok    ?? '';

        document.getElementById('previewAddr').textContent  = data.address ?? '';
        document.getElementById('previewEmail').textContent = data.email   ?? '';
        document.getElementById('previewPhone').textContent = data.phone   ?? '';
        document.getElementById('previewRccm').textContent  = data.rccm   ?? '';

        // Charger la carte sauvegardée
        if (data.map_lat && data.map_lng) {
            document.getElementById('mapLat').value = data.map_lat;
            document.getElementById('mapLng').value = data.map_lng;
            showMap(data.map_lat, data.map_lng);
            document.getElementById('dispLat').textContent = parseFloat(data.map_lat).toFixed(6);
            document.getElementById('dispLng').textContent = parseFloat(data.map_lng).toFixed(6);
            document.getElementById('coordsBar').style.display = 'flex';
        } else if (data.map_embed) {
            document.getElementById('mapEmbedUrl').value = data.map_embed;
            loadEmbedUrl(data.map_embed);
        }
    });

    // ── Afficher la carte avec lat/lng ─────────────────────────────────────
    function showMap(lat, lng) {
        const url = `https://maps.google.com/maps?q=${lat},${lng}&z=16&output=embed`;
        const iframe = document.getElementById('mapIframe');
        iframe.src = url;
        document.getElementById('mapPlaceholder').style.display = 'none';
    }

    // ── Charger un embed URL personnalisé ──────────────────────────────────
    function loadEmbedUrl(url) {
        if (!url.trim()) return;
        document.getElementById('mapIframe').src = url.trim();
        document.getElementById('mapPlaceholder').style.display = 'none';
    }

    document.getElementById('mapEmbedUrl').addEventListener('input', function() {
        if (this.value.trim()) loadEmbedUrl(this.value);
    });

    // ── Bouton Position actuelle ────────────────────────────────────────────
    document.getElementById('geoBtn').onclick = function() {
        if (!navigator.geolocation) {
            showToast('Géolocalisation non supportée par ce navigateur', 'error');
            return;
        }

        const btn       = this;
        const status    = document.getElementById('geoStatus');
        const statusIcon = document.getElementById('geoStatusIcon');
        const statusText = document.getElementById('geoStatusText');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Localisation...';
        status.style.display = 'flex';
        status.style.background = 'var(--accent-soft)';
        status.style.color = 'var(--accent)';
        statusIcon.className = 'fas fa-spinner fa-spin';
        statusText.textContent = 'Recherche de votre position GPS...';

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const acc = Math.round(pos.coords.accuracy);

                // Mettre à jour les champs cachés
                document.getElementById('mapLat').value = lat;
                document.getElementById('mapLng').value = lng;
                document.getElementById('mapEmbedUrl').value = '';

                // Afficher les coordonnées
                document.getElementById('dispLat').textContent = lat.toFixed(6);
                document.getElementById('dispLng').textContent = lng.toFixed(6);
                document.getElementById('dispAccuracy').textContent = `Précision : ±${acc} m`;
                document.getElementById('coordsBar').style.display = 'flex';

                // Afficher la carte
                showMap(lat, lng);

                // Statut succès
                status.style.background = 'rgba(4,120,71,0.08)';
                status.style.color = 'var(--accent)';
                statusIcon.className = 'fas fa-check-circle';
                statusText.textContent = `Position trouvée — précision ±${acc} m`;

                // Géocodage inverse pour l'adresse (facultatif)
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                    .then(r => r.json())
                    .then(d => {
                        if (d.display_name) {
                            const addr = document.getElementById('contactAddress');
                            if (!addr.value) {
                                addr.value = d.display_name;
                                document.getElementById('previewAddr').textContent = d.display_name;
                            }
                        }
                    }).catch(() => {});

                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Position détectée';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-crosshairs"></i> Position actuelle';
                }, 3000);

                showToast('Position GPS détectée avec succès');
            },
            function(err) {
                const msgs = {
                    1: 'Permission refusée — autorisez la localisation dans votre navigateur.',
                    2: 'Position introuvable — vérifiez votre connexion.',
                    3: 'Délai dépassé — réessayez.',
                };
                status.style.background = 'rgba(232,57,42,0.08)';
                status.style.color = 'var(--red)';
                statusIcon.className = 'fas fa-exclamation-triangle';
                statusText.textContent = msgs[err.code] || 'Erreur de géolocalisation.';

                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> Position actuelle';
                showToast(msgs[err.code] || 'Erreur GPS', 'error');
            },
            { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
        );
    };

    // ── Sauvegarde ─────────────────────────────────────────────────────────
    document.getElementById('saveContactBtn').onclick = async () => {
        const btn = document.getElementById('saveContactBtn');
        btn.disabled = true;
        try {
            await api('PUT', '/admin/api/contact', {
                address:   document.getElementById('contactAddress').value,
                email:     document.getElementById('contactEmail').value,
                phone:     document.getElementById('contactPhone').value,
                rccm:      document.getElementById('contactRccm').value,
                map_lat:   document.getElementById('mapLat').value,
                map_lng:   document.getElementById('mapLng').value,
                map_embed: document.getElementById('mapEmbedUrl').value,
            });
            showToast('Contact mis à jour — visible sur le site');
        } catch(e) {
            showToast('Erreur : ' + e.message, 'error');
        } finally {
            btn.disabled = false;
        }
    };

    document.getElementById('saveMapBtn').onclick = () => {
        document.getElementById('saveContactBtn').click();
    };

    // ── Sauvegarde réseaux sociaux ─────────────────────────────────────────
    document.getElementById('saveSocialBtn').onclick = async () => {
        const btn = document.getElementById('saveSocialBtn');
        btn.disabled = true;
        try {
            await api('PUT', '/admin/api/contact/social', {
                social_facebook:  document.getElementById('socialFacebook').value.trim(),
                social_instagram: document.getElementById('socialInstagram').value.trim(),
                social_twitter:   document.getElementById('socialTwitter').value.trim(),
                social_linkedin:  document.getElementById('socialLinkedin').value.trim(),
                social_youtube:   document.getElementById('socialYoutube').value.trim(),
                social_tiktok:    document.getElementById('socialTiktok').value.trim(),
            });
            showToast('Réseaux sociaux mis à jour');
        } catch(e) {
            showToast('Erreur : ' + e.message, 'error');
        } finally {
            btn.disabled = false;
        }
    };
</script>
@endsection
