document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        gemsInput: 25000,
        wlCostPerPack: 0,
        stocks: {
            beachBlast: null,
            summerSurprise: null,
            fireworks: null,
        },
        prices: {
            beachBlast: null,
            summerSurprise: null,
            fireworks: null,
        },
        items: {
            beachBlast: { name: 'Beach Blast', type: 'wl/item', icon: 'images/event/summer/beach_blast.png' },
            summerSurprise: { name: 'Summer Surprise', type: 'wl/item', icon: 'images/event/summer/summer_surprise.png' },
            fireworks: { name: 'Fireworks', type: 'per wl', icon: 'images/event/summer/fireworks.png' },
        },
        pathItems: {
            bb: { name: 'Beach Blast', icon: 'images/event/summer/beach_blast.png' },
            ss: { name: 'Summer Surprise', icon: 'images/event/summer/summer_surprise.png' },
            fw: { name: 'Fireworks', icon: 'images/event/summer/fireworks.png' },
            pss: { name: 'Placed Summer Surprise', icon: 'images/event/summer/summer_surprise.png' },
            sss: { name: 'Super Summer Surprise', icon: 'images/event/summer/sss.png' },
            ac: { name: 'Summer Artifact Chest', icon: 'images/event/summer/artifact.png' },
            sand: { name: 'Sand', icon: 'images/event/summer/sand.png' }
        },
        comparePaths: [
            { id: 'bb_ss_fw', group: 'Jalur Tanpa AC', label: 'Summer Surprise + Fireworks' },
            { id: 'bb_ss_pss', group: 'Jalur Tanpa AC', label: 'Summer Surprise + Placed Summer Surprise' },
            { id: 'bb_ss_sss', group: 'Jalur Tanpa AC', label: 'Summer Surprise + Super Summer Surprise' },
            { id: 'bb_sss_fw', group: 'Jalur Tanpa AC', label: 'Super Summer Surprise + Fireworks' },
            { id: 'bb_sss_pss', group: 'Jalur Tanpa AC', label: 'Super Summer Surprise + Placed Summer Surprise' },
            { id: 'bb_sss', group: 'Jalur Tanpa AC', label: 'Super Summer Surprise' },
            { id: 'bb_ac_ss_fw', group: 'Jalur Dengan AC', label: 'Summer Artifact Chest + Summer Surprise + Fireworks' },
            { id: 'bb_ac_ss_pss', group: 'Jalur Dengan AC', label: 'Summer Artifact Chest + Summer Surprise + Placed Summer Surprise' },
            { id: 'bb_ac_ss_sss', group: 'Jalur Dengan AC', label: 'Summer Artifact Chest + Summer Surprise + Super Summer Surprise' }
        ],
        comparePrices: { bb: null, ss: null, fw: null, pss: null, sss: null, ac: null, sand: null },
        compareTypes: { bb: 'wl/item', ss: 'wl/item', fw: 'per wl', pss: 'wl/item', sss: 'wl/item', ac: 'wl/item', sand: 'per wl' },
        init() {
            const key = 'gthelp-summerfest-pack-v2';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const data = JSON.parse(saved);
                    if (data.items) {
                        for (let k in data.items) {
                            if (this.items && this.items[k] && data.items[k].type !== undefined) {
                                this.items[k].type = data.items[k].type;
                            }
                        }
                    }
                    if (data.numPacks !== undefined) this.numPacks = data.numPacks;
                    if (data.gemsInput !== undefined) this.gemsInput = data.gemsInput;
                    if (data.wlCostPerPack !== undefined) this.wlCostPerPack = data.wlCostPerPack;
                    if (data.prices) Object.assign(this.prices, data.prices);
                    if (data.stocks) Object.assign(this.stocks, data.stocks);
                    if (data.comparePrices) Object.assign(this.comparePrices, data.comparePrices);
                    if (data.compareTypes) Object.assign(this.compareTypes, data.compareTypes);
                }
            } catch (e) { }
            // Save every second
            setInterval(() => this._save(key), 1000);

            // Watch for numPacks to auto-calculate stocks
            this.$watch('numPacks', (newVal) => {
                let n = Number(newVal);
                if (n >= 0) {
                    let currentGems = Number(this.gemsInput);
                    if (Math.floor(currentGems / 25000) !== n) {
                        this.gemsInput = n * 25000;
                    }
                    this.stocks.beachBlast = n * 1;
                    this.stocks.summerSurprise = n * 1;
                    this.stocks.fireworks = n * 100;
                }
            });

            this.$watch('gemsInput', (newVal) => {
                let g = Number(newVal);
                if (g >= 0) {
                    let n = Math.floor(g / 25000);
                    if (this.numPacks !== n) {
                        this.numPacks = n;
                    }
                }
            });

            // Initialize once on load if empty
            if (!this.stocks.beachBlast) this.stocks.beachBlast = this.numPacks * 1;
            if (!this.stocks.summerSurprise) this.stocks.summerSurprise = this.numPacks * 1;
            if (!this.stocks.fireworks) this.stocks.fireworks = this.numPacks * 100;

            // Sync comparison prices with main table initially
            this.comparePrices.bb = this.prices.beachBlast || null;
            this.comparePrices.ss = this.prices.summerSurprise || null;
            this.comparePrices.fw = this.prices.fireworks || null;
            if (this.items.beachBlast) this.compareTypes.bb = this.items.beachBlast.type;
            if (this.items.summerSurprise) this.compareTypes.ss = this.items.summerSurprise.type;
            if (this.items.fireworks) this.compareTypes.fw = this.items.fireworks.type;

            this.$watch('prices', (newVal) => {
                this.comparePrices.bb = newVal.beachBlast || null;
                this.comparePrices.ss = newVal.summerSurprise || null;
                this.comparePrices.fw = newVal.fireworks || null;
            }, { deep: true });

            this.$watch('items', (newVal) => {
                if (newVal.beachBlast) this.compareTypes.bb = newVal.beachBlast.type;
                if (newVal.summerSurprise) this.compareTypes.ss = newVal.summerSurprise.type;
                if (newVal.fireworks) this.compareTypes.fw = newVal.fireworks.type;
            }, { deep: true });
        },
        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    items: this.items,
                    numPacks: this.numPacks,
                    gemsInput: this.gemsInput,
                    wlCostPerPack: this.wlCostPerPack,
                    prices: this.prices,
                    stocks: this.stocks,
                    comparePrices: this.comparePrices,
                    compareTypes: this.compareTypes
                }));
            } catch (e) { }
        },

        get totalCost() {
            let p = Math.max(0, Math.floor(Number(this.numPacks) || 0));
            let c = Math.max(0, Number(this.wlCostPerPack) || 0);
            return p * c;
        },

        getItemRevenue(key) {
            let item = this.items[key];
            let val = parseFloat(this.prices[key]);
            if (!val || val <= 0) return 0;

            let totalItems = this.stocks[key] || 0;
            if (totalItems <= 0) return 0;

            if (item.type === 'per wl') {
                return Math.floor(totalItems / val);
            } else if (item.type === '200/?') {
                return Math.floor((totalItems / 200) * val);
            } else if (item.type === 'wl/item') {
                return Math.floor(totalItems * val);
            }
            return 0;
        },

        get totalRevenue() {
            let rev = 0;
            for (let key in this.items) {
                rev += this.getItemRevenue(key);
            }
            return rev;
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
        },

        formatTotalCost() {
            return `${this.totalCost.toFixed(1).replace(/\.0$/, '')} WL`;
        },
        formatTotalRevenue() {
            return `${this.totalRevenue.toFixed(1).replace(/\.0$/, '')} WL`;
        },
        formatNetProfit() {
            let p = this.netProfit;
            let sign = p > 0 ? '+' : '';
            return `${sign}${p.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        getPathYield(strategyId) {
            let nPacks = Math.max(0, Math.floor(Number(this.numPacks) || 0));
            let bb = nPacks;
            let ss = nPacks;
            let fw = nPacks * 100;
            let pss = 0;
            let sss = 0;
            let ac = 0;
            let sand = 0;

            if (strategyId.startsWith('bb_ac')) {
                let maxAc = Math.floor(ss / 5);
                ac = maxAc;
                ss -= ac * 5;
                fw -= ac * 100;
                sand = ac > 0 ? -(ac * 10) : 0;

                if (strategyId === 'bb_ac_ss_fw') {
                } else if (strategyId === 'bb_ac_ss_pss') {
                    pss = Math.floor(fw / 100);
                    fw -= pss * 100;
                } else if (strategyId === 'bb_ac_ss_sss') {
                    pss = Math.floor(fw / 100);
                    fw -= pss * 100;
                    
                    let revSS = this.getUnitValueInWL('ss');
                    let revPSS = this.getUnitValueInWL('pss');
                    let totalPool = ss + pss;
                    let newSss = Math.floor(totalPool / 20);
                    sss += newSss;
                    let consumed = newSss * 20;
                    
                    if (revSS < revPSS) {
                        let consumeSS = Math.min(ss, consumed);
                        ss -= consumeSS;
                        pss -= (consumed - consumeSS);
                    } else {
                        let consumePSS = Math.min(pss, consumed);
                        pss -= consumePSS;
                        ss -= (consumed - consumePSS);
                    }
                }
            } else {
                if (strategyId === 'bb_ss_fw') {
                } else if (strategyId === 'bb_ss_pss') {
                    pss = Math.floor(fw / 100);
                    fw -= pss * 100;
                } else if (strategyId === 'bb_ss_sss') {
                    pss = Math.floor(fw / 100);
                    fw -= pss * 100;
                    sss += Math.floor(pss / 20);
                    pss = pss % 20;
                } else if (strategyId === 'bb_sss_fw') {
                    sss += Math.floor(ss / 20);
                    ss = ss % 20;
                } else if (strategyId === 'bb_sss_pss') {
                    sss += Math.floor(ss / 20);
                    ss = ss % 20;
                    pss = Math.floor(fw / 100);
                    fw -= pss * 100;
                } else if (strategyId === 'bb_sss') {
                    pss = Math.floor(fw / 100);
                    fw -= pss * 100;
                    
                    let revSS = this.getUnitValueInWL('ss');
                    let revPSS = this.getUnitValueInWL('pss');
                    let totalPool = ss + pss;
                    let newSss = Math.floor(totalPool / 20);
                    sss += newSss;
                    let consumed = newSss * 20;
                    
                    if (revSS < revPSS) {
                        let consumeSS = Math.min(ss, consumed);
                        ss -= consumeSS;
                        pss -= (consumed - consumeSS);
                    } else {
                        let consumePSS = Math.min(pss, consumed);
                        pss -= consumePSS;
                        ss -= (consumed - consumePSS);
                    }
                }
            }

            return { bb, ss, fw, pss, sss, ac, sand };
        },

        get sortedPaths() {
            let sorted = [...this.comparePaths].sort((a, b) => {
                return this.getPathProfit(b) - this.getPathProfit(a);
            });
            sorted.forEach((p, i) => p._rank = i + 1);
            return sorted;
        },
        getPathRevenue(path) {
            let yields = this.getPathYield(path.id);
            let rev = 0;
            for (let itemId in yields) {
                let qty = yields[itemId];
                if (qty === 0) continue;
                
                let isCost = qty < 0;
                let itemRev = this.getItemRevenueHelper(itemId, Math.abs(qty), isCost);
                if (isCost) {
                    rev -= itemRev;
                } else {
                    rev += itemRev;
                }
            }
            return rev;
        },
        getPathProfit(path) {
            return parseFloat((this.getPathRevenue(path) - this.totalCost).toFixed(2));
        },
        formatPathRevenue(path) {
            return `${this.getPathRevenue(path).toFixed(1).replace(/\.0$/, '')} WL`;
        },
        formatPathProfit(path) {
            let p = this.getPathProfit(path);
            let sign = p > 0 ? '+' : '';
            return `${sign}${p.toFixed(1).replace(/\.0$/, '')} WL`;
        },
        getPathYieldString(path) {
            let yields = this.getPathYield(path.id);
            let parts = [];
            for (let itemId in yields) {
                if (yields[itemId] > 0 && this.pathItems[itemId]) {
                    parts.push(`${yields[itemId]} ${this.pathItems[itemId].name.replace(' (Cost)', '')}`);
                }
            }
            if (yields.sand < 0) {
                parts.push(`(-${Math.abs(yields.sand)} Sand required)`);
            }
            return parts.join(', ');
        },
        getPathExplanations(strategyId) {
            // Single source of truth: all numbers derived from getPathYield.
            // y.bb === clamped nPacks because BB is never converted.
            let y = this.getPathYield(strategyId);
            let nPacks = y.bb; // no separate Math.floor/Math.max needed
            let steps = [];

            if (strategyId.startsWith('bb_ac')) {
                if (y.ac > 0) {
                    steps.push(`Craft ${y.ac} Summer Artifact Chest using ${y.ac*5} Summer Surprise, ${y.ac*100} Fireworks, and ${y.ac*10} Sand.`);
                }
                if (strategyId === 'bb_ac_ss_fw') {
                    // no further steps
                } else if (strategyId === 'bb_ac_ss_pss') {
                    if (y.pss > 0) steps.push(`Use ${y.pss*100} Fireworks to get ${y.pss} Placed Summer Surprise.`);
                } else if (strategyId === 'bb_ac_ss_sss') {
                    // ssAfterAC = SS before SSS pool = nPacks - AC*5
                    let ssAfterAC = nPacks - y.ac * 5;
                    if (y.sss > 0) {
                        // Derive consumed amounts from yield delta â€” no price logic needed
                        let consumeSS = ssAfterAC - y.ss;
                        let consumePSS = (y.sss * 20) - consumeSS;
                        let pssProduced = y.pss + consumePSS;
                        if (pssProduced > 0) steps.push(`Use ${pssProduced*100} Fireworks to get ${pssProduced} Placed Summer Surprise.`);
                        if (consumeSS === 0) {
                            steps.push(`Break ${consumePSS} Placed SS into ${y.sss} Super Summer Surprise.`);
                        } else if (consumePSS === 0) {
                            steps.push(`Break ${consumeSS} SS into ${y.sss} Super Summer Surprise.`);
                        } else {
                            steps.push(`Combine & Break ${consumeSS} SS and ${consumePSS} Placed SS into ${y.sss} Super Summer Surprise.`);
                        }
                    } else if (y.pss > 0) {
                        steps.push(`Use ${y.pss*100} Fireworks to get ${y.pss} Placed Summer Surprise.`);
                    }
                }
            } else {
                if (strategyId === 'bb_ss_fw') {
                    steps.push("No conversions performed. All items kept in raw form.");
                } else if (strategyId === 'bb_ss_pss') {
                    if (y.pss > 0) steps.push(`Use ${y.pss*100} Fireworks to get ${y.pss} Placed Summer Surprise.`);
                } else if (strategyId === 'bb_ss_sss') {
                    // pss total produced = pss remainder + pss consumed breaking SSS
                    let pssProduced = y.pss + y.sss * 20;
                    if (pssProduced > 0) steps.push(`Use ${pssProduced*100} Fireworks to get ${pssProduced} Placed Summer Surprise.`);
                    if (y.sss > 0) steps.push(`Break ${y.sss*20} Placed Summer Surprise into ${y.sss} Super Summer Surprise.`);
                } else if (strategyId === 'bb_sss_fw') {
                    if (y.sss > 0) steps.push(`Break ${y.sss*20} Summer Surprise into ${y.sss} Super Summer Surprise.`);
                } else if (strategyId === 'bb_sss_pss') {
                    if (y.sss > 0) steps.push(`Break ${y.sss*20} Summer Surprise into ${y.sss} Super Summer Surprise.`);
                    if (y.pss > 0) steps.push(`Use ${y.pss*100} Fireworks to get ${y.pss} Placed Summer Surprise.`);
                } else if (strategyId === 'bb_sss') {
                    if (y.sss > 0) {
                        // Derive consumed amounts from yield delta â€” no price logic needed
                        let consumeSS = nPacks - y.ss;
                        let consumePSS = (y.sss * 20) - consumeSS;
                        let pssProduced = y.pss + consumePSS;
                        if (pssProduced > 0) steps.push(`Use ${pssProduced*100} Fireworks to get ${pssProduced} Placed Summer Surprise.`);
                        if (consumeSS === 0) {
                            steps.push(`Break ${consumePSS} Placed SS into ${y.sss} Super Summer Surprise.`);
                        } else if (consumePSS === 0) {
                            steps.push(`Break ${consumeSS} SS into ${y.sss} Super Summer Surprise.`);
                        } else {
                            steps.push(`Combine & Break ${consumeSS} SS and ${consumePSS} Placed SS into ${y.sss} Super Summer Surprise.`);
                        }
                    } else if (y.pss > 0) {
                        steps.push(`Use ${y.pss*100} Fireworks to get ${y.pss} Placed Summer Surprise.`);
                    }
                }
            }
            
            if (steps.length === 0) {
                steps.push("Not enough items to perform any conversions.");
            }
            return steps;
        },

        getUnitValueInWL(itemId) {
            let type = this.compareTypes[itemId];
            let val = parseFloat(this.comparePrices[itemId]);
            if (!val || val <= 0) return 0;
            if (type === 'per wl') return 1 / val;
            if (type === '200/?') return val / 200;
            if (type === 'wl/item') return val;
            return 0;
        },
        getItemRevenueHelper(itemId, absQty, isCost = false) {
            let type = this.compareTypes[itemId];
            let val = parseFloat(this.comparePrices[itemId]);
            if (!val || val <= 0) return 0;
            if (type === 'per wl') return isCost ? Math.ceil(absQty / val) : Math.floor(absQty / val);
            if (type === '200/?') return isCost ? Math.ceil((absQty / 200) * val) : Math.floor((absQty / 200) * val);
            if (type === 'wl/item') return isCost ? Math.ceil(absQty * val) : Math.floor(absQty * val);
            return 0;
        },
        getSellBreakdown(pathId) {
            let yields = this.getPathYield(pathId);
            let res = [];

            for (let itemId in yields) {
                let qty = yields[itemId];
                if (qty === 0) continue;
                
                let isCost = qty < 0;
                let absQty = Math.abs(qty);
                let rev = this.getItemRevenueHelper(itemId, absQty, isCost);
                let val = parseFloat(this.comparePrices[itemId]) || 0;
                let typeStr = '';
                if(this.compareTypes[itemId] === 'wl/item') typeStr = `${val} WL/item`;
                if(this.compareTypes[itemId] === 'per wl') typeStr = `${val} items/WL`;
                if(this.compareTypes[itemId] === '200/?') typeStr = `${val} WL/200`;
                if(val === 0) typeStr = '0 WL';

                let itemObj = {
                    itemId: itemId,
                    name: this.pathItems[itemId].name.replace(' (Cost)', ''),
                    icon: this.pathItems[itemId].icon,
                    qty: absQty,
                    valStr: typeStr,
                    rev: rev,
                    isCost: qty < 0,
                    sourceIcon: null,
                    sourceText: null,
                    silentZeroWarning: (rev === 0 && !isCost && val > 0 && absQty > 0)
                };

                if (qty > 0) {
                    if (itemId === 'pss') {
                        itemObj.sourceIcon = this.pathItems['fw'].icon;
                        itemObj.sourceText = 'Fireworks';
                    } else if (itemId === 'sss') {
                        if (pathId === 'bb_sss_fw' || pathId === 'bb_sss_pss') {
                            itemObj.sourceIcon = this.pathItems['ss'].icon;
                            itemObj.sourceText = 'SS';
                        } else if (pathId === 'bb_ss_sss') {
                            itemObj.sourceIcon = this.pathItems['pss'].icon;
                            itemObj.sourceText = 'Placed SS';
                        } else if (pathId === 'bb_sss' || pathId === 'bb_ac_ss_sss') {
                            itemObj.sourceIcon = this.pathItems['ss'].icon; 
                            itemObj.sourceText = 'SS & Placed SS';
                        }
                    }
                } else {
                    if (itemId === 'sand') {
                        itemObj.name = 'Sand Cost';
                    }
                }

                res.push(itemObj);
            }
            return res;
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-summerfest-pack-v2'); } catch (e) { }
            this.numPacks = 1;
            this.gemsInput = 25000;
            this.wlCostPerPack = 0;
            for (let key in this.prices) this.prices[key] = null;
            this.stocks.beachBlast = 1;
            this.stocks.summerSurprise = 1;
            this.stocks.fireworks = 100;
            if (this.items && this.items.beachBlast) this.items.beachBlast.type = 'wl/item';
            if (this.items && this.items.summerSurprise) this.items.summerSurprise.type = 'wl/item';
            if (this.items && this.items.fireworks) this.items.fireworks.type = 'per wl';
            for (let key in this.comparePrices) this.comparePrices[key] = null;
            this.compareTypes = { bb: 'wl/item', ss: 'wl/item', fw: 'per wl', pss: 'wl/item', sss: 'wl/item', ac: 'wl/item', sand: 'per wl' };
        },
        resetTable() {
            for (let key in this.prices) this.prices[key] = null;
            let n = Number(this.numPacks);
            if (n >= 0) {
                this.stocks.beachBlast = n * 1;
                this.stocks.summerSurprise = n * 1;
                this.stocks.fireworks = n * 100;
            }
            if (this.items && this.items.beachBlast) this.items.beachBlast.type = 'wl/item';
            if (this.items && this.items.summerSurprise) this.items.summerSurprise.type = 'wl/item';
            if (this.items && this.items.fireworks) this.items.fireworks.type = 'per wl';
            for (let key in this.comparePrices) this.comparePrices[key] = null;
            this.compareTypes = { bb: 'wl/item', ss: 'wl/item', fw: 'per wl', pss: 'wl/item', sss: 'wl/item', ac: 'wl/item', sand: 'per wl' };
        }
    }));
});

