/**
 * TEACHER WORKLOAD SCRIPT (Full CRUD with Multi-day Support)
 */

let currentView = 'grid'; 
const START_HOUR = 6;

document.addEventListener('DOMContentLoaded', () => {
    reloadData();
    setupFormSubmission();
});

// ==========================================
// 1. VIEW & DATA FETCHING
// ==========================================

function switchView(view) {
    currentView = view;
    
    // UI Toggles
    document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.toggle-btn[onclick="switchView('${view}')"]`).classList.add('active');

    document.getElementById('viewGrid').style.display = view === 'grid' ? 'block' : 'none';
    document.getElementById('viewList').style.display = view === 'list' ? 'block' : 'none';

    document.getElementById('gridControls').style.display = view === 'grid' ? 'block' : 'none';
    document.getElementById('gridLegend').style.display = view === 'grid' ? 'flex' : 'none';
    document.getElementById('listControls').style.display = view === 'list' ? 'block' : 'none';

    reloadData();
}

function reloadData() {
    const myId = document.getElementById('myUserId').value;
    const sem = document.getElementById('filterSemester').value;
    const sort = document.getElementById('sortOption').value;

    let url = `../../../backend/schedule/list_by_teacher.php?teacher_id=${myId}`;

    if (currentView === 'grid') {
        if (sem) url += `&semester=${sem}`;
    } else {
        url += `&sort_by=${sort}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const schedules = data.schedules || [];
                if (schedules.length > 0) {
                    document.getElementById('displaySY').innerText = schedules[0].school_year;
                }
                
                if (currentView === 'grid') {
                    renderGrid(schedules);
                } else {
                    renderList(schedules);
                }
            }
        });
}

// ==========================================
// 2. GRID RENDERING (UPDATED TO MATCH SECRETARY)
// ==========================================

function renderGrid(schedules) {
    const grid = document.getElementById('gridContent');
    grid.innerHTML = '';
    
    // Draw Headers & Grid Lines (6 days + Saturday, 13 hours = 26 slots)
    ['Time', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'].forEach(h => {
        const d = document.createElement('div');
        d.className = 'grid-header-cell';
        d.innerText = h;
        grid.appendChild(d);
    });

    for(let i = 0; i < 26; i++) { // 13 hours * 2 (30-min slots)
        const row = i + 2;
        const line = document.createElement('div');
        line.className = (i % 2 === 1) ? 'grid-bg-line hour-marker' : 'grid-bg-line';
        line.style.gridColumn = '1/-1';
        line.style.gridRow = row;
        grid.appendChild(line);

        if(i % 2 === 0) {
            const time = document.createElement('div');
            time.className = 'time-label';
            time.innerText = formatHour(START_HOUR + i/2);
            time.style.gridRow = row;
            time.style.gridColumn = 1;
            grid.appendChild(time);
        }
    }

    // Place schedule blocks
    schedules.forEach(s => {
        const dayMap = {'Monday':2, 'Tuesday':3, 'Wednesday':4, 'Thursday':5, 'Friday':6, 'Saturday':7};
        const col = dayMap[s.day_of_week];
        if(!col) return;
        
        const start = ((parseInt(s.time_in.split(':')[0]) - START_HOUR) * 2) + (parseInt(s.time_in.split(':')[1]) >= 30 ? 1 : 0) + 2;
        const end = ((parseInt(s.time_out.split(':')[0]) - START_HOUR) * 2) + (parseInt(s.time_out.split(':')[1]) >= 30 ? 1 : 0) + 2;
        const duration = end - start;

        if(start >= 2 && duration > 0) {
            const el = document.createElement('div');
            const isCoed = s.schedule_type === 'COED';
            el.className = `schedule-block ${isCoed ? 'block-coed' : 'block-les'}`;
            el.style.gridColumn = col;
            el.style.gridRow = `${start} / span ${duration}`;
            
            const lockIcon = !isCoed ? '<i class="fa-solid fa-lock" style="float:right; font-size:10px;"></i>' : '';
            el.innerHTML = `<strong>${escapeHtml(s.subject)}</strong> ${lockIcon}<br><small>${escapeHtml(s.room)}</small>`;
            
            if(isCoed) {
                el.style.cursor = 'pointer';
                el.onclick = () => openEditModal(s);
            } else {
                el.style.cursor = 'not-allowed';
                el.onclick = () => alert('LOCKED: This is an LES schedule assigned by the Secretary.');
            }
            grid.appendChild(el);
        }
    });
}

// ==========================================
// 3. LIST RENDERING
// ==========================================

function renderList(schedules) {
    const tbody = document.getElementById('listContent');
    if (schedules.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px;">No schedules found.</td></tr>';
        return;
    }

    tbody.innerHTML = schedules.map(s => {
        const isCoed = s.schedule_type === 'COED';
        const json = escapeHtml(JSON.stringify(s));
        const actions = isCoed 
            ? `<button onclick='triggerEdit(${json})' class="btn-edit-icon"><i class="fa-solid fa-pen"></i></button>`
            : `<i class="fa-solid fa-lock" style="color:#aaa;"></i>`;

        return `
            <tr>
                <td><strong>${escapeHtml(s.subject)}</strong></td>
                <td><span class="badge ${isCoed ? 'badge-coed' : 'badge-les'}">${s.schedule_type}</span></td>
                <td>${s.day_of_week}<br><small>${formatTime(s.time_in)} - ${formatTime(s.time_out)}</small></td>
                <td>${escapeHtml(s.room)}</td>
                <td>${s.semester}</td>
                <td>${actions}</td>
            </tr>
        `;
    }).join('');
}

// ==========================================
// 4. CRUD LOGIC (UPDATED TO MATCH SECRETARY)
// ==========================================

function openAddModal() {
    document.getElementById('modalTitle').innerText = 'Add COED Schedule';
    document.getElementById('scheduleForm').reset();
    document.getElementById('schedule_id').value = '';
    document.getElementById('form_school_year').value = calculateSY();
    
    // Uncheck and enable all days
    document.querySelectorAll('input[name="days[]"]').forEach(cb => {
        cb.checked = false;
        cb.disabled = false;
    });
    
    document.getElementById('btnDelete').style.display = 'none';
    document.getElementById('scheduleModal').style.display = 'block';
}

function openEditModal(s) {
    document.getElementById('modalTitle').innerText = 'Edit COED Schedule';
    document.getElementById('schedule_id').value = s.schedule_id;
    
    ['subject','units','class_type','time_in','time_out','room','course_year','school_year','semester'].forEach(f => {
        const el = document.querySelector(`[name="${f}"]`);
        if(el) el.value = s[f];
    });

    // Check only the specific day, disable others
    document.querySelectorAll('input[name="days[]"]').forEach(cb => {
        cb.checked = (cb.value === s.day_of_week);
        cb.disabled = !cb.checked;
    });

    document.getElementById('btnDelete').style.display = 'inline-block';
    document.getElementById('scheduleModal').style.display = 'block';
}

function setupFormSubmission() {
    const form = document.getElementById('scheduleForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('schedule_id').value;
        const formData = new FormData(this);
        const days = [];
        document.querySelectorAll('input[name="days[]"]:checked').forEach(cb => days.push(cb.value));

        if(days.length === 0) return alert("Select at least one day.");
        
        const btn = document.getElementById('btnSave');
        const txt = btn.innerText;
        btn.innerText = "Saving...";
        btn.disabled = true;

        if(id) {
            // UPDATE: Single schedule
            formData.set('day_of_week', days[0]);
            fetch('../../../backend/schedule/update.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(d => {
                    if(d.status === 'success') {
                        closeModal();
                        reloadData();
                    } else {
                        alert(d.message);
                    }
                })
                .finally(() => {
                    btn.innerText = txt;
                    btn.disabled = false;
                });
        } else {
            // CREATE: Loop for multiple days
            const promises = days.map(day => {
                const dData = new FormData(form);
                dData.set('day_of_week', day);
                return fetch('../../../backend/schedule/create.php', { method:'POST', body:dData }).then(r => r.json());
            });
            
            Promise.all(promises).then(results => {
                const errs = results.filter(r => r.status !== 'success');
                if(errs.length > 0) {
                    alert("Errors:\n" + errs.map(e => e.message).join('\n'));
                } else {
                    closeModal();
                    reloadData();
                }
            }).finally(() => {
                btn.innerText = txt;
                btn.disabled = false;
            });
        }
    });
}

function deleteSchedule() {
    if(!confirm("Delete this schedule?")) return;
    const fd = new FormData();
    fd.append('schedule_id', document.getElementById('schedule_id').value);
    fetch('../../../backend/schedule/delete.php', { method:'POST', body:fd })
        .then(r => r.json())
        .then(d => {
            if(d.status === 'success') {
                closeModal();
                reloadData();
            } else {
                alert(d.message);
            }
        });
}

// ==========================================
// 5. UTILITIES
// ==========================================

function closeModal() {
    document.getElementById('scheduleModal').style.display = 'none';
}

function triggerEdit(json) {
    openEditModal(JSON.parse(json));
}

function calculateSY() {
    const y = new Date().getFullYear();
    return (new Date().getMonth() < 5) ? `${y-1}-${y}` : `${y}-${y+1}`;
}

function formatHour(h) {
    return (h > 12 ? h - 12 : h) + ":00 " + (h >= 12 ? "PM" : "AM");
}

function formatTime(t) {
    const [h, m] = t.split(':');
    return parseInt(h) + ":" + m;
}

function escapeHtml(t) {
    return t ? t.toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','\'':'&#039;','\"':'&quot;'}[m])) : '';
}

function printReport() {
    const myId = document.getElementById('myUserId').value;
    window.open(`report.php?teacher_id=${myId}`, '_blank');
}