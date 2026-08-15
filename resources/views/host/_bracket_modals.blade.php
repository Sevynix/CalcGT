<!-- Alpine Modal -->
<div x-show="modalOpen" style="display:none;" class="modal-backdrop">
    <div class="modal-box" @click.away="modalOpen = false">
        <h3 x-text="modalTitle" class="modal-title"></h3>
        
        <!-- Assign Participant Modal Body -->
        <div x-show="modalType === 'assign'">
            <p class="muted" style="font-size:13px; margin-bottom:12px;">Pilih peserta untuk mengisi slot ini:</p>
            <select x-model="selectedParticipant" class="form-control" style="width:100%; margin-bottom: 20px;">
                <option value="">-- Pilih Peserta --</option>
                <template x-for="p in unassigned" :key="p">
                    <option :value="p" x-text="p"></option>
                </template>
            </select>
            <div class="flex gap-2" style="justify-content:flex-end;">
                <button class="btn btn-ghost" @click="modalOpen = false">Batal</button>
                <button class="btn btn-primary" @click="submitAssign()" :disabled="!selectedParticipant">Simpan</button>
            </div>
        </div>

        <!-- Confirm Winner Modal Body -->
        <div x-show="modalType === 'correct_winner'">
            <p class="muted">Koreksi pemenang menjadi <strong x-text="selectedParticipant" style="color:#fff;"></strong>?</p>
            <div class="flex gap-2 mt-4" style="justify-content:flex-end;">
                <button class="btn btn-ghost" @click="modalOpen = false">Batal</button>
                <button class="btn btn-danger" @click="submitCorrectWinner()">Ya, Koreksi</button>
            </div>
        </div>
        
        <div x-show="modalType === 'set_winner'">
            <p class="muted">Tetapkan <strong x-text="selectedParticipant" style="color:#fff;"></strong> sebagai pemenang ronde ini?</p>
            <div class="flex gap-2 mt-4" style="justify-content:flex-end;">
                <button class="btn btn-ghost" @click="modalOpen = false">Batal</button>
                <button class="btn btn-success" @click="submitSetWinner()">Tetapkan</button>
            </div>
        </div>
    </div>
</div>
