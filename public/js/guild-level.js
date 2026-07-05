document.addEventListener('alpine:init', () => {
    Alpine.data('guildLevelCalculator', () => ({
        currentLevel: 1,
        targetLevel: 15,
        currentExp: 0,
        
        // Accurate values for Guild Level
        // Index 1 corresponds to EXP needed from Level 1 to 2
        levelDataXP: [
            0,
            405000,
            1215000,
            2430000,
            4050000,
            7290000,
            11430000,
            16200000,
            21870000,
            28350000,
            38700000,
            48600000,
            60547500,
            73710000,
            91125000
        ],
        
        levelDataGems: [
            0,
            100000,
            125000,
            150000,
            200000,
            250000,
            300000,
            350000,
            400000,
            500000,
            600000,
            700000,
            800000,
            900000,
            1000000
        ],

        init() {

            const key = 'gthelp-guild-level';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const d = JSON.parse(saved);
                    if (d.currentLevel !== undefined) this.currentLevel = d.currentLevel;
                    if (d.targetLevel !== undefined) this.targetLevel = d.targetLevel;
                    if (d.currentExp !== undefined) this.currentExp = d.currentExp;
                }
            } catch(e) {}
            setInterval(() => {
                try { localStorage.setItem(key, JSON.stringify({ currentLevel: this.currentLevel, targetLevel: this.targetLevel, currentExp: this.currentExp })); } catch(e) {}
            }, 1000);
            // Keep target level valid
            this.$watch('currentLevel', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let cl = Number(value);
                if (cl < 1) { this.currentLevel = 1; return; }
                if (cl > 14) { this.currentLevel = 14; return; }
                if (Number(this.targetLevel) <= this.currentLevel && this.currentLevel < 15) {
                    this.targetLevel = Number(this.currentLevel) + 1;
                }
            });

            this.$watch('targetLevel', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let tl = Number(value);
                if (tl < 1) { this.targetLevel = 1; return; }
                if (tl > 15) { this.targetLevel = 15; return; }
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
            if (lvl >= 15) return 0;
            return this.levelDataXP[lvl] || 0;
        },

        calculateTotalExpNeeded() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            let ce = Number(this.currentExp);
            
            if (cl >= tl) return 0;
            
            let total = 0;
            for (let i = cl; i < tl; i++) {
                total += this.levelDataXP[i] || 0;
            }
            
            total -= ce;
            return total > 0 ? total : 0;
        },

        calculateTotalGemsNeeded() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            
            if (cl >= tl) return 0;
            
            let total = 0;
            for (let i = cl; i < tl; i++) {
                total += this.levelDataGems[i] || 0;
            }
            return total;
        },

        calculateExpToNextLevel() {
            let cl = Number(this.currentLevel);
            let ce = Number(this.currentExp);
            if (cl >= 15) return 0;
            
            let needed = (this.levelDataXP[cl] || 0) - ce;
            return needed > 0 ? needed : 0;
        },

        calculateGemsToNextLevel() {
            let cl = Number(this.currentLevel);
            if (cl >= 15) return 0;
            
            return this.levelDataGems[cl] || 0;
        },

        getVisibleBreakdown() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            if (cl >= tl) return [];
            
            let breakdown = [];
            let cumulativeEXP = 0;
            let cumulativeGems = 0;
            
            for (let i = cl; i < tl; i++) {
                let expNeeded = this.levelDataXP[i] || 0;
                let gemsNeeded = this.levelDataGems[i] || 0;
                
                cumulativeEXP += expNeeded;
                cumulativeGems += gemsNeeded;
                
                breakdown.push({
                    levelFrom: i,
                    levelTo: i + 1,
                    expNeeded: expNeeded,
                    gemsNeeded: gemsNeeded,
                    totalEXP: cumulativeEXP,
                    totalGems: cumulativeGems
                });
            }
            
            return breakdown;
        },

        formatNumber(num) {
            if (!num && num !== 0) return '0';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Using commas for Guild XP readability since images used commas
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

