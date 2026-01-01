/* frontend/secretary/dashboard/script.js */

document.addEventListener('DOMContentLoaded', () => {
    // Load Profile & Dashboard Stats
    loadProfileName();
    loadDashboardData();
});

function loadProfileName() {
    // Load secretary's name from profile
    fetch('../../../backend/user/get_profile.php')
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                const nameElement = document.getElementById('secretaryName');
                if (nameElement) {
                    nameElement.innerText = data.data.first_name;
                }
            }
        })
        .catch(err => console.error('Error loading profile:', err));
}

function loadDashboardData() {
    const tableBody = document.getElementById('recentSchedulesBody');

    fetch('../../../backend/dashboard/stats.php')
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const data = res.data;
                const stats = data.stats;

                // 1. Populate Stats Cards
                // Backend provides: total_faculty, total_classes
                document.getElementById('totalFaculty').innerText = stats.total_faculty;
                document.getElementById('totalClasses').innerText = stats.total_classes;

                // 2. Populate "Recent Schedules" Table
                // Backend provides: subject, date_created, schedule_type, last_name, first_name
                if (stats.recent_schedules.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" style="text-align:center; padding:30px; color:#999;">
                                <i class="fa-solid fa-inbox" style="font-size:24px; margin-bottom:10px;"></i><br>
                                No recent schedules found.
                            </td>
                        </tr>
                    `;
                } else {
                    tableBody.innerHTML = stats.recent_schedules.map(sched => {
                        const teacherName = `${sched.first_name} ${sched.last_name}`;
                        const isCoed = sched.schedule_type === 'COED';
                        const badgeClass = isCoed ? 'coed' : 'les';
                        const createdDate = formatDateTime(sched.date_created);

                        return `
                            <tr onclick="window.location.href='../workloads/index.php'" style="cursor:pointer;" title="Go to Workloads">
                                <td><strong>${escapeHtml(sched.subject)}</strong></td>
                                <td>${escapeHtml(teacherName)}</td>
                                <td><span class="badge-pill ${badgeClass}">${sched.schedule_type}</span></td>
                                <td style="color:#666; font-size:13px;">${createdDate}</td>
                            </tr>
                        `;
                    }).join('');
                }
            } else {
                console.error("Dashboard Load Error:", res.message);
                tableBody.innerHTML = `<tr><td colspan="4" style="color:red; text-align:center;">Error: ${escapeHtml(res.message)}</td></tr>`;
            }
        })
        .catch(err => {
            console.error(err);
            tableBody.innerHTML = '<tr><td colspan="4" style="color:red; text-align:center;">Network Error loading dashboard.</td></tr>';
        });
}

// UTILITIES
function formatDateTime(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
    };
    return date.toLocaleDateString('en-US', options);
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString().replace(/[&<>"']/g, function(m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
}