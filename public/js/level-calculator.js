document.addEventListener('alpine:init', () => {
    Alpine.data('levelCalculator', () => ({
        currentLevel: 0,
        targetLevel: 125,
        currentExp: 0,
        
        buffs: {
            charm: true,
            tart: true,
            totemLevel: 0,
            ring: false,
            cookie: false,
            brain: false
        },
        
        // The exact mathematical curve derived from real Growtopia EXP data
        // For any target level (i+1), the EXP required from level i is exactly: 50 * i^2 + 100
        // This flawlessly matches 32,175,000 Total EXP for level 125.
        levelData: Array.from({ length: 125 }, (_, i) => 50 * i * i + 100),

        init() {

            const key = 'gthelp-level-calculator';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const d = JSON.parse(saved);
                    if (d.currentLevel !== undefined) this.currentLevel = d.currentLevel;
                    if (d.targetLevel !== undefined) this.targetLevel = d.targetLevel;
                    if (d.currentExp !== undefined) this.currentExp = d.currentExp;
                    if (d.buffs) Object.assign(this.buffs, d.buffs);
                }
            } catch(e) {}
            setInterval(() => {
                try { localStorage.setItem(key, JSON.stringify({ currentLevel: this.currentLevel, targetLevel: this.targetLevel, currentExp: this.currentExp, buffs: this.buffs })); } catch(e) {}
            }, 1000);
            // Keep target level valid
            this.$watch('currentLevel', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let cl = Number(value);
                if (cl < 0) { this.currentLevel = 0; return; }
                if (cl > 124) { this.currentLevel = 124; return; }
                if (Number(this.targetLevel) <= this.currentLevel && this.currentLevel < 125) {
                    this.targetLevel = Number(this.currentLevel) + 1;
                }
            });

            this.$watch('targetLevel', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let tl = Number(value);
                if (tl < 1) { this.targetLevel = 1; return; }
                if (tl > 125) { this.targetLevel = 125; return; }
            });
            
            this.$watch('currentExp', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let ce = Number(value);
                let needed = this.getExpNeededForNextLevel();
                if (ce < 0) { this.currentExp = 0; return; }
                if (ce > needed) { this.currentExp = needed; return; }
            });
        },

        getExpNeededForNextLevel() {
            let lvl = Number(this.currentLevel);
            if (lvl >= 125) return 0;
            return this.levelData[lvl] || 0;
        },

        calculateTotalExpNeeded() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            let ce = Number(this.currentExp);
            
            if (cl >= tl) return 0;
            
            let total = 0;
            for (let i = cl; i < tl; i++) {
                total += this.levelData[i] || 0;
            }
            
            total -= ce;
            return total > 0 ? total : 0;
        },

        calculateExpToNextLevel() {
            let cl = Number(this.currentLevel);
            let ce = Number(this.currentExp);
            if (cl >= 125) return 0;
            
            let needed = (this.levelData[cl] || 0) - ce;
            return needed > 0 ? needed : 0;
        },
        
        getGhostExp() {
            let base = 1; // Native base EXP for catching a ghost
            if (this.buffs.charm) base += 200;
            if (this.buffs.tart) base += 50;
            
            let multiplier = 1.0;
            
            // Totem: logic mapping level 1-6 to +5% through +10%
            let totemLvl = Number(this.buffs.totemLevel);
            if (totemLvl > 0) {
                multiplier += (totemLvl + 4) / 100; // e.g. lvl 1 = 5%, lvl 6 = 10%
            }
            
            if (this.buffs.ring) multiplier += 0.10; // +10%
            if (this.buffs.cookie) multiplier += 0.20; // 10% chance for 3x (+20% avg)
            if (this.buffs.brain) multiplier += 0.50; // +50%
            
            return Math.ceil(base * multiplier);
        },
        
        getGhostsNeeded() {
            let exp = this.getGhostExp();
            return this.formatNumber(Math.ceil(this.calculateTotalExpNeeded() / exp));
        },

        getVisibleBreakdown() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            if (cl >= tl) return [];
            
            let breakdown = [];
            let cumulative = 0;
            
            for (let i = cl; i < tl; i++) {
                let r = this.levelData[i] || 0;
                cumulative += r;
                breakdown.push({
                    needed: r,
                    total: cumulative
                });
            }
            
            return breakdown;
        },

        formatNumber(num) {
            if (!num && num !== 0) return '0';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        formatNumberText(num) {
            if (!num && num !== 0) return '0';
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1).replace('.0', '') + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1).replace('.0', '') + 'K';
            }
            return num.toString();
        }
    }));
});
