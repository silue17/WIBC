@extends('admin.layout')
@section('title', 'Équipe')
@section('subtitle', 'Gérez les membres de l\'équipe WIBC')

@section('content')

<style>
    .upload-zone {
        border: 2px dashed var(--border-light);
        border-radius: 14px;
        padding: 18px 14px;
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
        width: 40px; height: 40px; border-radius: 11px;
        background: var(--accent-soft); display: flex;
        align-items: center; justify-content: center;
        color: var(--accent); font-size: 1rem; margin: 0 auto 8px;
    }
    .upload-zone p { font-size: 0.8rem; color: var(--text-muted); line-height: 1.5; margin: 0; }
    .upload-zone strong { color: var(--accent); }
    .member-avatar { width:56px;height:56px;border-radius:16px;object-fit:cover;flex-shrink:0;border:2.5px solid var(--border-light);display:block; }
    .member-avatar-fallback { width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.05rem;color:#fff;flex-shrink:0; }
    .photo-modal-preview { width:86px;height:86px;border-radius:20px;object-fit:cover;border:3px solid var(--border-light);display:block; }
    .photo-modal-fallback { width:86px;height:86px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.5rem;color:#fff; }
</style>

<div class="toolbar">
    <div class="count-label"><strong id="memberCount">0</strong> membre(s)</div>
    <button id="addMemberBtn" class="btn-primary"><i class="fas fa-user-plus"></i> Ajouter un membre</button>
</div>

<div class="items-grid" id="teamGrid"></div>

@endsection

@section('scripts')
<script>
    const FOUNDERS      = ['miwouguih','secongo','clotchor','johann','coulibaly','ousmane','tidiane','aziz'];
    const AVATAR_COLORS = ['#047847','#e8392a','#07102a','#05a35f'];
    let   members       = [];
    let   pendingPhoto  = null;

    function isFounder(n){ return FOUNDERS.some(f => n.toLowerCase().includes(f)); }
    function getInitials(n){ return n.split(' ').slice(0,2).map(w=>w[0]||'').join('').toUpperCase(); }

    /* ── RENDER ── */
    function renderTeam() {
        document.getElementById('memberCount').textContent = members.length;
        document.getElementById('teamGrid').innerHTML = members.map((m, idx) => {
            const bg      = AVATAR_COLORS[idx % AVATAR_COLORS.length];
            const founder = isFounder(m.name);
            const avatarBlock = m.photo
                ? `<img class="member-avatar" data-photo-id="${m.id}" src="" alt="">
                   <div class="member-avatar-fallback" data-fallback-id="${m.id}" style="background:${bg};display:none;">${getInitials(m.name)}</div>`
                : `<div class="member-avatar-fallback" style="background:${bg};">${getInitials(m.name)}</div>`;

            return `
            <div class="item-card">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                    <div style="position:relative;flex-shrink:0;cursor:pointer;" onclick="editMember(${m.id},true)">
                        ${avatarBlock}
                        <div style="position:absolute;bottom:-5px;right:-5px;width:22px;height:22px;border-radius:7px;background:#047847;border:2.5px solid var(--surface);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.6rem;">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.9rem;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escapeHtml(m.name)}</div>
                        <div style="font-size:0.76rem;color:var(--text-muted);margin-top:3px;">${escapeHtml(m.position||'')}</div>
                    </div>
                    ${founder ? `<span class="badge badge-green" style="flex-shrink:0;"><i class="fas fa-star"></i> Fondateur</span>` : ''}
                </div>
                ${m.bio ? `<div style="font-size:0.78rem;color:var(--text-secondary);line-height:1.55;background:var(--bg-app);border-radius:10px;padding:10px 12px;margin-bottom:12px;">${escapeHtml(m.bio)}</div>` : ''}
                <div class="item-actions">
                    <button class="btn-edit" onclick="editMember(${m.id})"><i class="fas fa-pen"></i> Modifier</button>
                    <button class="btn-delete" onclick="deleteMember(${m.id})"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('') || '<div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);">Aucun membre.</div>';

        // Assigner les src en JS (évite les problèmes base64 dans innerHTML)
        members.forEach(m => {
            if (!m.photo) return;
            const img      = document.querySelector(`img[data-photo-id="${m.id}"]`);
            const fallback = document.querySelector(`[data-fallback-id="${m.id}"]`);
            if (!img) return;
            img.src = m.photo;
            img.onerror = () => { img.style.display='none'; if(fallback) fallback.style.display='flex'; };
            img.onload  = () => { img.style.display='block'; if(fallback) fallback.style.display='none'; };
        });
    }

    /* ── EDIT MODAL ── */
    function editMember(id, focusPhoto = false) {
        const m  = members.find(x => x.id === id);
        if (!m) return;
        pendingPhoto = null;
        const bg = AVATAR_COLORS[members.indexOf(m) % AVATAR_COLORS.length];

        document.querySelector('.modal-content').style.maxWidth = '520px';

        openModal('Modifier le membre', `
            <div style="display:flex;flex-direction:column;align-items:center;gap:12px;padding-bottom:18px;margin-bottom:18px;border-bottom:1px solid var(--border-light);">
                <img id="modalPhotoPreview" class="photo-modal-preview" src="" style="${m.photo?'':'display:none;'}">
                <div id="modalPhotoFallback" class="photo-modal-fallback" style="background:${bg};${m.photo?'display:none;':'display:flex;'}">${getInitials(m.name)}</div>
                <div class="upload-zone" id="uploadZone" style="width:100%;">
                    <input type="file" id="photoFileInput" accept="image/jpeg,image/png,image/webp">
                    <div class="upload-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <p><strong>Cliquer</strong> ou glisser une photo ici<br>
                       <span style="font-size:0.7rem;">JPG · PNG · WEBP — max 2 Mo</span></p>
                </div>
                ${m.photo ? `<button type="button" onclick="removeModalPhoto()" style="font-size:0.75rem;color:var(--red);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;"><i class="fas fa-times-circle"></i> Supprimer la photo</button>` : ''}
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-user"></i> Nom complet</label>
                <input id="mName" class="field-input" value="${escapeHtml(m.name)}" placeholder="Prénom Nom">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-briefcase"></i> Poste</label>
                <input id="mPos" class="field-input" value="${escapeHtml(m.position||'')}" placeholder="Ex: Directeur Général">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-comment"></i> Bio</label>
                <textarea id="mBio" class="field-input" rows="3">${escapeHtml(m.bio||'')}</textarea>
            </div>
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-save"></i> Enregistrer
            </button>`,
            async () => {
                const payload = {
                    name:     document.getElementById('mName').value,
                    position: document.getElementById('mPos').value,
                    bio:      document.getElementById('mBio').value,
                };
                if (pendingPhoto !== null) payload.photo = pendingPhoto;
                try {
                    const updated = await api('PUT', `/admin/api/team/${id}`, payload);
                    const idx = members.findIndex(x => x.id === id);
                    members[idx] = updated;
                    renderTeam();
                    showToast('Membre mis à jour');
                } catch(e) { showToast(e.message, 'error'); }
                document.querySelector('.modal-content').style.maxWidth = '';
            });

        setTimeout(() => {
            if (m.photo) {
                const prev = document.getElementById('modalPhotoPreview');
                if (prev) prev.src = m.photo;
            }
            wireUpload(focusPhoto);
        }, 60);
    }

    function wireUpload(autoOpen) {
        const input = document.getElementById('photoFileInput');
        const zone  = document.getElementById('uploadZone');
        if (!input || !zone) return;
        input.addEventListener('change', e => { if(e.target.files[0]) readPhoto(e.target.files[0]); });
        zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('drag-over');
            const f = e.dataTransfer.files[0];
            if (f && f.type.startsWith('image/')) readPhoto(f);
        });
        if (autoOpen) input.click();
    }

    function readPhoto(file) {
        if (file.size > 2.5 * 1024 * 1024) { showToast('Image trop lourde (max 2 Mo)', 'error'); return; }
        const reader = new FileReader();
        reader.onload = e => {
            pendingPhoto = e.target.result;
            const preview  = document.getElementById('modalPhotoPreview');
            const fallback = document.getElementById('modalPhotoFallback');
            if (preview)  { preview.src = pendingPhoto; preview.style.display = 'block'; }
            if (fallback) { fallback.style.display = 'none'; }
            document.getElementById('uploadZone').style.borderColor = 'var(--accent)';
        };
        reader.readAsDataURL(file);
    }

    function removeModalPhoto() {
        pendingPhoto = '';
        const preview  = document.getElementById('modalPhotoPreview');
        const fallback = document.getElementById('modalPhotoFallback');
        if (preview)  { preview.src = ''; preview.style.display = 'none'; }
        if (fallback) { fallback.style.display = 'flex'; }
    }

    /* ── DELETE ── */
    async function deleteMember(id) {
        if (!confirm('Supprimer ce membre ?')) return;
        try {
            await api('DELETE', `/admin/api/team/${id}`);
            members = members.filter(x => x.id !== id);
            renderTeam();
            showToast('Membre supprimé', 'error');
        } catch(e) { showToast(e.message, 'error'); }
    }

    /* ── ADD ── */
    document.getElementById('addMemberBtn').onclick = () => {
        pendingPhoto = null;
        document.querySelector('.modal-content').style.maxWidth = '520px';
        openModal('Nouveau membre', `
            <div class="field-group">
                <label class="field-label"><i class="fas fa-user"></i> Nom complet</label>
                <input id="mName" class="field-input" placeholder="Prénom Nom">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-briefcase"></i> Poste</label>
                <input id="mPos" class="field-input" placeholder="Ex: Directeur Général">
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-comment"></i> Bio</label>
                <textarea id="mBio" class="field-input" rows="3"></textarea>
            </div>
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                <i class="fas fa-plus"></i> Créer
            </button>`,
            async () => {
                try {
                    const created = await api('POST', '/admin/api/team', {
                        name:     document.getElementById('mName').value,
                        position: document.getElementById('mPos').value,
                        bio:      document.getElementById('mBio').value,
                    });
                    members.push(created);
                    renderTeam();
                    showToast('Membre ajouté');
                } catch(e) { showToast(e.message, 'error'); }
                document.querySelector('.modal-content').style.maxWidth = '';
            });
    };

    /* ── LOAD ── */
    async function load() {
        members = await api('GET', '/admin/api/team');
        renderTeam();
    }

    load();
</script>
@endsection
