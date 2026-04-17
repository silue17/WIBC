@extends('admin.layout')
@section('title', 'Réalisations')
@section('subtitle', 'Gérez les projets et réalisations de WIBC')

@section('content')

<style>
    .upload-zone {
        border: 2px dashed var(--border-light);
        border-radius: 14px;
        padding: 16px 14px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--bg-app);
        position: relative;
        overflow: hidden;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color: var(--accent);
        background: var(--accent-soft);
    }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%; z-index: 2;
    }
    .upload-zone-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--accent); font-size: 0.95rem; margin: 0 auto 8px;
    }
    .upload-zone p { font-size: 0.78rem; color: var(--text-muted); line-height: 1.5; margin: 0; }
    .upload-zone strong { color: var(--accent); }
    .ach-thumb {
        width: 100%; height: 120px; border-radius: 12px;
        object-fit: cover; display: block;
        border: 2px solid var(--border-light);
    }
    .ach-card-img {
        width: 100%; height: 140px; border-radius: 12px;
        object-fit: cover; display: block; margin-bottom: 12px;
        border: 1.5px solid var(--border-light);
    }
    .ach-card-img-fallback {
        width: 100%; height: 140px; border-radius: 12px;
        background: var(--bg-app);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px;
        border: 1.5px solid var(--border-light);
        color: var(--text-muted); font-size: 2rem;
    }
</style>

<div class="toolbar">
    <div class="filter-bar" id="filterBar">
        <button class="chip active" data-cat="all">Tous</button>
    </div>
    <button id="addAchBtn" class="btn-primary"><i class="fas fa-plus"></i> Ajouter</button>
</div>

<div class="items-grid" id="achGrid"></div>

@endsection

@section('scripts')
<script>
    let achievements = [];
    let activeFilter = 'all';
    let pendingPhoto = null;

    function categories() {
        return [...new Set(achievements.map(a => a.category).filter(Boolean))];
    }

    function renderFilters() {
        const bar = document.getElementById('filterBar');
        bar.innerHTML = `<button class="chip ${activeFilter==='all'?'active':''}" data-cat="all">Tous</button>`
            + categories().map(c => `<button class="chip ${activeFilter===c?'active':''}" data-cat="${escapeHtml(c)}">${escapeHtml(c)}</button>`).join('');
        bar.querySelectorAll('.chip').forEach(btn => {
            btn.onclick = () => { activeFilter = btn.dataset.cat; renderAll(); };
        });
    }

    function renderAch() {
        const list = activeFilter === 'all' ? achievements : achievements.filter(a => a.category === activeFilter);
        document.getElementById('achGrid').innerHTML = list.map((a, idx) => `
            <div class="item-card">
                ${a.photo
                    ? `<img class="ach-card-img" data-ach-photo="${a.id}" src="" alt="${escapeHtml(a.title)}">`
                    : `<div class="ach-card-img-fallback"><i class="fas fa-image"></i></div>`
                }
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--red-soft);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;color:var(--red);flex-shrink:0;">
                        ${String(idx+1).padStart(2,'0')}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.88rem;margin-bottom:4px;">${escapeHtml(a.title)}</div>
                        <span class="badge badge-navy">${escapeHtml(a.category||'—')}</span>
                    </div>
                </div>
                <div style="font-size:0.78rem;color:var(--text-muted);line-height:1.55;margin-bottom:12px;">${escapeHtml((a.description||'').substring(0,100))}${(a.description||'').length>100?'…':''}</div>
                <div class="item-actions">
                    <button class="btn-edit" onclick="editAch(${a.id})"><i class="fas fa-pen"></i> Modifier</button>
                    <button class="btn-delete" onclick="deleteAch(${a.id})"><i class="fas fa-trash"></i></button>
                </div>
            </div>`).join('') || '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted);">Aucune réalisation dans cette catégorie.</div>';

        // Assigner les photos après le rendu
        achievements.forEach(a => {
            if (!a.photo) return;
            const img = document.querySelector(`img[data-ach-photo="${a.id}"]`);
            if (img) img.src = a.photo;
        });
    }

    function renderAll() { renderFilters(); renderAch(); }

    function achPhotoSection(a = {}) {
        return `
        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;padding-bottom:16px;margin-bottom:16px;border-bottom:1px solid var(--border-light);">
            <img id="achPhotoPreview" class="ach-thumb" src="" style="${a.photo ? '' : 'display:none;'}">
            <div id="achPhotoFallback" style="width:100%;height:120px;border-radius:12px;background:var(--bg-app);border:1.5px solid var(--border-light);display:${a.photo ? 'none' : 'flex'};align-items:center;justify-content:center;color:var(--text-muted);font-size:2rem;">
                <i class="fas fa-image"></i>
            </div>
            <div class="upload-zone" id="achUploadZone" style="width:100%;">
                <input type="file" id="achFileInput" accept="image/jpeg,image/png,image/webp">
                <div class="upload-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <p><strong>Cliquer</strong> ou glisser une photo<br>
                   <span style="font-size:0.7rem;">JPG · PNG · WEBP — max 5 Mo</span></p>
            </div>
            ${a.photo ? `<button type="button" onclick="removeAchPhoto()" style="font-size:0.75rem;color:var(--red);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;"><i class="fas fa-times-circle"></i> Supprimer la photo</button>` : ''}
        </div>`;
    }

    function wireAchUpload(currentPhoto) {
        const input = document.getElementById('achFileInput');
        const zone  = document.getElementById('achUploadZone');
        if (!input || !zone) return;

        if (currentPhoto) {
            const prev = document.getElementById('achPhotoPreview');
            if (prev) prev.src = currentPhoto;
        }

        input.addEventListener('change', e => { if (e.target.files[0]) readAchPhoto(e.target.files[0]); });
        zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('drag-over');
            const f = e.dataTransfer.files[0];
            if (f && f.type.startsWith('image/')) readAchPhoto(f);
        });
    }

    function readAchPhoto(file) {
        if (file.size > 5 * 1024 * 1024) { showToast('Image trop lourde (max 5 Mo)', 'error'); return; }
        const reader = new FileReader();
        reader.onload = e => {
            pendingPhoto = e.target.result;
            const preview  = document.getElementById('achPhotoPreview');
            const fallback = document.getElementById('achPhotoFallback');
            if (preview)  { preview.src = pendingPhoto; preview.style.display = 'block'; }
            if (fallback) { fallback.style.display = 'none'; }
            document.getElementById('achUploadZone').style.borderColor = 'var(--accent)';
        };
        reader.readAsDataURL(file);
    }

    function removeAchPhoto() {
        pendingPhoto = '';
        const preview  = document.getElementById('achPhotoPreview');
        const fallback = document.getElementById('achPhotoFallback');
        if (preview)  { preview.src = ''; preview.style.display = 'none'; }
        if (fallback) { fallback.style.display = 'flex'; }
    }

    function achForm(a = {}) {
        return `
            ${achPhotoSection(a)}
            <div class="field-group">
                <label class="field-label"><i class="fas fa-trophy"></i> Titre</label>
                <input id="aTitle" class="field-input" value="${escapeHtml(a.title||'')}" placeholder="Ex: African MMA League">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-tag"></i> Catégorie</label>
                <input id="aCat" class="field-input" value="${escapeHtml(a.category||'')}" placeholder="Ex: Sport, Innovation...">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="aDesc" class="field-input" rows="3">${escapeHtml(a.description||'')}</textarea>
            </div>`;
    }

    function editAch(id) {
        const a = achievements.find(x => x.id === id);
        if (!a) return;
        pendingPhoto = null;
        document.querySelector('.modal-content').style.maxWidth = '520px';

        openModal('Modifier la réalisation', achForm(a) + `
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-save"></i> Enregistrer
            </button>`,
            async () => {
                try {
                    const payload = {
                        title:       document.getElementById('aTitle').value,
                        category:    document.getElementById('aCat').value,
                        description: document.getElementById('aDesc').value,
                    };
                    if (pendingPhoto !== null) payload.photo = pendingPhoto;
                    const updated = await api('PUT', `/admin/api/achievements/${id}`, payload);
                    const idx = achievements.findIndex(x => x.id === id);
                    achievements[idx] = updated;
                    renderAll();
                    showToast('Réalisation mise à jour');
                } catch(e) { showToast(e.message, 'error'); }
                document.querySelector('.modal-content').style.maxWidth = '';
            });

        setTimeout(() => wireAchUpload(a.photo), 60);
    }

    async function deleteAch(id) {
        if (!confirm('Supprimer cette réalisation ?')) return;
        try {
            await api('DELETE', `/admin/api/achievements/${id}`);
            achievements = achievements.filter(x => x.id !== id);
            renderAll();
            showToast('Réalisation supprimée', 'error');
        } catch(e) { showToast(e.message, 'error'); }
    }

    document.getElementById('addAchBtn').onclick = () => {
        pendingPhoto = null;
        document.querySelector('.modal-content').style.maxWidth = '520px';
        openModal('Nouvelle réalisation', achForm() + `
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-plus"></i> Créer
            </button>`,
            async () => {
                try {
                    const payload = {
                        title:       document.getElementById('aTitle').value,
                        category:    document.getElementById('aCat').value,
                        description: document.getElementById('aDesc').value,
                    };
                    if (pendingPhoto !== null) payload.photo = pendingPhoto;
                    const created = await api('POST', '/admin/api/achievements', payload);
                    achievements.push(created);
                    renderAll();
                    showToast('Réalisation ajoutée');
                } catch(e) { showToast(e.message, 'error'); }
                document.querySelector('.modal-content').style.maxWidth = '';
            });

        setTimeout(() => wireAchUpload(null), 60);
    };

    async function load() {
        achievements = await api('GET', '/admin/api/achievements');
        renderAll();
    }

    load();
</script>
@endsection
