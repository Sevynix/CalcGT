document.addEventListener('alpine:init', () => {
    Alpine.data('nanoforgeSim', () => ({
        // isFuel tracks what goes into the nanoforge
        // isTarget tracks what we calculate profit ON
        tools: [
            { name: 'AI Brain', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Cyborg Diplomat', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Galactibolt', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Gigablaster', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Growton Torpedo', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'HyperShields', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Quadriscanner', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Space Meds', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Star Supplies', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Stellar Documents', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Tactical Drone', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Teleporter Charge', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
        ],

        getAssetPath(name) {
            const map = {
                'AI Brain': 'ai',
                'Cyborg Diplomat': 'cyborg',
                'Galactibolt': 'bolt',
                'Gigablaster': 'giga',
                'Growton Torpedo': 'torpedo',
                'HyperShields': 'shield',
                'Quadriscanner': 'qscan',
                'Space Meds': 'meds',
                'Star Supplies': 'supplies',
                'Stellar Documents': 'doc',
                'Tactical Drone': 'tac',
                'Teleporter Charge': 'tele'
            };
            return `/images/startopia/startopia_tools/${map[name] || name}.png`;
        },

        formatWL(val) {
            return `${parseFloat(val).toFixed(2).replace(/\.00$/, '').replace(/(\.[0-9])0$/, '$1')} WL`;
        },

        resetData() {
            this.tools.forEach(t => {
                t.stock = '';
                t.buyPrice = '';
                t.sellPrice = '';
                t.isFuel = false;
                t.isTarget = false;
            });
        },

        get simulation() {
            let currentCounts = this.tools.map(t => (t.isFuel && typeof t.stock === 'number') ? t.stock : 0);
            let totalCycles = 0;
            let loopLimit = 10000;
            
            let changed = true;
            let iters = 0;
            while(changed && iters < loopLimit) {
                changed = false;
                iters++;
                
                for(let i=0; i < this.tools.length; i++) {
                    // Nanoforge any tool that is NOT a Target. 
                    if(!this.tools[i].isTarget && currentCounts[i] >= 20) {
                        let cyclesAvailable = Math.floor(currentCounts[i] / 20);
                        currentCounts[i] -= (cyclesAvailable * 20);
                        
                        // Add 1 to all other tools
                        for(let j=0; j < this.tools.length; j++) {
                            if(i !== j) {
                                currentCounts[j] += cyclesAvailable;
                            }
                        }
                        
                        totalCycles += cyclesAvailable;
                        changed = true;
                    }
                }
            }
            
            return {
                finalCounts: currentCounts,
                cycles: totalCycles
            };
        },

        getFinalCount(index) {
            return Math.floor(this.simulation.finalCounts[index]);
        },

        get totalCyclesRun() {
            return this.simulation.cycles;
        },

        get totalCost() {
            let cost = 0;
            this.tools.forEach(t => {
                let s = typeof t.stock === 'number' ? t.stock : 0;
                let bp = parseFloat(t.buyPrice);
                if(t.isFuel && s > 0 && bp > 0) {
                    if(t.buyType === 'per wl') {
                        cost += (s / bp);
                    } else if (t.buyType === '200/?') {
                        cost += (s / 200) * bp;
                    } else if (t.buyType === 'wl/item') {
                        cost += (s * bp);
                    }
                }
            });
            return cost || 0;
        },

        get totalRevenue() {
            let rev = 0;
            let finalCounts = this.simulation.finalCounts;
            
            this.tools.forEach((t, i) => {
                const count = Math.floor(finalCounts[i]);
                const sp = parseFloat(t.sellPrice);
                
                if(t.isTarget && count > 0 && sp > 0) {
                    if(t.sellType === 'per wl') {
                        rev += Math.floor(count / sp);
                    } else if (t.sellType === '200/?') {
                        rev += Math.floor((count / 200) * sp);
                    } else if (t.sellType === 'wl/item') {
                        rev += Math.floor(count * sp);
                    }
                }
            });
            return rev || 0;
        },

        get netProfit() {
            return parseFloat((this.totalRevenue - this.totalCost).toFixed(2));
        },

        get profitPercentage() {
            let cost = this.totalCost;
            if (cost === 0) return this.netProfit > 0 ? '+100.0%' : '+0.0%';
            let pct = (this.netProfit / cost) * 100;
            let sign = pct > 0 ? '+' : '';
            return `${sign}${pct.toFixed(1)}%`;
        }

    }));
});
