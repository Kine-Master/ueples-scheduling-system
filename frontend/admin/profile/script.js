// frontend/admin/profile/script.js

function showToast(msg, type = 'info') {
    const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.innerHTML = `<i class="fa-solid ${icons[type]}"></i><span>${msg}</span>`;
    document.getElementById('toastContainer').appendChild(el);
    setTimeout(() => el.remove(), 4500);
}

async function loadProfile() {
    try {
        const res = await fetch('../../../backend/user/get_profile.php');
        const json = await res.json();
        if (json.status !== 'success') throw new Error(json.message);
        const u = json.data;
        document.getElementById('profileFullName').textContent = `${u.first_name} ${u.last_name}`;
        document.getElementById('pLastName').value = u.last_name;
        document.getElementById('pFirstName').value = u.first_name;
        document.getElementById('pMiddleName').value = u.middle_name || '';
        document.getElementById('pEmail').value = u.email || '';
        document.getElementById('pRank').value = u.academic_rank || '';
        document.getElementById('pSchool').value = u.school_college || '';
        document.getElementById('pDept').value = u.department || '';
    } catch (e) { showToast('Error loading profile: ' + e.message, 'error'); }
}

async function saveProfile(e) {
    e.preventDefault();
    const body = new FormData();
    body.append('first_name', document.getElementById('pFirstName').value.trim());
    body.append('middle_name', document.getElementById('pMiddleName').value.trim());
    body.append('last_name', document.getElementById('pLastName').value.trim());
    body.append('email', document.getElementById('pEmail').value.trim());
    body.append('academic_rank', document.getElementById('pRank').value.trim());
    body.append('school_college', document.getElementById('pSchool').value.trim());
    body.append('department', document.getElementById('pDept').value.trim());
    try {
        const res = await fetch('../../../backend/user/update_profile.php', { method: 'POST', body });
        const json = await res.json();
        if (json.status !== 'success') throw new Error(json.message);
        document.getElementById('profileFullName').textContent = `${body.get('first_name')} ${body.get('last_name')}`;
        showToast('Profile updated.', 'success');
    } catch (e) { showToast('Error: ' + e.message, 'error'); }
}

async function changePassword(e) {
    e.preventDefault();
    const newPass = document.getElementById('cNewPass').value;
    const confirmPass = document.getElementById('cConfirmPass').value;
    if (newPass !== confirmPass) { showToast('Passwords do not match.', 'warning'); return; }
    if (newPass.length < 6) { showToast('Password must be at least 6 characters.', 'warning'); return; }
    const body = new FormData();
    body.append('current_password', document.getElementById('cCurrentPass').value);
    body.append('new_password', newPass);
    body.append('confirm_password', confirmPass);
    try {
        const res = await fetch('../../../backend/user/change_password.php', { method: 'POST', body });
        const json = await res.json();
        if (json.status !== 'success') throw new Error(json.message);
        document.getElementById('passwordForm').reset();
        showToast('Password changed successfully.', 'success');
    } catch (e) { showToast('Error: ' + e.message, 'error'); }
}

loadProfile();
