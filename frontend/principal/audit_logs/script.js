/* frontend/principal/audit_logs/script.js */

let currentPage = 1;
let debounceTimer;

document.addEventListener('DOMContentLoaded', () => {
    loadLogs();
    loadActionsDropdown();
    loadSettings();
});

// --- 1. DATA LOADING ---
function loadLogs() {
    const search = document.getElementById('searchInput').value;
    const action = document.getElementById('actionFilter').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;

    const tbody = document.getElementById('auditTableBody');
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Loading logs...</td></tr>';

    const url = `../../../backend/audit_logs/list.php?page=${currentPage}&search=${encodeURIComponent(search)}&action=${encodeURIComponent(action)}&start_date=${start}&end_date=${end}`;

    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                renderTable(res.data);
            } else {
                tbody.innerHTML = '<tr><td colspan="6" style="color:red; text-align:center;">Error loading data</td></tr>';
            }
        })
        .catch(err => console.error(err));
}

function renderTable(rows) {
    const tbody = document.getElementById('auditTableBody');
    if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px;">No logs found.</td></tr>';
        return;
    }

    tbody.innerHTML = rows.map(r => `
        <tr style="border-bottom:1px solid #eee;">
            <td>#${r.log_id}</td>
            <td>
                <strong>${r.last_name || 'System'}, ${r.first_name || 'Admin'}</strong><br>
                <small style="color:#888;">${r.username || 'N/A'}</small>
            </td>
            <td>${getRoleName(r.role_id)}</td>
            <td>
                <span style="font-weight:bold; color:#333;">${r.user_action}</span>
            </td>
            <td>${r.ip_address || '::1'}</td>
            <td>${new Date(r.timestamp).toLocaleString()}</td>
        </tr>
    `).join('');

    // Update Page Indicator
    document.getElementById('pageIndicator').innerText = `Page ${currentPage}`;
    document.getElementById('btnPrev').disabled = currentPage === 1;
    document.getElementById('btnNext').disabled = rows.length < 50; // Assuming limit is 50
}

function loadActionsDropdown() {
    fetch('../../../backend/audit_logs/get_actions.php')
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const select = document.getElementById('actionFilter');
                res.data.forEach(action => {
                    const opt = document.createElement('option');
                    opt.value = action;
                    opt.innerText = action;
                    select.appendChild(opt);
                });
            }
        });
}

// --- 2. SETTINGS LOGIC ---
function loadSettings() {
    fetch('../../../backend/audit_logs/settings.php')
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                document.getElementById('retentionMonths').value = res.threshold;
            }
        });
}

function openSettingsModal() {
    document.getElementById('settingsModal').style.display = 'block';
}

function closeSettingsModal() {
    document.getElementById('settingsModal').style.display = 'none';
}


window.onclick = function(event) {
    const modal = document.getElementById('settingsModal');
    if (event.target == modal) {
        closeSettingsModal();
    }
}

function saveSettings() {
    const val = document.getElementById('retentionMonths').value;
    const fd = new FormData();
    fd.append('threshold', val);

    fetch('../../../backend/audit/settings.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(res => {
            alert(res.message);
            closeSettingsModal();
        });
}

function runCleanup() {
    if (!confirm("Are you sure? This will PERMANENTLY delete old logs.")) return;

    fetch('../../../backend/audit/run_cleanup.php', { method: 'POST' })
        .then(res => res.json())
        .then(res => {
            alert(res.message);
            loadLogs();
            closeSettingsModal();
        });
}

// --- 3. UTILITIES ---
function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        currentPage = 1;
        loadLogs();
    }, 500);
}

function changePage(dir) {
    if (dir === -1 && currentPage > 1) {
        currentPage--;
        loadLogs();
    } else if (dir === 1) {
        currentPage++;
        loadLogs();
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('actionFilter').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    currentPage = 1;
    loadLogs();
}

function generateReport() {
    const search = document.getElementById('searchInput').value;
    const action = document.getElementById('actionFilter').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    
    // Open the Report PHP file with current filters
    const url = `report.php?search=${encodeURIComponent(search)}&action=${encodeURIComponent(action)}&start_date=${start}&end_date=${end}`;
    window.open(url, '_blank');
}

function getRoleName(id) {
    if (id == 1) return '<span style="color:red">Principal</span>';
    if (id == 2) return '<span style="color:blue">Secretary</span>';
    if (id == 3) return '<span style="color:green">Teacher</span>';
    return 'Unknown';
}