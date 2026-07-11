document.addEventListener('alpine:init', () => {
    Alpine.data('rolesCalculator', () => ({
        rolesList: [
            { id: 'farmer', name: 'Farmer', type: 'supplier', img: 'farmer.png', color: 'emerald' },
            { id: 'surgeon', name: 'Surgeon', type: 'supplier', img: 'surgeon.png', color: 'rose' },
            { id: 'fishing', name: 'Fisher', type: 'supplier', img: 'fishing.png', color: 'blue' },
            { id: 'startopia', name: 'Star Captain', type: 'supplier', img: 'startopia.png', color: 'indigo' },
            { id: 'builder', name: 'Builder', type: 'crafter', img: 'builder.png', color: 'amber' },
            { id: 'chef', name: 'Chef', type: 'crafter', img: 'chef.png', color: 'orange' }
        ],
        selectedRoleId: 'farmer',
        
        currentLevel: 0,
        targetLevel: 10,
        currentPoints: 0,
        questsPerDay: 1,

        buffs: {
            jack: false,
            supplier_cape: false,
            crafter_cape: false
        },

        init() {

            const key = 'gthelp-roles-calc';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const d = JSON.parse(saved);
                    if (d.selectedRoleId !== undefined) this.selectedRoleId = d.selectedRoleId;
                    if (d.currentLevel !== undefined) this.currentLevel = d.currentLevel;
                    if (d.targetLevel !== undefined) this.targetLevel = d.targetLevel;
                    if (d.currentPoints !== undefined) this.currentPoints = d.currentPoints;
                    if (d.questsPerDay !== undefined) this.questsPerDay = d.questsPerDay;
                    if (d.buffs) Object.assign(this.buffs, d.buffs);
                }
            } catch(e) {}
            setInterval(() => {
                try { localStorage.setItem(key, JSON.stringify({ selectedRoleId: this.selectedRoleId, currentLevel: this.currentLevel, targetLevel: this.targetLevel, currentPoints: this.currentPoints, questsPerDay: this.questsPerDay, buffs: this.buffs })); } catch(e) {}
            }, 1000);
            this.$watch('currentLevel', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let lvl = Number(value);
                if (lvl < 0) { this.currentLevel = 0; return; }
                if (lvl > 9) { this.currentLevel = 9; return; }
                
                if (Number(this.targetLevel) <= this.currentLevel) {
                    this.targetLevel = Number(this.currentLevel) + 1;
                }
                this.validatePoints();
            });

            this.$watch('targetLevel', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let lvl = Number(value);
                if (lvl < 1) { this.targetLevel = 1; return; }
                if (lvl > 10) { this.targetLevel = 10; return; }
            });

            this.$watch('currentPoints', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let pts = Number(value);
                if (pts < 0) { this.currentPoints = 0; return; }
                this.validatePoints();
            });

            this.$watch('questsPerDay', (value) => {
                if (value === '' || value === null || Number.isNaN(value)) return;
                let q = Number(value);
                if (q < 1) { this.questsPerDay = 1; return; }
                if (q > 10) { this.questsPerDay = 10; return; }
            });
            
            // Re-validate points bounds when role changes
            this.$watch('selectedRoleId', () => {
                this.validatePoints();
            });
        },

        validatePoints() {
            let maxPointsForLevel = this.getPointsForNextLevel(Number(this.currentLevel));
            if (this.currentPoints > maxPointsForLevel) {
                this.currentPoints = maxPointsForLevel;
            }
        },

        getSelectedRole() {
            return this.rolesList.find(role => role.id === this.selectedRoleId) || this.rolesList[0];
        },

        getMultiplier() {
            let mult = 1.00;
            if (this.buffs.jack) mult += 0.03;
            
            let role = this.getSelectedRole();
            if (this.buffs.supplier_cape && role.type === 'supplier') mult += 0.05;
            if (this.buffs.crafter_cape && role.type === 'crafter') mult += 0.05;
            
            return mult;
        },

        getPointsForNextLevel(level) {
            let role = this.getSelectedRole();
            let base = role.type === 'supplier' ? 1300 : 1500;
            return base * Math.pow(level + 1, 2);
        },

        getQuestYield(level) {
            let role = this.getSelectedRole();
            let base = role.type === 'supplier' ? 270 : 230;
            return Math.floor(base * (level + 1) * this.getMultiplier());
        },

        calculateTotalPointsNeeded() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            if (cl >= tl) return 0;

            let total = 0;
            for (let i = cl; i < tl; i++) {
                total += this.getPointsForNextLevel(i);
            }
            return Math.max(0, total - Number(this.currentPoints));
        },

        calculateTotalQuestsNeeded() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            if (cl >= tl) return 0;

            let totalQuests = 0;
            let startingPoints = Number(this.currentPoints);

            for (let i = cl; i < tl; i++) {
                let pointsNeeded = this.getPointsForNextLevel(i);
                
                // If it's the very first level in the iteration, subtract already gained points
                if (i === cl && startingPoints > 0) {
                    pointsNeeded -= startingPoints;
                }
                
                let yieldPerQuest = this.getQuestYield(i);
                totalQuests += Math.ceil(pointsNeeded / yieldPerQuest);
            }
            return totalQuests;
        },

        getDailyGemCost(quests) {
            if (quests <= 1) return 0;
            let cost = 0;
            let role = this.getSelectedRole();
            let baseCost = (role.id === 'chef' || role.id === 'builder') ? 4000 : 3000;
            for (let q = 2; q <= quests; q++) {
                cost += Math.pow(q - 1, 2) * baseCost;
            }
            return cost;
        },

        calculateTotalGemCost() {
            let totalQuests = this.calculateTotalQuestsNeeded();
            let qPerDay = Number(this.questsPerDay);
            if (qPerDay <= 1 || totalQuests === 0) return 0;
            
            let fullDays = Math.floor(totalQuests / qPerDay);
            let remainderQuests = totalQuests % qPerDay;
            
            return (fullDays * this.getDailyGemCost(qPerDay)) + this.getDailyGemCost(remainderQuests);
        },

        calculateTotalDaysNeeded() {
            let totalQuests = this.calculateTotalQuestsNeeded();
            let qPerDay = Number(this.questsPerDay);
            if (qPerDay <= 0) return 0;
            return Math.ceil(totalQuests / qPerDay);
        },

        getVisibleBreakdown() {
            let cl = Number(this.currentLevel);
            let tl = Number(this.targetLevel);
            if (cl >= tl) return [];
            
            let breakdown = [];
            let cumulativePoints = 0;
            let startingPoints = Number(this.currentPoints);
            
            for (let i = cl; i < tl; i++) {
                let requiredPoints = this.getPointsForNextLevel(i);
                let actualPointsNeeded = requiredPoints;
                
                if (i === cl && startingPoints > 0) {
                    actualPointsNeeded -= startingPoints;
                }
                
                cumulativePoints += actualPointsNeeded;
                let yieldPerQuest = this.getQuestYield(i);
                let questsForPhase = Math.ceil(actualPointsNeeded / yieldPerQuest);
                
                let qPerDay = Number(this.questsPerDay);
                let daysForPhase = Math.ceil(questsForPhase / qPerDay);
                
                let fullDays = Math.floor(questsForPhase / qPerDay);
                let remainderQuests = questsForPhase % qPerDay;
                let gemCostPhase = (fullDays * this.getDailyGemCost(qPerDay)) + this.getDailyGemCost(remainderQuests);
                
                breakdown.push({
                    levelFrom: i,
                    levelTo: i + 1,
                    pointsNeeded: actualPointsNeeded,
                    questYield: yieldPerQuest,
                    questsNeeded: questsForPhase,
                    daysNeeded: daysForPhase,
                    gemCost: gemCostPhase,
                    cumulativePhaseCost: cumulativePoints,
                    absoluteRequired: requiredPoints 
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

