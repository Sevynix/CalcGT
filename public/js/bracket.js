function showPhase(phase, btn) {
    document.getElementById('phase-1-wrap').style.display = phase === 1 ? 'block' : 'none';
    const p2 = document.getElementById('phase-2-wrap');
    if (p2) p2.style.display = phase === 2 ? 'block' : 'none';
    document.querySelectorAll('.phase-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
}

document.addEventListener('alpine:init', () => {
    Alpine.data('bracketUI', () => ({
        modalOpen: false,
        modalType: '', // 'assign', 'correct_winner', 'set_winner'
        modalTitle: '',
        targetNodeId: null,
        targetSlot: null,
        selectedParticipant: '',
        unassigned: window.unassignedParticipants || [],

        openAssign(nodeId, slot) {
            if (this.unassigned.length === 0) {
                showAlert('Semua peserta sudah dimasukkan ke dalam bagan.');
                return;
            }
            this.targetNodeId = nodeId;
            this.targetSlot = slot;
            this.selectedParticipant = '';
            this.modalType = 'assign';
            this.modalTitle = 'Assign Participant';
            this.modalOpen = true;
        },

        openSetWinner(nodeId, winner) {
            this.targetNodeId = nodeId;
            this.selectedParticipant = winner;
            this.modalType = 'set_winner';
            this.modalTitle = 'Tetapkan Pemenang';
            this.modalOpen = true;
        },

        openCorrectWinner(nodeId, winner) {
            this.targetNodeId = nodeId;
            this.selectedParticipant = winner;
            this.modalType = 'correct_winner';
            this.modalTitle = 'Koreksi Pemenang';
            this.modalOpen = true;
        },

        async submitAssign() {
            if (!this.selectedParticipant) return;
            const resp = await fetch(`/host/bracket-node/${this.targetNodeId}/assign-participant`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ slot: this.targetSlot, participant: this.selectedParticipant }),
            });
            const data = await resp.json();
            if (resp.ok) location.reload();
            else showAlert(data.error || 'Terjadi kesalahan');
        },

        async submitSetWinner() {
            const resp = await fetch(`/host/bracket-node/${this.targetNodeId}/set-winner`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ winner: this.selectedParticipant }),
            });
            const data = await resp.json();
            if (resp.ok) location.reload();
            else showAlert(data.error || 'Terjadi kesalahan');
        },

        async submitCorrectWinner() {
            const resp = await fetch(`/host/bracket-node/${this.targetNodeId}/correct-winner`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ winner: this.selectedParticipant }),
            });
            const data = await resp.json();
            if (resp.ok) location.reload();
            else showAlert(data.error || 'Terjadi kesalahan');
        }
    }));
});
