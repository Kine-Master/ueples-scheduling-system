/* frontend/teacher/dashboard/script.js */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Set Date immediately
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').innerText = now.toLocaleDateString('en-US', options);
    
    // 2. Load Profile (For Name) & Stats
    loadDashboardData();
    loadProfileName();
});

function loadProfileName() {
    // Re-use the existing user profile endpoint
    fetch('../../../backend/user/get_profile.php')
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('teacherName').innerText = data.data.first_name;
            }
        });
}

function loadDashboardData() {
    const tableBody = document.getElementById('todayTableBody');

    fetch('../../../backend/dashboard/stats.php')
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const data = res.data;
                const stats = data.stats;

                // 1. Populate Cards
                document.getElementById('totalSubjects').innerText = stats.total_subjects;
                document.getElementById('todayCount').innerText = stats.today_count;
                document.getElementById('currentDay').innerText = data.current_day; // e.g. "Monday"

                // 2. Populate "Today's Schedule" Table
                if (stats.todays_schedule.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px; color:#999;">
                                <i class="fa-solid fa-mug-hot" style="font-size:24px; margin-bottom:10px;"></i><br>
                                No classes scheduled for today.
                            </td>
                        </tr>
                    `;
                } else {
                    tableBody.innerHTML = stats.todays_schedule.map(sched => {
                        const isCoed = sched.schedule_type === 'COED';
                        const badgeClass = isCoed ? 'coed' : 'les';
                        const timeStr = formatTime(sched.time_in) + ' - ' + formatTime(sched.time_out);
                        const status = getStatus(sched.time_in, sched.time_out);

                        return `
                            <tr>
                                <td style="font-weight:bold; color:#555;">${timeStr}</td>
                                <td>${escapeHtml(sched.subject)}</td>
                                <td>${escapeHtml(sched.room)}</td>
                                <td><span class="badge-pill ${badgeClass}">${sched.schedule_type}</span></td>
                                <td>${status}</td>
                            </tr>
                        `;
                    }).join('');
                }
            } else {
                console.error("Dashboard Load Error:", res.message);
            }
        })
        .catch(err => {
            console.error(err);
            tableBody.innerHTML = '<tr><td colspan="5" style="color:red; text-align:center;">Network Error loading dashboard.</td></tr>';
        });
}

// UTILITIES
function formatTime(timeStr) {
    if (!timeStr) return '';
    const [h, m] = timeStr.split(':');
    let hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12;
    return `${hour}:${m} ${ampm}`;
}

function getStatus(start, end) {
    // Simple visual helper to see if class is upcoming or done
    const now = new Date();
    const currentMins = now.getHours() * 60 + now.getMinutes();
    
    const [sH, sM] = start.split(':').map(Number);
    const [eH, eM] = end.split(':').map(Number);
    const startMins = sH * 60 + sM;
    const endMins = eH * 60 + eM;

    if (currentMins > endMins) {
        return '<span style="color:#aaa;"><span class="status-indicator status-done"></span> Done</span>';
    } else if (currentMins >= startMins && currentMins <= endMins) {
        return '<span style="color:#2ecc71; font-weight:bold;"><span class="status-indicator" style="background:#2ecc71;"></span> Ongoing</span>';
    } else {
        return '<span style="color:#3498db;"><span class="status-indicator status-upcoming"></span> Upcoming</span>';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString().replace(/[&<>"']/g, function(m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
}