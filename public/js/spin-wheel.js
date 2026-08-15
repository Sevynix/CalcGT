const spinOneBtn = document.getElementById('btn-spin-one');
const spinAllBtn = document.getElementById('btn-spin-all');
const poolCount  = document.getElementById('pool-count');
const selCount   = document.getElementById('selected-count');
const selList    = document.getElementById('selected-list');
const toast      = document.getElementById('drawn-toast');
const drawnName  = document.getElementById('drawn-name');

let spinning  = false;
let currentRotation = 0;

// Build wheel
function buildWheel(names) {
    const svg = document.getElementById('wheel-slices');
    if (!svg) return;
    
    svg.innerHTML = '';
    if (names.length === 0) {
        // Empty state
        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('r', '180');
        circle.setAttribute('fill', '#1a1e2e');
        circle.setAttribute('stroke', '#252b40');
        circle.setAttribute('stroke-width', '2');
        svg.appendChild(circle);
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        text.setAttribute('text-anchor', 'middle');
        text.setAttribute('dy', '8px');
        text.setAttribute('fill', '#7b82a0');
        text.setAttribute('font-size', '16');
        text.textContent = 'Pool kosong';
        svg.appendChild(text);
        return;
    }

    const n       = names.length;
    const angle   = (2 * Math.PI) / n;
    const r       = 180;
    const colors  = ['#6c63ff','#00d4aa','#ff4757','#ffa502','#2ed573','#a855f7','#ec4899','#f97316','#3b82f6','#14b8a6'];

    names.forEach((name, i) => {
        const startAngle = i * angle - Math.PI / 2;
        const endAngle   = startAngle + angle;
        const midAngle   = startAngle + angle / 2;

        const x1 = Math.cos(startAngle) * r;
        const y1 = Math.sin(startAngle) * r;
        const x2 = Math.cos(endAngle)   * r;
        const y2 = Math.sin(endAngle)   * r;

        const largeArc = angle > Math.PI ? 1 : 0;
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        const d = n === 1
            ? `M 0,0 m -${r},0 a ${r},${r} 0 1,0 ${r*2},0 a ${r},${r} 0 1,0 -${r*2},0`
            : `M 0,0 L ${x1},${y1} A ${r},${r} 0 ${largeArc},1 ${x2},${y2} Z`;
        path.setAttribute('d', d);
        path.setAttribute('fill', colors[i % colors.length]);
        path.setAttribute('stroke', '#0a0c10');
        path.setAttribute('stroke-width', '2');
        svg.appendChild(path);

        // Label
        const textR  = r * 0.62;
        const tx     = Math.cos(midAngle) * textR;
        const ty     = Math.sin(midAngle) * textR;
        const textEl = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        textEl.setAttribute('x', tx);
        textEl.setAttribute('y', ty);
        textEl.setAttribute('text-anchor', 'middle');
        textEl.setAttribute('dominant-baseline', 'middle');
        textEl.setAttribute('transform', `rotate(${(midAngle * 180 / Math.PI)}, ${tx}, ${ty})`);
        textEl.setAttribute('fill', '#fff');
        textEl.setAttribute('font-size', Math.max(8, Math.min(13, 160 / n)));
        textEl.setAttribute('font-weight', '700');
        textEl.setAttribute('font-family', 'Inter, sans-serif');
        // Truncate name
        const maxChars = Math.max(4, Math.floor(22 / Math.sqrt(n)));
        textEl.textContent = name.length > maxChars ? name.substring(0, maxChars) + '…' : name;
        svg.appendChild(textEl);
    });
}

function showToast(name) {
    if(!drawnName || !toast) return;
    drawnName.textContent = name;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

function addToSelected(name) {
    if(!selList) return;
    const num = window.spinSelected.length;
    const item = document.createElement('div');
    item.className = 'selected-item bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/60 rounded-xl p-3 flex items-center justify-between gap-3';
    item.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-black text-[11px] flex items-center justify-center border border-blue-200 dark:border-blue-700/50 shrink-0">${num}</div>
            <span class="font-bold text-slate-800 dark:text-slate-100 text-sm break-all">${name}</span>
        </div>
        <button class="text-slate-400 hover:text-red-500 font-bold text-xs p-1 transition-colors cursor-pointer" onclick="removeSelectedName('${name.replace(/'/g, "\\'")}')" title="Hapus">✕</button>
    `;
    selList.appendChild(item);
}

function updateCounts() {
    if(poolCount) poolCount.textContent = window.spinPool.length;
    if(selCount) selCount.textContent  = window.spinSelected.length;
}

async function doSpin() {
    if (spinning || window.spinSelected.length >= window.spinSlotTotal || window.spinPool.length === 0) return;
    spinning = true;
    if(spinOneBtn) spinOneBtn.disabled = true;
    if(spinAllBtn) spinAllBtn.disabled = true;

    // Animate wheel
    const wheel = document.getElementById('spin-wheel');
    const extraSpins = 5 + Math.random() * 5;
    const randomStop = Math.random() * 360;
    currentRotation += extraSpins * 360 + randomStop;
    wheel.style.transition = 'transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99)';
    wheel.style.transform  = `rotate(${currentRotation}deg)`;

    // Call API first, THEN wait for animation
    let data = null;
    let ok   = false;
    try {
        const resp = await fetch(window.spinDrawUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        });
        data = await resp.json();
        ok   = resp.ok;
    } catch (e) {
        console.error('Spin error:', e);
    }

    // Wait for animation to finish
    await new Promise(r => setTimeout(r, 4200));

    if (ok && data) {
        // Show modal!
        drawnName.textContent = data.drawn;
        toast.classList.add('show');
        window.currentPendingDraw = data.drawn;
    } else if (data?.error) {
        showAlert('Error: ' + data.error);
        if(spinOneBtn) spinOneBtn.disabled = false;
        if(spinAllBtn) spinAllBtn.disabled = false;
        spinning = false;
    }
}

// Global functions for modal and manual
window.cancelSpin = function() {
    toast.classList.remove('show');
    window.currentPendingDraw = null;
    
    // Reset spin buttons
    spinning = false;
    if(spinOneBtn) spinOneBtn.disabled = false;
    if(spinAllBtn) spinAllBtn.disabled = false;
};

window.acceptSpin = async function() {
    if (!window.currentPendingDraw) return;
    const name = window.currentPendingDraw;
    
    try {
        const resp = await fetch(window.spinAcceptUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ name })
        });
        const data = await resp.json();
        
        if (resp.ok) {
            toast.classList.remove('show');
            window.currentPendingDraw = null;
            
            updateStateFromData(data);
            
            if (data.is_full) {
                location.reload();
                return;
            }
        } else {
            showAlert(data.error || 'Gagal menerima spin');
        }
    } catch(e) {
        console.error(e);
        showAlert('Terjadi kesalahan jaringan.');
    }
    
    spinning = false;
    if(spinOneBtn) spinOneBtn.disabled = false;
    if(spinAllBtn) spinAllBtn.disabled = false;
};

window.addManualName = async function() {
    const input = document.getElementById('manual-name');
    if(!input) return;
    const name = input.value.trim();
    if(!name) return;
    
    try {
        const resp = await fetch(window.spinAddManualUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ name })
        });
        const data = await resp.json();
        
        if (resp.ok) {
            input.value = '';
            updateStateFromData(data);
            
            if (data.is_full) {
                location.reload();
            }
        } else {
            showAlert(data.error || 'Gagal menambahkan peserta');
        }
    } catch(e) {
        console.error(e);
    }
};

window.removeSelectedName = async function(name) {
    const performRemove = async () => {
        try {
            const resp = await fetch(window.spinRemoveUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name })
            });
            const data = await resp.json();
            
            if (resp.ok) {
                updateStateFromData(data);
                
                // Allow spin again if it was full
                if(spinOneBtn) spinOneBtn.disabled = false;
                if(spinAllBtn) spinAllBtn.disabled = false;
            } else {
                showAlert(data.error || 'Gagal menghapus peserta');
            }
        } catch(e) {
            console.error(e);
        }
    };

    if (window.Alpine && window.Alpine.store('modal')) {
        window.Alpine.store('modal').show(
            `Hapus ${name} dari daftar terpilih?`, 
            performRemove, 
            'Konfirmasi', 
            'Ya, Hapus', 
            'danger'
        );
    } else {
        if(!confirm(`Hapus ${name} dari daftar terpilih?`)) return;
        performRemove();
    }
};

function updateStateFromData(data) {
    window.spinPool     = data.pool     ?? window.spinPool;
    window.spinSelected = data.selected ?? window.spinSelected;
    
    // Rebuild pool wheel
    buildWheel(window.spinPool);
    
    // Rebuild selected list
    if (selList) {
        selList.innerHTML = '';
        window.spinSelected.forEach((name, i) => {
            const item = document.createElement('div');
            item.className = 'selected-item bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/60 rounded-xl p-3 flex items-center justify-between gap-3';
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-black text-[11px] flex items-center justify-center border border-blue-200 dark:border-blue-700/50 shrink-0">${i + 1}</div>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-sm break-all">${name}</span>
                </div>
                <button class="text-slate-400 hover:text-red-500 font-bold text-xs p-1 transition-colors cursor-pointer" onclick="removeSelectedName('${name.replace(/'/g, "\\'")}')" title="Hapus">✕</button>
            `;
            selList.appendChild(item);
        });
    }
    
    // Update count titles
    const selCountTitle = document.getElementById('selected-count-title');
    if (selCountTitle) selCountTitle.textContent = window.spinSelected.length;
    updateCounts();
    
    // Handle empty pool state
    if (window.spinPool.length === 0) {
            if(spinOneBtn) spinOneBtn.disabled = false;
            if(spinAllBtn) spinAllBtn.disabled = false;
        } else if (window.spinPool.length === 0) {
            if(spinOneBtn) spinOneBtn.disabled = true;
            if(spinAllBtn) spinAllBtn.disabled = true;
            
            if (!document.getElementById('pool-empty-msg')) {
                const msg = document.createElement('div');
                msg.id = 'pool-empty-msg';
                msg.style.cssText = 'background:rgba(255,165,2,.1);border:1px solid rgba(255,165,2,.3);color:#ffa502;padding:10px 14px;border-radius:8px;font-size:13px;margin-top:8px;text-align:center;';
                msg.innerHTML = '<span style="margin-right:4px;">!</span> Pool habis. Lanjutkan dengan peserta yang sudah dipilih.';
                if(spinOneBtn) spinOneBtn.closest('.spin-controls').insertAdjacentElement('afterend', msg);
            }
            
            // Allow setting bracket anyway
            if (typeof openSettingsModal === 'function') {
                const btnContainer = document.createElement('div');
                btnContainer.id = 'dynamic-start-btn';
                btnContainer.style.cssText = 'margin-top:12px;text-align:center;';
                btnContainer.innerHTML = `
                    <button type="button" onclick="openSettingsModal()" class="btn btn-success" style="display:inline-flex; align-items:center; gap:8px;">
                        <img src="/images/items/trophy.png" style="width:20px;height:20px;object-fit:contain;" alt=""> Buat Bagan (Bracket)
                    </button>
                `;
                if(spinOneBtn) spinOneBtn.closest('.spin-controls').insertAdjacentElement('afterend', btnContainer);
            }
        }
    }

if(spinOneBtn) {
    spinOneBtn.addEventListener('click', doSpin);
}

if(spinAllBtn) {
    spinAllBtn.addEventListener('click', async () => {
        if (spinning) return;
        spinning = true;
        spinOneBtn.disabled = true;
        spinAllBtn.disabled = true;

        // Animate
        const wheel = document.getElementById('spin-wheel');
        currentRotation += 1080 + Math.random() * 360;
        wheel.style.transition = 'transform 2s cubic-bezier(0.17, 0.67, 0.12, 0.99)';
        wheel.style.transform  = `rotate(${currentRotation}deg)`;

        try {
            const resp = await fetch(window.spinDrawAllUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            });
            const data = await resp.json();
            await new Promise(r => setTimeout(r, 2200));
            if (resp.ok) {
                location.reload();
            } else { 
                showAlert(data.error || 'Gagal spin all'); 
                spinning = false; spinOneBtn.disabled = false; spinAllBtn.disabled = false; 
            }
        } catch (e) {
            await new Promise(r => setTimeout(r, 2200));
            spinning = false;
            spinOneBtn.disabled = false;
            spinAllBtn.disabled = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Guard: disable spin if pool already empty on load
    if (window.spinPool && window.spinPool.length === 0) {
        if(spinOneBtn) spinOneBtn.disabled = true;
        if(spinAllBtn) spinAllBtn.disabled = true;
    }
    
    // Init wheel
    if (window.spinPool) {
        buildWheel(window.spinPool);
    }
});
