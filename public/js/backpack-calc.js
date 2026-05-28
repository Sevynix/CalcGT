document.addEventListener('alpine:init', () => {
    Alpine.data('backpackCalculator', () => ({
        currentSlots: 16,
        targetSlots: 396,

        // Data arrays to store the pre-calculated costs for instant lookup
        costs: [],
        cumulative: [],
        maxUpgrades: 38,
        
        init() {
            this.generateUpgradeData();

            const key = 'gthelp-backpack-calc';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const d = JSON.parse(saved);
                    if (d.currentSlots !== undefined) this.currentSlots = d.currentSlots;
                    if (d.targetSlots !== undefined) this.targetSlots = d.targetSlots;
                }
            } catch(e) {}
            setInterval(() => {
                try { localStorage.setItem(key, JSON.stringify({ currentSlots: this.currentSlots, targetSlots: this.targetSlots })); } catch(e) {}
            }, 1000);

            // Validate currentSlots
            this.$watch('currentSlots', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let slots = Number(value);
                
                // Snap to round UP to the next 10 with base 16 (e.g. 16, 26, 36)
                if ((slots - 16) % 10 !== 0) {
                    slots = 16 + Math.ceil((slots - 16) / 10) * 10;
                }

                if (slots < 16) { slots = 16; }
                if (slots > 386) { slots = 386; }
                
                if (Number(this.currentSlots) !== slots) {
                    this.currentSlots = slots;
                }
                
                if (Number(this.targetSlots) <= slots && slots <= 386) {
                    this.targetSlots = slots + 10;
                }
            });

            // Validate targetSlots
            this.$watch('targetSlots', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let slots = Number(value);
                
                if ((slots - 16) % 10 !== 0) {
                    slots = 16 + Math.ceil((slots - 16) / 10) * 10;
                }

                if (slots < 26) { slots = 26; }
                if (slots > 396) { slots = 396; }
                
                if (Number(this.targetSlots) !== slots) {
                    this.targetSlots = slots;
                }
            });
        },
        generateUpgradeData() {
            // Index 0 represents the state at 16 slots (0 upgrades)
            this.costs = [0, 100];
            this.cumulative = [0, 100];
            
            let currCost = 100;
            let diff = 100;
            let cumTotal = 100;

            for (let i = 2; i <= this.maxUpgrades; i++) {
                if (i > 2) {
                    diff += 200;
                }
                currCost += diff;
                cumTotal += currCost;
                
                this.costs.push(currCost);
                this.cumulative.push(cumTotal);
            }
        },

        getUpgradeIndex(slots) {
            // Converts slot amount (16, 26, 36...) to upgrade step (0, 1, 2...). We apply Math.ceil as a fail-safe.
            return Math.max(0, Math.ceil((slots - 16) / 10));
        },

        calculateTotalGemsNeeded() {
            let clIndex = this.getUpgradeIndex(Number(this.currentSlots));
            let tlIndex = this.getUpgradeIndex(Number(this.targetSlots));
            
            if (clIndex >= tlIndex) return 0;
            
            // The cost to target is target cumulative minus current cumulative
            return this.cumulative[tlIndex] - this.cumulative[clIndex];
        },

        calculateUpgradesNeeded() {
            let clIndex = this.getUpgradeIndex(Number(this.currentSlots));
            let tlIndex = this.getUpgradeIndex(Number(this.targetSlots));
            
            return Math.max(0, tlIndex - clIndex);
        },

        calculateGemsToNextUpgrade() {
            let clIndex = this.getUpgradeIndex(Number(this.currentSlots));
            if (clIndex >= this.maxUpgrades) return 0;
            
            return this.costs[clIndex + 1] || 0;
        },

        getVisibleBreakdown() {
            let clIndex = this.getUpgradeIndex(Number(this.currentSlots));
            let tlIndex = this.getUpgradeIndex(Number(this.targetSlots));
            if (clIndex >= tlIndex) return [];
            
            let breakdown = [];
            let cumulativeGems = 0;
            
            for (let i = clIndex + 1; i <= tlIndex; i++) {
                let cost = this.costs[i];
                cumulativeGems += cost;
                
                breakdown.push({
                    upgradeStep: i,
                    slotsResult: 16 + (i * 10),
                    cost: cost,
                    cumulativePhaseCost: cumulativeGems,
                    absoluteCumulativeCost: this.cumulative[i] // Total since creation
                });
            }
            
            return breakdown;
        },

        formatNumber(num) {
            if (!num && num !== 0) return '0';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }));
});
