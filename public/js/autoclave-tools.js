document.addEventListener('alpine:init', () => {
    Alpine.data('autoclaveSim', () => ({
        // isFuel tracks what goes into the autoclave
        // isTarget tracks what we calculate profit ON
        tools: [
            { name: 'Anesthetic', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Antibiotics', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Antiseptic', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Clamp', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Defibrillator', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Lab Kit', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Pins', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Scalpel', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Splint', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Sponge', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: 'per wl' },
            { name: 'Transfusion', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Ultrasound', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
            { name: 'Stitches', isFuel: false, isTarget: false, stock: '', buyPrice: '', buyType: 'per wl', sellPrice: '', sellType: '200/?' },
        ],

        getAssetPath(name) {
            const formatted = name.toLowerCase().replace(' ', '');
            return `/images/surgery/surg_tools/${formatted}.webp`;
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
                    // Autoclave any tool that is NOT a Target. 
                    // This includes explicit Fuel tools, AND byproducts generated during the process.
                    if(!this.tools[i].isTarget && currentCounts[i] >= 20) {
                        let cyclesAvailable = Math.floor(currentCounts[i] / 20);
                        currentCounts[i] -= (cyclesAvailable * 20);
                        
                        // Add to all other 12 tools
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
                    // Match MSurg calculation natively: floor the resulting revenue
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
