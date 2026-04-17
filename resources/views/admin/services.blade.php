@extends('admin.layout')
@section('title', 'Pôles d\'expertise')
@section('subtitle', 'Gérez les services proposés par WIBC')

@section('content')

<style>
    .tag-list { display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;min-height:20px; }
    .tag-item {
        display:inline-flex;align-items:center;gap:6px;
        background:var(--accent-soft);border:1px solid rgba(4,120,71,0.2);
        color:var(--accent);font-size:0.75rem;font-weight:600;
        padding:4px 12px;border-radius:999px;
    }
    .tag-item button {
        background:none;border:none;cursor:pointer;color:var(--accent);
        font-size:0.75rem;padding:0;line-height:1;opacity:0.7;
    }
    .tag-item button:hover { opacity:1; }
    .tag-input-wrap { display:flex;gap:8px; }
    .tag-input-wrap input { flex:1; }
    .tag-input-wrap button { flex-shrink:0;padding:0 16px; }
    .service-tags { display:flex;flex-wrap:wrap;gap:6px;margin-top:10px; }
    .service-tag-badge {
        font-size:0.72rem;font-weight:600;padding:3px 10px;
        border-radius:999px;background:var(--accent-soft);
        color:var(--accent);border:1px solid rgba(4,120,71,0.15);
    }
</style>

<div class="toolbar">
    <div class="count-label"><strong id="itemCount">0</strong> pôle(s)</div>
    <button id="addBtn" class="btn-primary"><i class="fas fa-plus"></i> Ajouter un pôle</button>
</div>

<div class="items-grid" id="itemsGrid"></div>

@endsection

@section('scripts')
<script>
    let services = [];
    let pendingFeatures = [];

    function renderItems() {
        document.getElementById('itemCount').textContent = services.length;
        document.getElementById('itemsGrid').innerHTML = services.map(item => {
            const features = Array.isArray(item.features) ? item.features : [];
            return `
            <div class="item-card">
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;">
                    <div class="item-icon"><i class="fas fa-chart-line"></i></div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.9rem;margin-bottom:5px;">${escapeHtml(item.name)}</div>
                        <div style="font-size:0.79rem;color:var(--text-muted);line-height:1.5;">${escapeHtml((item.description||'').substring(0,90))}${(item.description||'').length>90?'…':''}</div>
                        ${features.length ? `<div class="service-tags">${features.map(f=>`<span class="service-tag-badge">${escapeHtml(f)}</span>`).join('')}</div>` : ''}
                    </div>
                </div>
                <div class="item-actions">
                    <button class="btn-edit" onclick="editItem(${item.id})"><i class="fas fa-pen"></i> Modifier</button>
                    <button class="btn-delete" onclick="deleteItem(${item.id})"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('') || '<div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);">Aucun pôle enregistré.</div>';
    }

    function tagSection(features) {
        return `
        <div class="field-group" style="margin-bottom:18px;">
            <label class="field-label"><i class="fas fa-tags"></i> Tags / Compétences</label>
            <div class="tag-list" id="tagList">
                ${features.map((f,i) => `
                <span class="tag-item">
                    ${escapeHtml(f)}
                    <button type="button" onclick="removeTag(${i})"><i class="fas fa-times"></i></button>
                </span>`).join('')}
            </div>
            <div class="tag-input-wrap">
                <input id="tagInput" class="field-input" placeholder="Ex: Gestion de carrière" style="font-size:0.85rem;">
                <button type="button" class="btn-primary" style="padding:0 14px;font-size:0.82rem;" onclick="addTag()">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>
        </div>`;
    }

    function renderTagList() {
        const list = document.getElementById('tagList');
        if (!list) return;
        list.innerHTML = pendingFeatures.map((f,i) => `
            <span class="tag-item">
                ${escapeHtml(f)}
                <button type="button" onclick="removeTag(${i})"><i class="fas fa-times"></i></button>
            </span>`).join('');
    }

    function addTag() {
        const input = document.getElementById('tagInput');
        const val = input.value.trim();
        if (!val) return;
        if (!pendingFeatures.includes(val)) {
            pendingFeatures.push(val);
            renderTagList();
        }
        input.value = '';
        input.focus();
    }

    function removeTag(i) {
        pendingFeatures.splice(i, 1);
        renderTagList();
    }

    function serviceForm(item = {}) {
        pendingFeatures = Array.isArray(item.features) ? [...item.features] : [];
        return `
            <div class="field-group">
                <label class="field-label"><i class="fas fa-heading"></i> Nom du pôle</label>
                <input id="editName" class="field-input" value="${escapeHtml(item.name||'')}" placeholder="Ex: Strategy & Business">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="editDesc" class="field-input" rows="3" placeholder="Décrivez ce pôle...">${escapeHtml(item.description||'')}</textarea>
            </div>
            ${tagSection(pendingFeatures)}`;
    }

    function editItem(id) {
        const item = services.find(i => i.id === id);
        if (!item) return;
        openModal('Modifier le pôle', serviceForm(item) + `
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-save"></i> Enregistrer
            </button>`,
            async () => {
                try {
                    const updated = await api('PUT', `/admin/api/services/${id}`, {
                        name:        document.getElementById('editName').value,
                        description: document.getElementById('editDesc').value,
                        features:    pendingFeatures,
                    });
                    const idx = services.findIndex(i => i.id === id);
                    services[idx] = updated;
                    renderItems();
                    showToast('Pôle mis à jour');
                } catch(e) { showToast(e.message, 'error'); }
            });

        // Permettre Entrée pour ajouter un tag
        setTimeout(() => {
            const input = document.getElementById('tagInput');
            if (input) input.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addTag(); } });
        }, 60);
    }

    async function deleteItem(id) {
        if (!confirm('Supprimer ce pôle ?')) return;
        try {
            await api('DELETE', `/admin/api/services/${id}`);
            services = services.filter(i => i.id !== id);
            renderItems();
            showToast('Pôle supprimé', 'error');
        } catch(e) { showToast(e.message, 'error'); }
    }

    document.getElementById('addBtn').onclick = () => {
        openModal('Nouveau pôle', serviceForm() + `
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-plus"></i> Créer
            </button>`,
            async () => {
                try {
                    const created = await api('POST', '/admin/api/services', {
                        name:        document.getElementById('editName').value,
                        description: document.getElementById('editDesc').value,
                        features:    pendingFeatures,
                    });
                    services.push(created);
                    renderItems();
                    showToast('Pôle créé');
                } catch(e) { showToast(e.message, 'error'); }
            });

        setTimeout(() => {
            const input = document.getElementById('tagInput');
            if (input) input.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addTag(); } });
        }, 60);
    };

    async function load() {
        services = await api('GET', '/admin/api/services');
        renderItems();
    }

    load();
</script>
@endsection
