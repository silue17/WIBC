@extends('admin.layout')
@section('title', 'Hero')
@section('subtitle', 'Contenu principal de la page d\'accueil')

@section('content')

<div class="preview-panel">
    <div class="preview-panel-inner">
        <div class="live-badge"><span class="live-badge-dot"></span> Aperçu en direct</div>
        <div id="previewTitle" style="font-size:clamp(1.2rem,2.5vw,1.9rem);font-weight:900;color:#fff;margin-bottom:8px;letter-spacing:-0.03em;line-height:1.2;"></div>
        <div id="previewTagline" style="font-size:0.95rem;font-weight:600;color:rgba(255,255,255,0.7);margin-bottom:8px;"></div>
        <div id="previewDesc" style="font-size:0.8rem;color:rgba(255,255,255,0.42);max-width:460px;margin-bottom:20px;line-height:1.65;"></div>
        <span id="previewBtn" style="display:inline-flex;align-items:center;gap:8px;background:#e8392a;color:#fff;padding:10px 24px;border-radius:999px;font-size:0.82rem;font-weight:700;box-shadow:0 6px 20px rgba(232,57,42,0.35);"></span>
    </div>
</div>

<div class="editor-card">
    <div class="editor-card-header">
        <div class="editor-card-title">
            <div class="editor-card-icon"><i class="fas fa-pen-fancy"></i></div>
            <div>
                <div class="editor-card-label">Éditeur Hero</div>
                <div class="editor-card-sub">Contenu affiché en haut de la page d'accueil</div>
            </div>
        </div>
        <button id="saveHeroBtn" class="btn-primary"><i class="fas fa-save"></i> Publier</button>
    </div>
    <div class="editor-card-body">
        <div class="form-grid">
            <div class="field-group full">
                <label class="field-label"><i class="fas fa-heading"></i> Titre principal</label>
                <input id="heroTitle" class="field-input" type="text"
                       placeholder="Ex: WORLD INNOVATIONS BUSINESS CONSULTING"
                       oninput="document.getElementById('previewTitle').textContent=this.value">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-quote-left"></i> Tagline</label>
                <input id="heroTagline" class="field-input" type="text"
                       placeholder="Ex: Une vision mondiale, un impact africain"
                       oninput="document.getElementById('previewTagline').textContent=this.value">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-hand-pointer"></i> Texte du bouton</label>
                <input id="heroBtn" class="field-input" type="text"
                       placeholder="Ex: Nous contacter"
                       oninput="document.getElementById('previewBtn').textContent=this.value">
            </div>
            <div class="field-group full">
                <label class="field-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="heroDesc" class="field-input" rows="3"
                          placeholder="Décrivez l'activité de WIBC..."
                          oninput="document.getElementById('previewDesc').textContent=this.value"></textarea>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Chargement depuis l'API
    api('GET', '/admin/api/hero').then(data => {
        document.getElementById('heroTitle').value   = data.title       ?? '';
        document.getElementById('heroTagline').value = data.tagline     ?? '';
        document.getElementById('heroDesc').value    = data.description ?? '';
        document.getElementById('heroBtn').value     = data.btnText     ?? '';

        document.getElementById('previewTitle').textContent   = data.title       ?? '';
        document.getElementById('previewTagline').textContent = data.tagline     ?? '';
        document.getElementById('previewDesc').textContent    = data.description ?? '';
        document.getElementById('previewBtn').textContent     = data.btnText     ?? '';
    });

    // Sauvegarde via l'API
    document.getElementById('saveHeroBtn').onclick = async () => {
        const btn = document.getElementById('saveHeroBtn');
        btn.disabled = true;
        try {
            await api('PUT', '/admin/api/hero', {
                title:       document.getElementById('heroTitle').value,
                tagline:     document.getElementById('heroTagline').value,
                description: document.getElementById('heroDesc').value,
                btnText:     document.getElementById('heroBtn').value,
            });
            showToast('Hero mis à jour — visible sur le site');
        } catch(e) {
            showToast('Erreur : ' + e.message, 'error');
        } finally {
            btn.disabled = false;
        }
    };
</script>
@endsection
