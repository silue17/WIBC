@extends('admin.layout')
@section('title', 'À propos')
@section('subtitle', 'Présentation de WIBC et sa mission')

@section('content')

<div class="preview-panel">
    <div class="preview-panel-inner">
        <div class="live-badge"><span class="live-badge-dot"></span> Aperçu en direct</div>
        <div id="previewAboutTitle" style="font-size:1.7rem;font-weight:900;color:#fff;margin-bottom:14px;letter-spacing:-0.03em;line-height:1.2;"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
            <div id="previewAboutDesc1" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-left:3px solid #4ade80;border-radius:14px;padding:14px 16px;font-size:0.82rem;color:rgba(255,255,255,0.65);line-height:1.6;"></div>
            <div id="previewAboutDesc2" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-left:3px solid #e8392a;border-radius:14px;padding:14px 16px;font-size:0.82rem;color:rgba(255,255,255,0.65);line-height:1.6;"></div>
        </div>
        <span id="previewAboutBadge" style="display:inline-flex;align-items:center;gap:7px;background:rgba(4,120,71,0.25);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:6px 18px;border-radius:999px;font-size:0.78rem;font-weight:700;"></span>
    </div>
</div>

<div class="editor-card">
    <div class="editor-card-header">
        <div class="editor-card-title">
            <div class="editor-card-icon"><i class="fas fa-pen-fancy"></i></div>
            <div>
                <div class="editor-card-label">Section À propos</div>
                <div class="editor-card-sub">Mission et valeurs de WIBC</div>
            </div>
        </div>
        <button id="saveAboutBtn" class="btn-primary"><i class="fas fa-save"></i> Publier</button>
    </div>
    <div class="editor-card-body">
        <div class="form-grid">
            <div class="field-group full">
                <label class="field-label"><i class="fas fa-heading"></i> Titre principal</label>
                <input id="aboutTitle" class="field-input" type="text"
                       placeholder="Ex: Transformer l'Afrique par l'innovation"
                       oninput="document.getElementById('previewAboutTitle').textContent=this.value">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-quote-left"></i> Bloc mission (bordure verte)</label>
                <textarea id="aboutDesc1" class="field-input" rows="4"
                          placeholder="Décrivez la mission principale..."
                          oninput="document.getElementById('previewAboutDesc1').textContent=this.value"></textarea>
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-quote-right"></i> Bloc valeur ajoutée (bordure rouge)</label>
                <textarea id="aboutDesc2" class="field-input" rows="4"
                          placeholder="Décrivez la valeur ajoutée..."
                          oninput="document.getElementById('previewAboutDesc2').textContent=this.value"></textarea>
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-certificate"></i> Badge / Label</label>
                <input id="aboutBadge" class="field-input" type="text"
                       placeholder="Ex: 100% Ivoirien"
                       oninput="document.getElementById('previewAboutBadge').textContent=this.value">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    api('GET', '/admin/api/about').then(data => {
        document.getElementById('aboutTitle').value = data.title        ?? '';
        document.getElementById('aboutDesc1').value = data.description1 ?? '';
        document.getElementById('aboutDesc2').value = data.description2 ?? '';
        document.getElementById('aboutBadge').value = data.badge        ?? '';

        document.getElementById('previewAboutTitle').textContent = data.title        ?? '';
        document.getElementById('previewAboutDesc1').textContent = data.description1 ?? '';
        document.getElementById('previewAboutDesc2').textContent = data.description2 ?? '';
        document.getElementById('previewAboutBadge').textContent = data.badge        ?? '';
    });

    document.getElementById('saveAboutBtn').onclick = async () => {
        const btn = document.getElementById('saveAboutBtn');
        btn.disabled = true;
        try {
            await api('PUT', '/admin/api/about', {
                title:        document.getElementById('aboutTitle').value,
                description1: document.getElementById('aboutDesc1').value,
                description2: document.getElementById('aboutDesc2').value,
                badge:        document.getElementById('aboutBadge').value,
            });
            showToast('Section "À propos" mise à jour');
        } catch(e) {
            showToast('Erreur : ' + e.message, 'error');
        } finally {
            btn.disabled = false;
        }
    };
</script>
@endsection
