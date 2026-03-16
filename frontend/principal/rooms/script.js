// frontend/principal/rooms/script.js

async function loadDropdowns() {
    try {
        const res = await fetch('../../../backend/master_data/building/list.php');
        const json = await res.json();
        const sel = document.getElementById('fBldg');
        json.data.forEach(b => {
            sel.innerHTML += `<option value="${b.building_id}">${b.building_name}</option>`;
        });
    } catch (e) { console.error(e); }

    loadRooms();
}

async function loadRooms() {
    const bId = document.getElementById('fBldg').value;
    const wrap = document.getElementById('roomWrap');
    const loading = document.getElementById('loadingWrap');

    wrap.style.display = 'none';
    loading.style.display = 'block';

    try {
        const res = await fetch('../../../backend/master_data/room/list.php');
        const json = await res.json();
        if (json.status !== 'success') throw new Error(json.message);

        let rooms = json.data;
        if (bId) rooms = rooms.filter(r => r.building_id == bId);

        if (!rooms.length) {
            wrap.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted)">No rooms found for this building.</div>`;
        } else {
            wrap.innerHTML = rooms.map(r => {
                const badge = r.is_active ? '<span class="badge badge-active" style="font-size:.7rem">Active</span>' : '<span class="badge badge-inactive" style="font-size:.7rem">Inactive</span>';
                return `
          <div class="room-card">
            <div class="room-header">
               <div class="room-title"><i class="fa-solid fa-door-open" style="color:var(--accent)"></i> ${r.room_name}</div>
               ${badge}
            </div>
            <div class="room-body">
               <div style="margin-bottom:8px"><strong>Building:</strong> <span style="color:var(--text)">${r.building_name}</span></div>
               <div><strong>Capacity:</strong> <span style="color:var(--text)">${r.capacity} seats</span></div>
               <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                 <span style="font-size:.8rem;color:var(--text-muted)"><i class="fa-solid fa-info-circle"></i> View schedule via Secretary portal</span>
               </div>
            </div>
          </div>
        `;
            }).join('');
        }

        loading.style.display = 'none';
        wrap.style.display = 'grid';

    } catch (e) {
        loading.innerHTML = `<i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;font-size:2rem;margin-bottom:12px"></i><p style="color:#ef4444">Failed to load rooms.</p>`;
    }
}

document.addEventListener('DOMContentLoaded', loadDropdowns);
