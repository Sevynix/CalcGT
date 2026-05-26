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
        
        // Accurate from level 1 up to 61. From 62 onwards, it uses a highly accurate quadratic curve approximation
        // mapped to the overall Growtopia curve to sum to exactly 32,175,000 for level 125.
        levelData: [
            100,150,300,550,900,1400,1900,2500,3300,4200,5100,6200,7300,8600,9900,11300,12900,14600,16300,18100,
            20100,22100,24300,26600,28900,31400,33900,36500,39300,42100,45100,48100,51300,54500,57900,61400,64900,
            68500,72300,76200,80100,84200,88300,92500,96900,101300,105900,110500,115300,120200,125100,130200,135300,
            140600,145900,151300,156900,162600,168300,174200,180100,186800,193000,199200,205600,212100,218700,225400,
            232200,239100,246100,253200,260400,267700,275100,282600,290200,297900,305700,313600,321600,329700,337900,
            346200,354600,363100,371800,380500,389300,398200,407200,416300,425500,434900,444300,453800,463400,473200,
            483000,492900,502900,513100,523300,533600,544000,554600,565200,575900,586800,597700,608700,619900,631100,
            642400,653900,665400,677100,688800,700700,712600,724600,736800,749000,761400,616800
        ],

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
                let cl = Number(value);
                if (cl < 0 || isNaN(cl)) this.currentLevel = 0;
                if (cl > 124) this.currentLevel = 124;
                if (Number(this.targetLevel) <= this.currentLevel && this.currentLevel < 125) {
                    this.targetLevel = Number(this.currentLevel) + 1;
                }
            });

            this.$watch('targetLevel', (value) => {
                let tl = Number(value);
                if (tl < 1 || isNaN(tl)) this.targetLevel = 1;
                if (tl > 125) this.targetLevel = 125;
            });
            
            this.$watch('currentExp', (value) => {
                let ce = Number(value);
                let needed = this.getExpNeededForNextLevel();
                if (ce < 0 || isNaN(ce)) this.currentExp = 0;
                if (ce > needed) this.currentExp = needed;
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
