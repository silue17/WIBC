@extends('admin.layout')
@section('title', 'Galerie')
@section('subtitle', 'Gérez les photos affichées sur le site')

@section('content')

<style>
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 14px;
    }
    .gallery-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 1;
        background: var(--bg-app);
        border: 1.5px solid var(--border-light);
        cursor: pointer;
        transition: all 0.2s;
    }
    .gallery-card:hover .gallery-overlay { opacity: 1; }
    .gallery-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: rgba(7,16,42,0.65);
        opacity: 0;
        transition: opacity 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .gallery-action-btn {
        width: 36px; height: 36px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.25);
        background: rgba(255,255,255,0.15);
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: background 0.2s;
    }
    .gallery-action-btn:hover { background: rgba(255,255,255,0.35); }
    .gallery-action-btn.red { background: rgba(232,57,42,0.25); border-color: rgba(232,57,42,0.4); }
    .gallery-action-btn.red:hover { background: rgba(232,57,42,0.65); }
    .upload-zone {
        border: 2px dashed var(--border-light);
        border-radius: 14px;
        padding: 20px 14px;
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
        width: 44px; height: 44px; border-radius: 12px;
        background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--accent); font-size: 1.1rem; margin: 0 auto 10px;
    }
    .upload-zone p { font-size: 0.8rem; color: var(--text-muted); line-height: 1.5; margin: 0; }
    .upload-zone strong { color: var(--accent); }
</style>

<div class="toolbar">
    <div class="count-label"><strong id="photoCount">0</strong> photo(s)</div>
    <button id="addPhotoBtn" class="btn-primary"><i class="fas fa-plus"></i> Ajouter une photo</button>
</div>

<div class="gallery-grid" id="galleryGrid"></div>

<!-- Preview Modal -->
<div class="modal" id="previewModal">
    <div style="position:relative;max-width:800px;width:90%;">
        <img id="previewImg" src="" alt="" style="width:100%;border-radius:20px;box-shadow:0 30px 60px rgba(0,0,0,0.5);display:block;">
        <button onclick="closePreview()" style="position:absolute;top:-14px;right:-14px;width:38px;height:38px;border-radius:50%;background:#e8392a;border:none;color:#fff;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(232,57,42,0.4);">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let gallery = [];

    function renderGallery() {
        document.getElementById('photoCount').textContent = gallery.length;
        const grid = document.getElementById('galleryGrid');
        if (!gallery.length) {
            grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);">Aucune photo dans la galerie.</div>';
            return;
        }
        grid.innerHTML = gallery.map(item => `
            <div class="gallery-card" data-img-id="${item.id}">
                <img src="" alt="Photo">
                <div class="gallery-overlay">
                    <button class="gallery-action-btn" onclick="event.stopPropagation();openPreviewById(${item.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="gallery-action-btn red" onclick="event.stopPropagation();deletePhoto(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`).join('');

        // Assign src after innerHTML to avoid base64-in-template issues
        gallery.forEach(item => {
            const card = document.querySelector(`[data-img-id="${item.id}"]`);
            if (!card) return;
            const img = card.querySelector('img');
            if (img) img.src = item.url;
        });
    }

    function openPreviewById(id) {
        const item = gallery.find(x => x.id === id);
        if (!item) return;
        document.getElementById('previewImg').src = item.url;
        const modal = document.getElementById('previewModal');
        modal.classList.add('open');
        modal.onclick = e => { if (e.target === modal) closePreview(); };
    }

    function closePreview() {
        document.getElementById('previewImg').src = '';
        document.getElementById('previewModal').classList.remove('open');
    }

    async function deletePhoto(id) {
        if (!confirm('Supprimer cette photo ?')) return;
        try {
            await api('DELETE', `/admin/api/gallery/${id}`);
            gallery = gallery.filter(x => x.id !== id);
            renderGallery();
            showToast('Photo supprimée', 'error');
        } catch(e) { showToast(e.message, 'error'); }
    }

    document.getElementById('addPhotoBtn').onclick = () => {
        openModal('Ajouter des photos', `
            <div class="upload-zone" id="galleryUploadZone">
                <input type="file" id="galleryFileInput" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
                <div class="upload-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <p><strong>Cliquer</strong> ou glisser des photos ici<br>
                   <span style="font-size:0.7rem;">JPG · PNG · WEBP · GIF — max 5 Mo par image</span></p>
            </div>
            <div id="uploadPreviews" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;margin-top:12px;"></div>
            <button id="modalSaveBtn" class="btn-primary" style="width:100%;justify-content:center;margin-top:16px;" disabled>
                <i class="fas fa-plus"></i> Ajouter <span id="uploadCount">0</span> photo(s)
            </button>`,
            async () => {
                if (!pendingGalleryFiles.length) return;
                let added = 0;
                for (const dataUrl of pendingGalleryFiles) {
                    try {
                        const created = await api('POST', '/admin/api/gallery', { url: dataUrl });
                        gallery.push(created);
                        added++;
                    } catch(e) { /* skip failed */ }
                }
                renderGallery();
                if (added) showToast(`${added} photo(s) ajoutée(s)`);
                pendingGalleryFiles = [];
            });

        setTimeout(() => wireGalleryUpload(), 60);
    };

    let pendingGalleryFiles = [];

    function wireGalleryUpload() {
        const input = document.getElementById('galleryFileInput');
        const zone  = document.getElementById('galleryUploadZone');
        if (!input || !zone) return;

        input.addEventListener('change', e => handleGalleryFiles(Array.from(e.target.files)));
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('drag-over');
            handleGalleryFiles(Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')));
        });
    }

    function handleGalleryFiles(files) {
        pendingGalleryFiles = [];
        const previewsEl = document.getElementById('uploadPreviews');
        const countEl    = document.getElementById('uploadCount');
        const saveBtn    = document.getElementById('modalSaveBtn');
        previewsEl.innerHTML = '';

        const readers = files.filter(f => f.size <= 5 * 1024 * 1024).map(file => {
            return new Promise(resolve => {
                const reader = new FileReader();
                reader.onload = e => {
                    pendingGalleryFiles.push(e.target.result);
                    previewsEl.innerHTML += `<div style="aspect-ratio:1;border-radius:10px;overflow:hidden;background:var(--bg-app);border:1.5px solid var(--border-light);"><img data-base64="${pendingGalleryFiles.length - 1}" src="" style="width:100%;height:100%;object-fit:cover;display:block;"></div>`;
                    resolve(e.target.result);
                };
                reader.readAsDataURL(file);
            });
        });

        Promise.all(readers).then(results => {
            // Assign src after all are loaded
            previewsEl.querySelectorAll('img[data-base64]').forEach((img, i) => {
                img.src = pendingGalleryFiles[i] || '';
            });
            countEl.textContent = pendingGalleryFiles.length;
            saveBtn.disabled = pendingGalleryFiles.length === 0;
        });

        if (files.some(f => f.size > 5 * 1024 * 1024)) {
            showToast('Certaines images dépassent 5 Mo et ont été ignorées', 'error');
        }
    }

    async function load() {
        gallery = await api('GET', '/admin/api/gallery');
        renderGallery();
    }

    load();
</script>
@endsection
