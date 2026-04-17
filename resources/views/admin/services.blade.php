@extends('admin.layout')
@section('title', 'Pôles d\'expertise')
@section('subtitle', 'Gérez les services proposés par WIBC')

@section('content')

<div class="toolbar">
    <div class="count-label"><strong id="itemCount">0</strong> pôle(s)</div>
    <button id="addBtn" class="btn-primary"><i class="fas fa-plus"></i> Ajouter un pôle</button>
</div>

<div class="items-grid" id="itemsGrid"></div>

@endsection

@section('scripts')
<script>
    let services = [];

    function renderItems() {
        document.getElementById('itemCount').textContent = services.length;
        document.getElementById('itemsGrid').innerHTML = services.map(item => `
            <div class="item-card">
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;">
                    <div class="item-icon"><i class="fas fa-chart-line"></i></div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.9rem;margin-bottom:5px;">${escapeHtml(item.name)}</div>
                        <div style="font-size:0.79rem;color:var(--text-muted);line-height:1.5;">${escapeHtml((item.description||'').substring(0,90))}${(item.description||'').length>90?'…':''}</div>
                    </div>
                </div>
                <div class="item-actions">
                    <button class="btn-edit" onclick="editItem(${item.id})"><i class="fas fa-pen"></i> Modifier</button>
                    <button class="btn-delete" onclick="deleteItem(${item.id})"><i class="fas fa-trash"></i></button>
                </div>
            </div>`).join('') || '<div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);">Aucun pôle enregistré.</div>';
    }

    async function load() {
        services = await api('GET', '/admin/api/services');
        renderItems();
    }

    function editItem(id) {
        const item = services.find(i => i.id === id);
        if (!item) return;
        openModal('Modifier le pôle', `
            <div class="field-group">
                <label class="field-label"><i class="fas fa-heading"></i> Nom du pôle</label>
                <input id="editName" class="field-input" value="${escapeHtml(item.name)}" placeholder="Ex: Strategy & Business">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="editDesc" class="field-input" rows="3">${escapeHtml(item.description||'')}</textarea>
            </div>
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-save"></i> Enregistrer
            </button>`,
            async () => {
                try {
                    const updated = await api('PUT', `/admin/api/services/${id}`, {
                        name:        document.getElementById('editName').value,
                        description: document.getElementById('editDesc').value,
                    });
                    const idx = services.findIndex(i => i.id === id);
                    services[idx] = updated;
                    renderItems();
                    showToast('Pôle mis à jour');
                } catch(e) { showToast(e.message, 'error'); }
            });
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
        openModal('Nouveau pôle', `
            <div class="field-group">
                <label class="field-label"><i class="fas fa-heading"></i> Nom du pôle</label>
                <input id="editName" class="field-input" placeholder="Ex: Strategy & Business">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="editDesc" class="field-input" rows="3" placeholder="Décrivez ce pôle..."></textarea>
            </div>
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-plus"></i> Créer
            </button>`,
            async () => {
                try {
                    const created = await api('POST', '/admin/api/services', {
                        name:        document.getElementById('editName').value,
                        description: document.getElementById('editDesc').value,
                    });
                    services.push(created);
                    renderItems();
                    showToast('Pôle créé');
                } catch(e) { showToast(e.message, 'error'); }
            });
    };

    load();
</script>
@endsection
