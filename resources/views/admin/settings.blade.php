@extends('admin.layout')
@section('title', 'Paramètres')
@section('subtitle', 'Gérez les identifiants de connexion admin')

@section('content')
<div style="max-width:520px;">

    <div class="card" style="padding:32px;">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:28px;padding-bottom:20px;border-bottom:1px solid var(--border-light);">
            <div style="width:44px;height:44px;border-radius:12px;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1.1rem;">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <div style="font-weight:700;font-size:1rem;color:var(--text-primary);">Identifiants de connexion</div>
                <div style="font-size:0.78rem;color:var(--text-muted);">Modifiez l'email et le mot de passe admin</div>
            </div>
        </div>

        <!-- Email -->
        <div class="field-group" style="margin-bottom:18px;">
            <label class="field-label"><i class="fas fa-envelope"></i> Adresse email</label>
            <input id="sEmail" type="email" class="field-input" value="" placeholder="admin@wibc.ci">
        </div>

        <!-- Nouveau mot de passe -->
        <div class="field-group" style="margin-bottom:18px;">
            <label class="field-label"><i class="fas fa-key"></i> Nouveau mot de passe <span style="color:var(--text-muted);font-weight:400;">(laisser vide pour ne pas changer)</span></label>
            <div style="position:relative;">
                <input id="sNewPw" type="password" class="field-input" placeholder="••••••••" style="padding-right:44px;">
                <button type="button" onclick="togglePw('sNewPw','icon1')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                    <i class="fas fa-eye" id="icon1"></i>
                </button>
            </div>
        </div>

        <!-- Confirmer mot de passe -->
        <div class="field-group" style="margin-bottom:28px;">
            <label class="field-label"><i class="fas fa-check-circle"></i> Confirmer le mot de passe</label>
            <div style="position:relative;">
                <input id="sConfirmPw" type="password" class="field-input" placeholder="••••••••" style="padding-right:44px;">
                <button type="button" onclick="togglePw('sConfirmPw','icon2')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                    <i class="fas fa-eye" id="icon2"></i>
                </button>
            </div>
        </div>

        <button id="saveBtn" class="btn-primary" style="width:100%;justify-content:center;" onclick="saveCredentials()">
            <i class="fas fa-save"></i> Enregistrer les modifications
        </button>
    </div>

</div>
@endsection

@section('scripts')
<script>
    async function loadCredentials() {
        try {
            const data = await api('GET', '/admin/api/settings/credentials');
            document.getElementById('sEmail').value = data.email || '';
        } catch(e) {}
    }

    async function saveCredentials() {
        const email     = document.getElementById('sEmail').value.trim();
        const newPw     = document.getElementById('sNewPw').value;
        const confirmPw = document.getElementById('sConfirmPw').value;

        if (!email) { showToast('Email requis', 'error'); return; }
        if (newPw && newPw !== confirmPw) { showToast('Les mots de passe ne correspondent pas', 'error'); return; }
        if (newPw && newPw.length < 6) { showToast('Mot de passe trop court (min 6 caractères)', 'error'); return; }

        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        try {
            await api('POST', '/admin/api/settings/credentials', {
                email:  email,
                new_pw: newPw || null,
            });
            showToast('Identifiants mis à jour avec succès');
            document.getElementById('sNewPw').value = '';
            document.getElementById('sConfirmPw').value = '';
        } catch(e) {
            showToast(e.message, 'error');
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Enregistrer les modifications';
    }

    function togglePw(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    loadCredentials();
</script>
@endsection
