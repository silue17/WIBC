@extends('admin.layout')
@section('title', 'Actualités')
@section('subtitle', 'Gérez les articles et actualités de WIBC')

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
    .news-thumb {
        width: 100%; height: 120px; border-radius: 12px;
        object-fit: cover; display: block;
        border: 2px solid var(--border-light);
    }
    .news-card-img {
        width: 100%; height: 130px; border-radius: 12px;
        object-fit: cover; display: block; margin-bottom: 12px;
        border: 1.5px solid var(--border-light);
    }
    .news-card-img-fallback {
        width: 100%; height: 130px; border-radius: 12px;
        background: var(--bg-app);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px;
        border: 1.5px solid var(--border-light);
        color: var(--text-muted); font-size: 2rem;
    }
</style>

<div class="toolbar">
    <div class="count-label"><strong id="newsCount">0</strong> article(s)</div>
    <button id="addNewsBtn" class="btn-primary"><i class="fas fa-plus"></i> Ajouter un article</button>
</div>

<div class="items-grid" id="newsGrid"></div>

@endsection

@section('scripts')
<script>
    let newsList = [];
    let pendingPhoto = null;

    // dd/mm/yyyy → yyyy-mm-dd (pour <input type="date">)
    function dateToInput(d) {
        if (!d) return '';
        const m = d.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
        if (m) return `${m[3]}-${m[2]}-${m[1]}`;
        if (/^\d{4}-\d{2}-\d{2}$/.test(d)) return d;
        return '';
    }
    // yyyy-mm-dd → dd/mm/yyyy (pour stockage et affichage)
    function inputToDate(d) {
        if (!d) return '';
        const m = d.match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (m) return `${m[3]}/${m[2]}/${m[1]}`;
        return d;
    }
    // Affichage sécurisé : retourne toujours jj/mm/aaaa
    function displayDate(d) {
        if (!d) return '';
        const m = d.match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (m) return `${m[3]}/${m[2]}/${m[1]}`;
        return d;
    }

    function renderNews() {
        document.getElementById('newsCount').textContent = newsList.length;
        document.getElementById('newsGrid').innerHTML = newsList.map(n => `
            <div class="item-card">
                ${n.photo
                    ? `<img class="news-card-img" data-news-photo="${n.id}" src="" alt="${escapeHtml(n.title)}">`
                    : `<div class="news-card-img-fallback"><i class="fas fa-newspaper"></i></div>`
                }
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;">
                    <div class="item-icon"><i class="fas fa-newspaper"></i></div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.88rem;margin-bottom:5px;line-height:1.3;">${escapeHtml(n.title)}</div>
                        <span class="badge badge-navy"><i class="fas fa-calendar-alt"></i> ${escapeHtml(displayDate(n.date||''))}</span>
                    </div>
                </div>
                <div style="font-size:0.78rem;color:var(--text-muted);line-height:1.55;margin-bottom:12px;">${escapeHtml((n.description||'').substring(0,100))}${(n.description||'').length>100?'…':''}</div>
                <div class="item-actions">
                    <button class="btn-edit" onclick="editNews(${n.id})"><i class="fas fa-pen"></i> Modifier</button>
                    <button class="btn-delete" onclick="deleteNews(${n.id})"><i class="fas fa-trash"></i></button>
                </div>
            </div>`).join('') || '<div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);">Aucun article.</div>';

        // Assigner les photos après le rendu
        newsList.forEach(n => {
            if (!n.photo) return;
            const img = document.querySelector(`img[data-news-photo="${n.id}"]`);
            if (img) img.src = n.photo;
        });
    }

    function newsPhotoSection(n = {}) {
        return `
        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;padding-bottom:16px;margin-bottom:16px;border-bottom:1px solid var(--border-light);">
            <img id="newsPhotoPreview" class="news-thumb" src="" style="${n.photo ? '' : 'display:none;'}">
            <div id="newsPhotoFallback" style="width:100%;height:120px;border-radius:12px;background:var(--bg-app);border:1.5px solid var(--border-light);display:${n.photo ? 'none' : 'flex'};align-items:center;justify-content:center;color:var(--text-muted);font-size:2rem;">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="upload-zone" id="newsUploadZone" style="width:100%;">
                <input type="file" id="newsFileInput" accept="image/jpeg,image/png,image/webp">
                <div class="upload-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <p><strong>Cliquer</strong> ou glisser une photo<br>
                   <span style="font-size:0.7rem;">JPG · PNG · WEBP — max 5 Mo</span></p>
            </div>
            ${n.photo ? `<button type="button" onclick="removeNewsPhoto()" style="font-size:0.75rem;color:var(--red);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;"><i class="fas fa-times-circle"></i> Supprimer la photo</button>` : ''}
        </div>`;
    }

    function wireNewsUpload(currentPhoto) {
        const input = document.getElementById('newsFileInput');
        const zone  = document.getElementById('newsUploadZone');
        if (!input || !zone) return;

        if (currentPhoto) {
            const prev = document.getElementById('newsPhotoPreview');
            if (prev) prev.src = currentPhoto;
        }

        input.addEventListener('change', e => { if (e.target.files[0]) readNewsPhoto(e.target.files[0]); });
        zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('drag-over');
            const f = e.dataTransfer.files[0];
            if (f && f.type.startsWith('image/')) readNewsPhoto(f);
        });
    }

    function readNewsPhoto(file) {
        if (file.size > 5 * 1024 * 1024) { showToast('Image trop lourde (max 5 Mo)', 'error'); return; }
        const reader = new FileReader();
        reader.onload = e => {
            pendingPhoto = e.target.result;
            const preview  = document.getElementById('newsPhotoPreview');
            const fallback = document.getElementById('newsPhotoFallback');
            if (preview)  { preview.src = pendingPhoto; preview.style.display = 'block'; }
            if (fallback) { fallback.style.display = 'none'; }
            document.getElementById('newsUploadZone').style.borderColor = 'var(--accent)';
        };
        reader.readAsDataURL(file);
    }

    function removeNewsPhoto() {
        pendingPhoto = '';
        const preview  = document.getElementById('newsPhotoPreview');
        const fallback = document.getElementById('newsPhotoFallback');
        if (preview)  { preview.src = ''; preview.style.display = 'none'; }
        if (fallback) { fallback.style.display = 'flex'; }
    }

    function newsForm(n = {}) {
        return `
            ${newsPhotoSection(n)}
            <div class="field-group">
                <label class="field-label"><i class="fas fa-heading"></i> Titre</label>
                <input id="nTitle" class="field-input" value="${escapeHtml(n.title||'')}" placeholder="Titre de l'article">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-calendar"></i> Date (jj/mm/aaaa)</label>
                <input id="nDate" type="date" class="field-input" value="${dateToInput(n.date||'')}">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-align-left"></i> Résumé</label>
                <textarea id="nDesc" class="field-input" rows="3">${escapeHtml(n.description||'')}</textarea>
            </div>`;
    }

    function editNews(id) {
        const n = newsList.find(x => x.id === id);
        if (!n) return;
        pendingPhoto = null;
        document.querySelector('.modal-content').style.maxWidth = '520px';

        openModal("Modifier l'article", newsForm(n) + `
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-save"></i> Enregistrer
            </button>`,
            async () => {
                try {
                    const payload = {
                        title:       document.getElementById('nTitle').value,
                        date:        inputToDate(document.getElementById('nDate').value),
                        description: document.getElementById('nDesc').value,
                    };
                    if (pendingPhoto !== null) payload.photo = pendingPhoto;
                    const updated = await api('PUT', `/admin/api/news/${id}`, payload);
                    const idx = newsList.findIndex(x => x.id === id);
                    newsList[idx] = updated;
                    renderNews();
                    showToast('Article mis à jour');
                } catch(e) { showToast(e.message, 'error'); }
                document.querySelector('.modal-content').style.maxWidth = '';
            });

        setTimeout(() => wireNewsUpload(n.photo), 60);
    }

    async function deleteNews(id) {
        if (!confirm('Supprimer cet article ?')) return;
        try {
            await api('DELETE', `/admin/api/news/${id}`);
            newsList = newsList.filter(x => x.id !== id);
            renderNews();
            showToast('Article supprimé', 'error');
        } catch(e) { showToast(e.message, 'error'); }
    }

    document.getElementById('addNewsBtn').onclick = () => {
        pendingPhoto = null;
        document.querySelector('.modal-content').style.maxWidth = '520px';
        openModal('Nouvel article', newsForm() + `
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-plus"></i> Créer
            </button>`,
            async () => {
                try {
                    const payload = {
                        title:       document.getElementById('nTitle').value,
                        date:        inputToDate(document.getElementById('nDate').value),
                        description: document.getElementById('nDesc').value,
                    };
                    if (pendingPhoto !== null) payload.photo = pendingPhoto;
                    const created = await api('POST', '/admin/api/news', payload);
                    newsList.push(created);
                    renderNews();
                    showToast('Article ajouté');
                } catch(e) { showToast(e.message, 'error'); }
                document.querySelector('.modal-content').style.maxWidth = '';
            });

        setTimeout(() => wireNewsUpload(null), 60);
    };

    async function load() {
        newsList = await api('GET', '/admin/api/news');
        renderNews();
    }

    load();
</script>
@endsection
